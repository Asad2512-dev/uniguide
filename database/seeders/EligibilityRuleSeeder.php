<?php

namespace Database\Seeders;

use App\Models\EligibilityRule;
use App\Services\EligibilityRulesEngine;
use Illuminate\Database\Seeder;

class EligibilityRuleSeeder extends Seeder
{
    public function run(): void
    {
        $engine = app(EligibilityRulesEngine::class);
        $rules = $engine->getRulesRepository();

        foreach ($rules as $key => $r) {
            EligibilityRule::updateOrCreate(
                ['rule_key' => $key],
                [
                    'title' => $r['title'],
                    'authority' => $r['authority'] ?? 'HEC',
                    'statutory_reference' => $r['statutory_reference'] ?? null,
                    'category' => $r['category'] ?? 'General Regulation',
                    'verified_date' => $r['verified_date'] ?? 'May 2026',
                    'summary' => $r['summary'],
                    'scope' => $r['scope'] ?? 'All Universities',
                    'formula_note' => $r['formula_note'] ?? null,
                    'minimum_percentage' => isset($r['minimum_percentage']) ? floatval($r['minimum_percentage']) : null,
                    'parameters' => $r['parameters'] ?? null,
                ]
            );
        }
    }
}
