<?php

namespace App\Services\UniversityImport;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class HecUniversityImporter
{
    public const URL = 'https://www.hec.gov.pk/english/universities/pages/recognised.aspx';

    /** @return array<string, mixed> */
    public function fetchSnapshot(): array
    {
        $html = Http::connectTimeout(10)->timeout(45)->get(self::URL)->throw()->body();

        return $this->parse($html);
    }

    /** @return array<string, mixed> */
    public function parse(string $html): array
    {
        $previous = libxml_use_internal_errors(true);
        try {
            $document = new DOMDocument;
            $document->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NONET);
            $xpath = new DOMXPath($document);
            $rows = [];
            foreach ($xpath->query('//li[@data-position]') as $card) {
                $metadata = $xpath->query('.//li[@Sector or @sector]', $card)->item(0);
                $link = $xpath->query('.//a[contains(@class, "read-more-arrow")]', $card)->item(0);
                if (! $metadata || ! $link) {
                    throw new RuntimeException('Incomplete HEC card; snapshot not replaced.');
                }
                $url = str_replace('https://www.hec.gov.pk//', 'https://www.hec.gov.pk/', $link->getAttribute('href'));
                $url = str_replace(' ', '%20', $url);
                if (isset($rows[$url])) {
                    throw new RuntimeException('Duplicate HEC source identity; manual review required.');
                }
                $row = ['name' => trim($card->getAttribute('data-position')), 'source_url' => $url];
                foreach (['city', 'province', 'sector', 'charteredby', 'discipline'] as $attribute) {
                    $row[$attribute] = trim($metadata->getAttribute($attribute));
                }
                $rows[$url] = $row;
            }
            if ($rows === []) {
                throw new RuntimeException('No HEC cards found; possible page change or access restriction.');
            }

            return ['schema_version' => 1, 'verified_at' => now()->toIso8601String(), 'source_url' => self::URL,
                'content_sha256' => hash('sha256', $html), 'universities' => array_values($rows),
                'publication_status' => 'research_only', 'release_regions' => config('uniguide.allowed_regions')];
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }
}
