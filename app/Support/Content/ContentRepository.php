<?php

namespace App\Support\Content;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ContentRepository
{
    /**
     * Absolute directory path containing content JSON files.
     */
    protected string $contentPath;

    /**
     * In-memory file cache for the active request lifecycle.
     */
    protected array $cache = [];

    /**
     * Whitelist of safe content file keys to prevent arbitrary path traversal.
     */
    protected array $allowedFiles = [
        'global',
        'navigation',
        'homepage',
        'about',
        'how-it-works',
        'features',
        'contact',
        'universities',
        'programs',
        'admissions',
        'scholarships',
        'comparison',
        'eligibility',
        'ai-counselor',
        'student',
        'university-admin',
        'national-admin',
        'authentication',
        'messages',
        'seo',
    ];

    public function __construct(?string $contentPath = null)
    {
        $this->contentPath = $contentPath ?: resource_path('content');
    }

    /**
     * Retrieve a content value using nested dot-notation.
     * Example: content('homepage.hero.title', 'Default Title', ['count' => 248])
     */
    public function get(string $key, mixed $default = null, array $replace = []): mixed
    {
        $segments = explode('.', $key);
        $fileKey = array_shift($segments);

        if (! $fileKey) {
            return $default;
        }

        $data = $this->loadFile($fileKey);

        if (empty($segments)) {
            $value = $data;
        } else {
            $value = Arr::get($data, implode('.', $segments), $default);
        }

        if (is_string($value) && ! empty($replace)) {
            return $this->interpolate($value, $replace);
        }

        return $value ?? $default;
    }

    /**
     * Check if a specific content key exists.
     */
    public function has(string $key): bool
    {
        $segments = explode('.', $key);
        $fileKey = array_shift($segments);

        if (! $fileKey) {
            return false;
        }

        $data = $this->loadFile($fileKey);

        if (empty($segments)) {
            return ! empty($data);
        }

        return Arr::has($data, implode('.', $segments));
    }

    /**
     * Return all content from a specific file.
     */
    public function all(string $fileKey): array
    {
        return $this->loadFile($fileKey);
    }

    /**
     * Clear the in-memory cache (useful during development / testing).
     */
    public function clear(): void
    {
        $this->cache = [];
    }

    /**
     * Load and parse a content JSON file safely.
     */
    protected function loadFile(string $fileKey): array
    {
        if (isset($this->cache[$fileKey])) {
            return $this->cache[$fileKey];
        }

        // Sanitize file key to ensure it only contains alpha-numeric and dash
        $sanitized = preg_replace('/[^a-zA-Z0-9_\-]/', '', strtolower($fileKey));

        // Enforce approved files or fallback safely
        if (! in_array($sanitized, $this->allowedFiles, true)) {
            $this->cache[$fileKey] = [];

            return [];
        }

        $filePath = $this->contentPath.DIRECTORY_SEPARATOR.$sanitized.'.json';

        if (! File::exists($filePath)) {
            $this->cache[$fileKey] = [];

            return [];
        }

        try {
            $contents = File::get($filePath);
            $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
            $this->cache[$fileKey] = is_array($decoded) ? $decoded : [];
        } catch (\JsonException $e) {
            Log::error("UniGuide ContentRepository: Failed to parse JSON file [{$filePath}]: ".$e->getMessage());
            $this->cache[$fileKey] = [];
        } catch (\Throwable $e) {
            Log::warning("UniGuide ContentRepository: Error reading [{$filePath}]: ".$e->getMessage());
            $this->cache[$fileKey] = [];
        }

        return $this->cache[$fileKey];
    }

    /**
     * Replace :placeholders or {placeholders} in a string.
     */
    protected function interpolate(string $text, array $replace): string
    {
        $search = [];
        $values = [];

        foreach ($replace as $key => $value) {
            $strValue = (string) $value;
            $search[] = ':'.$key;
            $values[] = $strValue;
            $search[] = ':'.strtoupper($key);
            $values[] = $strValue;
            $search[] = '{'.$key.'}';
            $values[] = $strValue;
            $search[] = '{'.strtoupper($key).'}';
            $values[] = $strValue;
        }

        return str_replace($search, $values, $text);
    }
}
