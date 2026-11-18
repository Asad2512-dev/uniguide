<?php

return [
    'options' => [
        'pre_engineering' => ['name' => 'FSc Pre-Engineering', 'desc' => 'Mathematics, Physics, Chemistry', 'icon' => 'cpu'],
        'ics' => ['name' => 'ICS (Computer Science)', 'desc' => 'Computer Science, Mathematics, and your elective subject', 'icon' => 'laptop'],
        'pre_medical' => ['name' => 'FSc Pre-Medical', 'desc' => 'Biology, Chemistry, Physics', 'icon' => 'heart-pulse'],
        'a_levels' => ['name' => 'Cambridge A-Levels', 'desc' => 'Requires an IBCC equivalence certificate', 'icon' => 'globe'],
        'icom' => ['name' => 'I.Com (Commerce)', 'desc' => 'Commerce, Accounting, and Business subjects', 'icon' => 'book-open'],
        'dae' => ['name' => 'Diploma of Associate Engineering (DAE)', 'desc' => '3-Year Technical Board Diploma', 'icon' => 'wrench'],
    ],
    'aliases' => [
        'fsc_pre_eng' => 'pre_engineering',
        'fsc_pre_med' => 'pre_medical',
        'ics_math_stat' => 'ics',
    ],
];
