<?php

return [
    'allowed_regions' => ['punjab', 'islamabad-capital-territory'],
    'region_labels' => [
        'punjab' => 'Punjab', 'islamabad-capital-territory' => 'Islamabad Capital Territory',
        'sindh' => 'Sindh', 'khyber-pakhtunkhwa' => 'Khyber Pakhtunkhwa',
        'balochistan' => 'Balochistan', 'azad-jammu-and-kashmir' => 'Azad Jammu and Kashmir',
        'gilgit-baltistan' => 'Gilgit-Baltistan',
    ],
    'region_aliases' => [
        'ict' => 'islamabad-capital-territory', 'islamabad' => 'islamabad-capital-territory',
        'islamabad-ict' => 'islamabad-capital-territory', 'federal-capital' => 'islamabad-capital-territory',
        'islamabad-federal' => 'islamabad-capital-territory',
        'kpk' => 'khyber-pakhtunkhwa', 'kp' => 'khyber-pakhtunkhwa',
        'ajk' => 'azad-jammu-and-kashmir', 'azad-jammu-kashmir' => 'azad-jammu-and-kashmir',
        'gb' => 'gilgit-baltistan', 'gilgit-baltistan' => 'gilgit-baltistan',
    ],
    'seed_demo_data' => false,
    'ai_coverage_instruction' => 'This UniGuide release covers Punjab and Islamabad Capital Territory only. Retrieve and recommend only permitted campus records. Explain when a requested location is outside coverage. Never infer campus offerings, admission eligibility, fees or deadlines from another campus.',
];
