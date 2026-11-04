<?php

namespace App\Services;

class NationalAdminService
{
    /**
     * 1. National Overview Dashboard Data.
     */
    public function getDashboardData(): array
    {
        $kpis = [
            ['title' => 'Total Indexed Universities', 'value' => '248', 'change' => '100% Chartered', 'changeType' => 'neutral', 'icon' => 'building-2', 'iconColor' => 'brand'],
            ['title' => 'Verified Institutions', 'value' => '214', 'change' => '86.3% Complete Audit', 'changeType' => 'increase', 'icon' => 'shield-check', 'iconColor' => 'success'],
            ['title' => 'Total Campuses & Colleges', 'value' => '412', 'change' => 'Across 6 Regions', 'changeType' => 'neutral', 'icon' => 'map-pin', 'iconColor' => 'brand'],
            ['title' => 'Degree Programs Indexed', 'value' => '3,420', 'change' => '+140 Fall 2026', 'changeType' => 'increase', 'icon' => 'book-open', 'iconColor' => 'brand'],
            ['title' => 'National Data Coverage', 'value' => '96.2%', 'change' => '+2.1% this week', 'changeType' => 'increase', 'icon' => 'database', 'iconColor' => 'success'],
            ['title' => 'Pending Field Verification', 'value' => '38', 'change' => 'Requires HEC Audit', 'changeType' => 'warning', 'icon' => 'clock', 'iconColor' => 'warning'],
            ['title' => 'Scraping Job Health', 'value' => '98.6%', 'change' => '12 Active Workers', 'changeType' => 'increase', 'icon' => 'activity', 'iconColor' => 'success'],
            ['title' => 'AI Chunks Indexed (RAG)', 'value' => '42,850', 'change' => 'text-embedding-3', 'changeType' => 'neutral', 'icon' => 'sparkles', 'iconColor' => 'brand'],
        ];

        $regionalBreakdown = [
            ['region' => 'Punjab', 'universities' => 104, 'campuses' => 182, 'programs' => 1480, 'coverage' => 97.4],
            ['region' => 'Sindh', 'universities' => 68, 'campuses' => 105, 'programs' => 890, 'coverage' => 95.8],
            ['region' => 'Khyber Pakhtunkhwa (KPK)', 'universities' => 44, 'campuses' => 72, 'programs' => 590, 'coverage' => 96.0],
            ['region' => 'Islamabad Capital Territory (ICT)', 'universities' => 16, 'campuses' => 28, 'programs' => 310, 'coverage' => 99.2],
            ['region' => 'Balochistan', 'universities' => 14, 'campuses' => 21, 'programs' => 130, 'coverage' => 91.5],
            ['region' => 'Azad Jammu & Kashmir / GB', 'universities' => 8, 'campuses' => 14, 'programs' => 80, 'coverage' => 92.0],
        ];

        $systemAlerts = [
            [
                'id' => 'alt-1',
                'severity' => 'critical',
                'title' => 'Unaccredited Sub-Campus Detected in Rawalpindi',
                'desc' => 'Scraper identified unauthorized admissions circular for unchartered constituent facility.',
                'action_label' => 'Issue Regulatory Warning Notice',
                'timestamp' => '1 hour ago',
            ],
            [
                'id' => 'alt-2',
                'severity' => 'warning',
                'title' => 'PEC Washington Accord Re-evaluation Required',
                'desc' => '2 engineering institutions have accreditation cycles expiring in Q3 2026.',
                'action_label' => 'Inspect Council Dossier',
                'timestamp' => '4 hours ago',
            ],
            [
                'id' => 'alt-3',
                'severity' => 'info',
                'title' => 'Automated Crawler Sync Concluded',
                'desc' => '248 official university prospectus repositories inspected. 38 updates staged for human verification.',
                'action_label' => 'Open Verification Queue',
                'timestamp' => '6 hours ago',
            ],
        ];

        $recentChanges = [
            ['university' => 'NUST Islamabad', 'change' => 'Tuition schedule Fall 2026 ratified by Directorate of Admissions', 'timestamp' => '25 mins ago', 'user' => 'Dr. Tariq Mahmood'],
            ['university' => 'FAST-NUCES', 'change' => 'Added BS Cyber Security intake capacity across 5 campuses', 'timestamp' => '1 hour ago', 'user' => 'Admissions Registrar'],
            ['university' => 'LUMS Lahore', 'change' => 'Updated Financial Aid endowment allocation for National Outreach Program', 'timestamp' => '3 hours ago', 'user' => 'Financial Aid Dean'],
            ['university' => 'GIKI Topi', 'change' => 'Re-certified Outcome-Based Education (OBE) Washington Accord standings', 'timestamp' => '5 hours ago', 'user' => 'Prof. S. Rauf'],
        ];

        return [
            'kpis' => $kpis,
            'regional_breakdown' => $regionalBreakdown,
            'system_alerts' => $systemAlerts,
            'recent_changes' => $recentChanges,
        ];
    }

    /**
     * 2. Master University Registry.
     */
    public function getUniversities(): array
    {
        $default = [
            [
                'id' => 'uni-reg-1',
                'name' => 'National University of Sciences & Technology',
                'acronym' => 'NUST',
                'slug' => 'nust',
                'sector' => 'Public',
                'charter_type' => 'Federal Act XXVIII of 1991',
                'province' => 'Islamabad (ICT)',
                'hec_category' => 'W4 (Premier)',
                'national_rank' => '#1 Overall & Engineering',
                'active_campuses_count' => 5,
                'active_programs_count' => 42,
                'last_audit_date' => '15 April 2026',
                'status' => 'Chartered & Verified',
                'badge_color' => 'success',
            ],
            [
                'id' => 'uni-reg-2',
                'name' => 'FAST - National University of Computer & Emerging Sciences',
                'acronym' => 'FAST-NUCES',
                'slug' => 'fast-nuces',
                'sector' => 'Chartered',
                'charter_type' => 'Federal Ordinance XXIII of 2000',
                'province' => 'Islamabad (ICT)',
                'hec_category' => 'W4 (Computing)',
                'national_rank' => '#1 Computing & Artificial Intelligence',
                'active_campuses_count' => 5,
                'active_programs_count' => 28,
                'last_audit_date' => '22 Jan 2026',
                'status' => 'Chartered & Verified',
                'badge_color' => 'success',
            ],
            [
                'id' => 'uni-reg-3',
                'name' => 'Lahore University of Management Sciences',
                'acronym' => 'LUMS',
                'slug' => 'lums',
                'sector' => 'Private',
                'charter_type' => 'Punjab Presidential Order 1985',
                'province' => 'Punjab',
                'hec_category' => 'W4 (Premier)',
                'national_rank' => '#1 Business & Social Sciences',
                'active_campuses_count' => 1,
                'active_programs_count' => 34,
                'last_audit_date' => '05 March 2026',
                'status' => 'Chartered & Verified',
                'badge_color' => 'success',
            ],
            [
                'id' => 'uni-reg-4',
                'name' => 'Ghulam Ishaq Khan Institute of Engineering Sciences and Technology',
                'acronym' => 'GIKI',
                'slug' => 'giki',
                'sector' => 'Private',
                'charter_type' => 'KPK NWFP Act XXIII of 1993',
                'province' => 'Khyber Pakhtunkhwa',
                'hec_category' => 'W4 (Engineering)',
                'national_rank' => '#2 Engineering & Technology',
                'active_campuses_count' => 1,
                'active_programs_count' => 18,
                'last_audit_date' => '12 Feb 2026',
                'status' => 'Chartered & Verified',
                'badge_color' => 'success',
            ],
            [
                'id' => 'uni-reg-5',
                'name' => 'Pakistan Institute of Engineering and Applied Sciences',
                'acronym' => 'PIEAS',
                'slug' => 'pieas',
                'sector' => 'Public',
                'charter_type' => 'Federal Presidential Charter 2000',
                'province' => 'Islamabad (ICT)',
                'hec_category' => 'W4 (Nuclear & Applied)',
                'national_rank' => '#1 Faculty Quality & Citations',
                'active_campuses_count' => 1,
                'active_programs_count' => 16,
                'last_audit_date' => '18 Nov 2025',
                'status' => 'Chartered & Verified',
                'badge_color' => 'success',
            ],
            [
                'id' => 'uni-reg-6',
                'name' => 'COMSATS University Islamabad',
                'acronym' => 'CUI',
                'slug' => 'comsats',
                'sector' => 'Public',
                'charter_type' => 'Federal Act II of 2018',
                'province' => 'Islamabad (ICT)',
                'hec_category' => 'W4 (Multi-Campus)',
                'national_rank' => '#3 Overall Research Publications',
                'active_campuses_count' => 7,
                'active_programs_count' => 54,
                'last_audit_date' => '10 Feb 2026',
                'status' => 'Chartered & Verified',
                'badge_color' => 'success',
            ],
            [
                'id' => 'uni-reg-7',
                'name' => 'Aga Khan University',
                'acronym' => 'AKU',
                'slug' => 'aga-khan-university',
                'sector' => 'Private',
                'charter_type' => 'Federal Presidential Order 3 of 1983',
                'province' => 'Sindh',
                'hec_category' => 'W4 (Medical)',
                'national_rank' => '#1 Health Sciences & Clinical Medicine',
                'active_campuses_count' => 2,
                'active_programs_count' => 22,
                'last_audit_date' => '02 Feb 2026',
                'status' => 'Chartered & Verified',
                'badge_color' => 'success',
            ],
            [
                'id' => 'uni-reg-8',
                'name' => 'University of Engineering and Technology Lahore',
                'acronym' => 'UET Lahore',
                'slug' => 'uet-lahore',
                'sector' => 'Public',
                'charter_type' => 'Punjab Act V of 1974',
                'province' => 'Punjab',
                'hec_category' => 'W3',
                'national_rank' => '#4 Engineering',
                'active_campuses_count' => 5,
                'active_programs_count' => 38,
                'last_audit_date' => '14 Jan 2026',
                'status' => 'Chartered & Verified',
                'badge_color' => 'success',
            ],
        ];

        return session()->get('uniguide_national_universities', $default);
    }

    /**
     * 3. University Approval Center (Charter & Accreditation Pipeline).
     */
    public function getApprovals(): array
    {
        $default = [
            [
                'id' => 'appr-1',
                'institution' => 'FAST-NUCES (Proposed Multan Campus)',
                'application_type' => 'Constituent Sub-Campus Endorsement',
                'submission_date' => '14 April 2026',
                'applicant_rector' => 'Dr. Aftab Maroof',
                'details' => 'Inspection dossier submitted for 25-acre purpose-built campus on Bosan Road Multan with 400 BS CS intake capacity.',
                'inspection_committee' => 'Prof. Dr. Tariq Rafi (Chairman HEC Sindh) & PEC Delegation',
                'status' => 'pending_review',
                'status_label' => 'Inspection Completed • Awaiting Council Vote',
                'badge_color' => 'warning',
            ],
            [
                'id' => 'appr-2',
                'institution' => 'Namal University Mianwali',
                'application_type' => 'Charter Level-II Upgrade & Degree Expansion',
                'submission_date' => '02 March 2026',
                'applicant_rector' => 'Prof. Dr. M. Najam',
                'details' => 'Request for approval of new School of Agri-Tech & Biotechnology under National PQF Level-VI guidelines.',
                'inspection_committee' => 'HEC Quality Assurance Agency (QAA)',
                'status' => 'pending_review',
                'status_label' => 'Pending Document Verification',
                'badge_color' => 'warning',
            ],
            [
                'id' => 'appr-3',
                'institution' => 'Balochistan University of Information Technology (BUITEMS)',
                'application_type' => 'PEC Washington Accord Level-II Re-accreditation',
                'submission_date' => '18 Feb 2026',
                'applicant_rector' => 'Engr. Khalid Hafeez',
                'details' => 'Outcome-Based Education (OBE) compliance audit report for BE Software & Electrical Engineering.',
                'inspection_committee' => 'Pakistan Engineering Council Evaluation Board',
                'status' => 'approved',
                'status_label' => 'Approved & Gazette Issued',
                'badge_color' => 'success',
            ],
        ];

        return session()->get('uniguide_national_approvals', $default);
    }

    public function processApproval(string $id, string $action): void
    {
        $approvals = $this->getApprovals();
        foreach ($approvals as &$app) {
            if ($app['id'] === $id) {
                $app['status'] = $action;
                $app['status_label'] = match ($action) {
                    'approved' => 'Approved & Gazette Issued',
                    'rejected' => 'Rejected • Deficiencies Notified',
                    default => 'Under Formal Review',
                };
                break;
            }
        }
        session()->put('uniguide_national_approvals', $approvals);
    }

    /**
     * 4. Campus and Program Registry.
     */
    public function getProgramsAndCampuses(): array
    {
        $programs = [
            ['id' => 'p-1', 'university' => 'NUST', 'campus' => 'Main Campus Islamabad', 'name' => 'BS Computer Science', 'level' => 'Undergraduate', 'council' => 'NCEAC Category W', 'quota_seats' => 140, 'annual_fee' => 'PKR 390,000', 'status' => 'Approved'],
            ['id' => 'p-2', 'university' => 'NUST', 'campus' => 'Main Campus Islamabad', 'name' => 'BE Electrical Engineering', 'level' => 'Undergraduate', 'council' => 'PEC Level-II (Washington Accord)', 'quota_seats' => 160, 'annual_fee' => 'PKR 370,000', 'status' => 'Approved'],
            ['id' => 'p-3', 'university' => 'FAST-NUCES', 'campus' => 'Islamabad H-9', 'name' => 'BS Software Engineering', 'level' => 'Undergraduate', 'council' => 'NCEAC Category W', 'quota_seats' => 200, 'annual_fee' => 'PKR 430,000', 'status' => 'Approved'],
            ['id' => 'p-4', 'university' => 'FAST-NUCES', 'campus' => 'Lahore Faisal Town', 'name' => 'BS Artificial Intelligence', 'level' => 'Undergraduate', 'council' => 'NCEAC Category W', 'quota_seats' => 150, 'annual_fee' => 'PKR 430,000', 'status' => 'Approved'],
            ['id' => 'p-5', 'university' => 'LUMS', 'campus' => 'DHA Phase 5 Lahore', 'name' => 'Bachelor of Science (BSc Honors)', 'level' => 'Undergraduate', 'council' => 'HEC / AACSB', 'quota_seats' => 450, 'annual_fee' => 'PKR 1,580,000', 'status' => 'Approved'],
            ['id' => 'p-6', 'university' => 'GIKI', 'campus' => 'Topi Swabi KPK', 'name' => 'BS Computer Engineering', 'level' => 'Undergraduate', 'council' => 'PEC Level-II (Washington Accord)', 'quota_seats' => 120, 'annual_fee' => 'PKR 890,000', 'status' => 'Approved'],
            ['id' => 'p-7', 'university' => 'Aga Khan University', 'campus' => 'Stadium Road Karachi', 'name' => 'Bachelor of Medicine & Surgery (MBBS)', 'level' => 'Undergraduate', 'council' => 'PMDC / WFME Accredited', 'quota_seats' => 100, 'annual_fee' => 'PKR 2,450,000', 'status' => 'Approved'],
        ];

        return [
            'programs' => $programs,
            'total_programs' => 3420,
            'total_campuses' => 412,
        ];
    }

    /**
     * 5. Official Data Source Management.
     */
    public function getDataSources(): array
    {
        $default = [
            [
                'id' => 'src-1',
                'name' => 'NUST Official Admissions Portal & Prospectus Feed',
                'institution' => 'NUST Islamabad',
                'base_url' => 'https://ugadmissions.nust.edu.pk',
                'endpoint' => '/fees-2026.pdf',
                'feed_type' => 'PDF Gazette Parser',
                'reliability_score' => 99.4,
                'status' => 'healthy',
                'http_status' => 200,
                'last_crawled' => '2 hours ago',
                'crawl_frequency' => 'Every 6 Hours',
            ],
            [
                'id' => 'src-2',
                'name' => 'FAST-NUCES Central University Portal',
                'institution' => 'FAST-NUCES',
                'base_url' => 'https://nu.edu.pk',
                'endpoint' => '/admissions/schedule',
                'feed_type' => 'HTML Table Scraper',
                'reliability_score' => 98.8,
                'status' => 'healthy',
                'http_status' => 200,
                'last_crawled' => '3 hours ago',
                'crawl_frequency' => 'Daily',
            ],
            [
                'id' => 'src-3',
                'name' => 'Pakistan Engineering Council (PEC) Gazette Repository',
                'institution' => 'Federal Regulatory Body',
                'base_url' => 'https://pec.org.pk',
                'endpoint' => '/accreditation/first-schedule.aspx',
                'feed_type' => 'Statutory Gazette Scraper',
                'reliability_score' => 100.0,
                'status' => 'healthy',
                'http_status' => 200,
                'last_crawled' => '6 hours ago',
                'crawl_frequency' => 'Daily',
            ],
            [
                'id' => 'src-4',
                'name' => 'LUMS Admissions & Financial Aid Feed',
                'institution' => 'LUMS Lahore',
                'base_url' => 'https://admission.lums.edu.pk',
                'endpoint' => '/undergraduate/fees',
                'feed_type' => 'REST JSON API Scraper',
                'reliability_score' => 97.2,
                'status' => 'healthy',
                'http_status' => 200,
                'last_crawled' => 'Yesterday',
                'crawl_frequency' => 'Daily',
            ],
            [
                'id' => 'src-5',
                'name' => 'UET Lahore Admission Portal',
                'institution' => 'UET Lahore',
                'base_url' => 'https://admission.uet.edu.pk',
                'endpoint' => '/prospectus/2026',
                'feed_type' => 'PDF Scraper',
                'reliability_score' => 84.5,
                'status' => 'warning',
                'http_status' => 403,
                'last_crawled' => '2 days ago',
                'crawl_frequency' => 'Every 12 Hours',
            ],
        ];

        return session()->get('uniguide_national_sources', $default);
    }

    public function createDataSource(array $data): void
    {
        $sources = $this->getDataSources();
        $newSource = [
            'id' => 'src-'.(count($sources) + 1),
            'name' => $data['name'],
            'institution' => $data['institution'],
            'base_url' => $data['base_url'],
            'endpoint' => $data['endpoint'] ?? '/',
            'feed_type' => $data['feed_type'] ?? 'HTML Table Scraper',
            'reliability_score' => 100.0,
            'status' => 'healthy',
            'http_status' => 200,
            'last_crawled' => 'Just registered',
            'crawl_frequency' => $data['crawl_frequency'] ?? 'Daily',
        ];

        array_unshift($sources, $newSource);
        session()->put('uniguide_national_sources', $sources);
    }

    /**
     * 6. Web Scraping Control Center.
     */
    public function getScrapingControl(): array
    {
        $queueStats = [
            'total_sources' => 248,
            'active_crawlers' => 4,
            'queue_depth' => 12,
            'failed_today' => 1,
            'success_rate' => 98.6,
            'avg_crawl_time' => '4.2s',
        ];

        $crawlers = [
            [
                'id' => 'crw-1',
                'source_name' => 'NUST Islamabad Prospectus',
                'status' => 'running',
                'status_label' => 'Crawling (Worker #1)',
                'badge_color' => 'brand',
                'last_success' => 'Today at 12:15 PM',
                'next_scheduled' => 'Today at 06:15 PM',
                'freshness' => 'Fresh (< 3h)',
                'pages_crawled' => 64,
                'entities_found' => 8,
                'error_count' => 0,
            ],
            [
                'id' => 'crw-2',
                'source_name' => 'FAST-NUCES Admissions Portal',
                'status' => 'idle',
                'status_label' => 'Idle • Next in 4h',
                'badge_color' => 'neutral',
                'last_success' => 'Today at 09:30 AM',
                'next_scheduled' => 'Tomorrow at 09:30 AM',
                'freshness' => 'Fresh (< 6h)',
                'pages_crawled' => 32,
                'entities_found' => 5,
                'error_count' => 0,
            ],
            [
                'id' => 'crw-3',
                'source_name' => 'UET Lahore ECAT Gazette',
                'status' => 'failed',
                'status_label' => 'HTTP 403 Cloudflare Gate',
                'badge_color' => 'error',
                'last_success' => '02 Nov 2026',
                'next_scheduled' => 'Manual Retry Pending',
                'freshness' => 'Stale (48h)',
                'pages_crawled' => 0,
                'entities_found' => 0,
                'error_count' => 3,
            ],
            [
                'id' => 'crw-4',
                'source_name' => 'PEC Outcome-Based Gazette Schedule',
                'status' => 'queued',
                'status_label' => 'Queued (#2)',
                'badge_color' => 'warning',
                'last_success' => 'Yesterday at 11:00 PM',
                'next_scheduled' => 'In 18 minutes',
                'freshness' => 'Fresh (14h)',
                'pages_crawled' => 18,
                'entities_found' => 2,
                'error_count' => 0,
            ],
        ];

        return [
            'stats' => $queueStats,
            'crawlers' => $crawlers,
        ];
    }

    public function triggerCrawl(string $sourceName): void
    {
        // Confirmed backend crawl trigger simulation
        session()->flash('toast_success', 'Crawl worker dispatched for '.htmlspecialchars($sourceName).'. Pipeline initialized.');
    }

    public function retryFailedCrawls(): void
    {
        session()->flash('toast_success', 'Re-queued all failed crawler jobs with exponential backoff.');
    }

    /**
     * 7. Scraping Job History.
     */
    public function getScrapingHistory(): array
    {
        return [
            [
                'job_id' => 'JOB-CRW-9842',
                'target' => 'NUST Islamabad Central Admissions',
                'url' => 'https://ugadmissions.nust.edu.pk/fees-2026.pdf',
                'started_at' => '2026-11-04 12:12:04 PKT',
                'duration' => '3.8s',
                'pages' => 48,
                'records_extracted' => 8,
                'status' => 'Completed',
                'badge_color' => 'success',
                'http_code' => 200,
                'log_snippet' => "Worker 1 initialized.\nGET https://ugadmissions.nust.edu.pk/fees-2026.pdf -> 200 OK (24.8MB)\nExtracted 42 table rows from Page 42.\nEntity detection matched: tuition_semester (PKR 195,000).\nSaved to AI Verification queue.",
            ],
            [
                'job_id' => 'JOB-CRW-9841',
                'target' => 'FAST-NUCES All-Pakistan Schedule',
                'url' => 'https://nu.edu.pk/admissions/schedule',
                'started_at' => '2026-11-04 09:28:11 PKT',
                'duration' => '2.1s',
                'pages' => 14,
                'records_extracted' => 5,
                'status' => 'Completed',
                'badge_color' => 'success',
                'http_code' => 200,
                'log_snippet' => "Worker 3 initialized.\nParsed HTML table #schedule-ug.\nFound 5 campuses. Deadlines verified: 05 July 2026.",
            ],
            [
                'job_id' => 'JOB-CRW-9840',
                'target' => 'UET Lahore ECAT Portal',
                'url' => 'https://admission.uet.edu.pk',
                'started_at' => '2026-11-03 22:15:00 PKT',
                'duration' => '1.2s',
                'pages' => 0,
                'records_extracted' => 0,
                'status' => 'Failed',
                'badge_color' => 'error',
                'http_code' => 403,
                'log_snippet' => "Worker 2 initialized.\nGET https://admission.uet.edu.pk -> 403 Forbidden\nCloudflare protection detected. Crawler halted.",
            ],
            [
                'job_id' => 'JOB-CRW-9839',
                'target' => 'GIKI Topi Degree Catalog',
                'url' => 'https://giki.edu.pk/admissions/ug-programs',
                'started_at' => '2026-11-03 18:40:22 PKT',
                'duration' => '5.4s',
                'pages' => 22,
                'records_extracted' => 6,
                'status' => 'Completed',
                'badge_color' => 'success',
                'http_code' => 200,
                'log_snippet' => "Parsed 18 degree offerings.\nOutcome-based accreditation confirmed.",
            ],
        ];
    }

    /**
     * 8. AI Extraction Review Workspace.
     */
    public function getAiExtractions(): array
    {
        $default = [
            [
                'id' => 'ext-1',
                'university' => 'NUST Islamabad',
                'entity' => 'Tuition Fee Structure',
                'field_name' => 'BS Artificial Intelligence Semester Tuition',
                'source_document' => 'NUST Undergraduate Prospectus 2026-2027 (Page 42)',
                'source_url' => 'https://nust.edu.pk/admissions/fees-2026.pdf',
                'extracted_snippet' => 'Undergraduate tuition fee for Computing and Artificial Intelligence programs (BS CS, BS SE, BS AI) admitted in Fall 2026 shall be PKR 195,000 per semester.',
                'before_value' => 'PKR 180,000',
                'after_value' => 'PKR 195,000',
                'confidence' => 99.4,
                'validation_status' => 'Clean • Complies with HEC Ceiling',
                'has_warning' => false,
                'status' => 'pending',
                'timestamp' => '2 hours ago',
            ],
            [
                'id' => 'ext-2',
                'university' => 'FAST-NUCES',
                'entity' => 'Admission Calendar',
                'field_name' => 'Fall 2026 Application Closing Date',
                'source_document' => 'FAST National Admissions Banner',
                'source_url' => 'https://nu.edu.pk/admissions',
                'extracted_snippet' => 'Last date for submission of online application forms for all campuses is 05 July 2026.',
                'before_value' => '28 June 2026',
                'after_value' => '05 July 2026',
                'confidence' => 98.2,
                'validation_status' => 'Clean • Verified against IT exam schedule',
                'has_warning' => false,
                'status' => 'pending',
                'timestamp' => '3 hours ago',
            ],
            [
                'id' => 'ext-3',
                'university' => 'GIKI Topi',
                'entity' => 'Degree Curriculum',
                'field_name' => 'BE Computer Engineering Total Credit Hours',
                'source_document' => 'PEC OBE Gazette Schedule 2026',
                'source_url' => 'https://pec.org.pk/gazette/giki-ce-2026.pdf',
                'extracted_snippet' => 'Graduation requirement for BE Computer Engineering rationalized to 134 credit hours.',
                'before_value' => '138 Credit Hours',
                'after_value' => '134 Credit Hours',
                'confidence' => 96.8,
                'validation_status' => 'Clean • Complies with PEC Washington Accord Manual',
                'has_warning' => false,
                'status' => 'pending',
                'timestamp' => '5 hours ago',
            ],
            [
                'id' => 'ext-4',
                'university' => 'UET Lahore',
                'entity' => 'Hostel Mess Tariff',
                'field_name' => 'Monthly Dining Charges',
                'source_document' => 'UET Notification HD-26',
                'source_url' => 'https://admission.uet.edu.pk/fees',
                'extracted_snippet' => 'Mess security and monthly dining fixed at PKR 12,000.',
                'before_value' => 'PKR 9,500',
                'after_value' => 'PKR 12,000',
                'confidence' => 84.1,
                'validation_status' => 'Warning: 26.3% increase exceeds annual baseline inflation',
                'has_warning' => true,
                'status' => 'pending',
                'timestamp' => 'Yesterday',
            ],
        ];

        return session()->get('uniguide_national_extractions', $default);
    }

    public function processExtraction(string $id, string $decision, ?string $editedValue = null): void
    {
        $items = $this->getAiExtractions();
        foreach ($items as &$it) {
            if ($it['id'] === $id) {
                $it['status'] = $decision;
                if ($decision === 'edited' && $editedValue !== null) {
                    $it['after_value'] = $editedValue;
                }
                $it['audited_at'] = now()->format('d M Y H:i');
                $it['audited_by'] = 'Federal Oversight Commissioner';
                break;
            }
        }
        session()->put('uniguide_national_extractions', $items);
    }

    /**
     * 9. Data Verification Queue.
     */
    public function getVerificationQueue(): array
    {
        $default = [
            [
                'id' => 'vq-1',
                'entity_type' => 'University Profile',
                'institution' => 'NUST Islamabad',
                'field' => 'Helpline Contact & Rector Name',
                'status' => 'Pending Review',
                'priority' => 'High',
                'submitted_by' => 'Dr. Tariq Mahmood',
                'submitted_at' => 'Today at 11:20 AM',
                'before_value' => 'Rector: Air Marshal (Retd) Rizwan Riaz • Phone: +92 51 9085 6878',
                'after_value' => 'Rector: Engr. Javed Mahmood Bukhari • Phone: +92 51 9085 1000',
                'confidence' => 99.1,
                'source_doc' => 'Official University Gazette SRO-2026/04',
            ],
            [
                'id' => 'vq-2',
                'entity_type' => 'Tuition Fee Revision',
                'institution' => 'FAST-NUCES',
                'field' => 'Computing BS Tuition (+8.3%)',
                'status' => 'Pending Review',
                'priority' => 'High',
                'submitted_by' => 'Admissions Registrar',
                'submitted_at' => 'Today at 09:15 AM',
                'before_value' => 'Per Credit Hour: PKR 9,500 (Semester Avg PKR 171,000)',
                'after_value' => 'Per Credit Hour: PKR 10,300 (Semester Avg PKR 185,400)',
                'confidence' => 97.4,
                'source_doc' => 'Board of Governors Circular No. 42/2026',
            ],
            [
                'id' => 'vq-3',
                'entity_type' => 'New Program Filing',
                'institution' => 'GIKI Topi',
                'field' => 'BS Artificial Intelligence (90 Seats)',
                'status' => 'Flagged for Audit',
                'priority' => 'Medium',
                'submitted_by' => 'Academic Council',
                'submitted_at' => 'Yesterday at 04:00 PM',
                'before_value' => 'Not Offered on Main Campus',
                'after_value' => 'BS AI: 90 Regular Intake Seats, HEC PQF Level-VI Approved',
                'confidence' => 96.8,
                'source_doc' => 'Academic Council Minutes 18th Session',
            ],
            [
                'id' => 'vq-4',
                'entity_type' => 'Quota Allocation',
                'institution' => 'UET Lahore',
                'field' => 'FATA & Balochistan Quota Seats Distribution',
                'status' => 'Approved',
                'priority' => 'Low',
                'submitted_by' => 'Govt. of Punjab',
                'submitted_at' => '02 Nov 2026',
                'before_value' => 'FATA: 24 Seats • Balochistan: 18 Seats',
                'after_value' => 'FATA: 30 Seats • Balochistan: 22 Seats (+10 Federal Addition)',
                'confidence' => 100.0,
                'source_doc' => 'Punjab Higher Education Department Gazette Notification',
            ],
        ];

        return session()->get('uniguide_national_verification', $default);
    }

    public function processVerification(string $id, string $action): void
    {
        $queue = $this->getVerificationQueue();
        foreach ($queue as &$item) {
            if ($item['id'] === $id) {
                $item['status'] = $action === 'approved' ? 'Approved' : 'Flagged for Audit';
                break;
            }
        }
        session()->put('uniguide_national_verification', $queue);
    }

    /**
     * 10. Knowledge Base Management (RAG Vector Store).
     */
    public function getKnowledgeBase(): array
    {
        $stats = [
            'total_documents' => 648,
            'embedded_chunks' => 42850,
            'embedding_model' => 'text-embedding-3-small (1536 dims)',
            'index_status' => 'Active & Synchronized',
            'search_latency' => '18.4ms',
            'failed_chunks' => 0,
        ];

        $documents = [
            [
                'id' => 'kb-doc-1',
                'title' => 'Pakistan Engineering Council (PEC) Act No. V of 1976 (Amended 2026)',
                'authority' => 'Federal Gazette',
                'chunks_count' => 148,
                'status' => 'Indexed',
                'status_color' => 'success',
                'last_embedded' => '15 Jan 2026',
            ],
            [
                'id' => 'kb-doc-2',
                'title' => 'HEC Pre-Medical to Computing Transition Gazette Notification SRO 45(I)/2026',
                'authority' => 'Higher Education Commission',
                'chunks_count' => 64,
                'status' => 'Indexed',
                'status_color' => 'success',
                'last_embedded' => '02 May 2026',
            ],
            [
                'id' => 'kb-doc-3',
                'title' => 'NUST Islamabad Complete Undergraduate Prospectus 2026-2027',
                'authority' => 'NUST Directorate of Admissions',
                'chunks_count' => 412,
                'status' => 'Indexed',
                'status_color' => 'success',
                'last_embedded' => '18 April 2026',
            ],
            [
                'id' => 'kb-doc-4',
                'title' => 'Inter Board Coordination Commission (IBCC) Cambridge Equivalence Manual',
                'authority' => 'IBCC Federal Authority',
                'chunks_count' => 92,
                'status' => 'Indexed',
                'status_color' => 'success',
                'last_embedded' => '20 March 2026',
            ],
        ];

        return [
            'stats' => $stats,
            'documents' => $documents,
        ];
    }

    public function reindexKnowledgeBase(): void
    {
        session()->flash('toast_success', 'Vector store re-indexing pipeline triggered across all 648 statutory documents.');
    }

    /**
     * 11. AI Chatbot Configuration.
     */
    public function getChatbotConfig(): array
    {
        $default = [
            'model' => 'gemini-1.5-pro',
            'temperature' => 0.20,
            'max_tokens' => 2048,
            'citation_threshold' => 0.85,
            'inject_pec_rules' => true,
            'inject_hec_premed_rules' => true,
            'inject_ibcc_rules' => true,
            'enforce_strict_citations' => true,
            'guardrails_hallucination_block' => true,
            'system_prompt' => "You are UniGuide AI Counselor, Pakistan's official national higher education admissions advisory intelligence. You must strictly provide verified admissions criteria, merit formulas, and tuition costs adhering to published HEC, PEC, and NCEAC statutory mandates. Never invent or speculate upon closing merit percentiles. Always cite official university gazettes.",
        ];

        return session()->get('uniguide_national_chatbot_config', $default);
    }

    public function updateChatbotConfig(array $data): void
    {
        $current = $this->getChatbotConfig();
        $updated = array_merge($current, $data);
        session()->put('uniguide_national_chatbot_config', $updated);
    }

    /**
     * 12. Student and Admin User Management.
     */
    public function getUsers(): array
    {
        $default = [
            [
                'id' => 'usr-1',
                'name' => 'Dr. Tariq Mahmood',
                'email' => 'tariq.mahmood@nust.edu.pk',
                'role' => 'University Administrator',
                'scope' => 'NUST Islamabad',
                'status' => 'Active',
                'two_factor' => 'Enforced',
                'created_at' => '15 Jan 2026',
            ],
            [
                'id' => 'usr-2',
                'name' => 'Ahmad Raza Khan',
                'email' => 'ahmad.raza@student.uniguide.pk',
                'role' => 'Applicant Candidate',
                'scope' => 'FSc Pre-Engineering',
                'status' => 'Active',
                'two_factor' => 'Active',
                'created_at' => '18 Oct 2026',
            ],
            [
                'id' => 'usr-3',
                'name' => 'Prof. Naveed Arshad',
                'email' => 'registrar@nust.edu.pk',
                'role' => 'University Administrator',
                'scope' => 'Central Registry NUST',
                'status' => 'Active',
                'two_factor' => 'Enforced',
                'created_at' => '10 Jan 2026',
            ],
            [
                'id' => 'usr-4',
                'name' => 'Prof. Dr. Mukhtar Ahmed',
                'email' => 'chairman@hec.gov.pk',
                'role' => 'Federal Oversight Commissioner',
                'scope' => 'National Regulatory HEC',
                'status' => 'Super Admin',
                'two_factor' => 'Enforced',
                'created_at' => '01 Jan 2026',
            ],
        ];

        return session()->get('uniguide_national_users', $default);
    }

    public function inviteUser(array $data): void
    {
        $users = $this->getUsers();
        $newUser = [
            'id' => 'usr-'.(count($users) + 1),
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'scope' => $data['scope'],
            'status' => 'Pending Verification',
            'two_factor' => ! empty($data['two_factor']) ? 'Enforced' : 'Optional',
            'created_at' => 'Just now',
        ];

        array_unshift($users, $newUser);
        session()->put('uniguide_national_users', $users);
    }

    /**
     * 13. Notifications Management.
     */
    public function getNotifications(): array
    {
        $broadcasts = [
            [
                'id' => 'notif-1',
                'title' => 'Federal Directive: Pre-Medical to Computing Remedial Mathematics Clarification',
                'category' => 'HEC Regulatory Circular',
                'target_audience' => 'All Chartered Universities & 240,000 Candidates',
                'channels' => ['In-App Banner', 'Email Digest', 'SMS Broadcast'],
                'dispatched_at' => '02 May 2026',
                'status' => 'Delivered (99.8%)',
            ],
            [
                'id' => 'notif-2',
                'title' => 'Emergency Extension: FBISE Result Awaiting Candidates Grace Window',
                'category' => 'National Admissions Alert',
                'target_audience' => 'FSc Final Year Candidates',
                'channels' => ['In-App Banner', 'Student Locker'],
                'dispatched_at' => '15 July 2026',
                'status' => 'Scheduled',
            ],
        ];

        return $broadcasts;
    }

    public function createNotification(array $data): void
    {
        session()->flash('toast_success', 'National regulatory notification broadcast dispatched successfully across all selected channels.');
    }

    /**
     * 14. Analytics and Reports.
     */
    public function getAnalytics(): array
    {
        return [
            'discipline_demand' => [
                ['name' => 'Computer Science & AI', 'share' => 48.5, 'candidates' => 116400],
                ['name' => 'Engineering & Technology (PEC)', 'share' => 24.2, 'candidates' => 58080],
                ['name' => 'Business & Management (BBA)', 'share' => 15.8, 'candidates' => 37920],
                ['name' => 'Medical & Allied Health Sciences', 'share' => 11.5, 'candidates' => 27600],
            ],
            'provincial_inflow' => [
                ['from' => 'Khyber Pakhtunkhwa', 'destination' => 'Islamabad (ICT) & Punjab', 'students_count' => 34800],
                ['from' => 'Balochistan', 'destination' => 'Punjab & Islamabad (ICT)', 'students_count' => 18400],
                ['from' => 'Sindh (Interior)', 'destination' => 'Karachi & Lahore', 'students_count' => 24200],
                ['from' => 'AJK / Gilgit-Baltistan', 'destination' => 'Islamabad (ICT)', 'students_count' => 14600],
            ],
            'fee_inflation_index' => [
                ['year' => '2023', 'public_avg' => 145000, 'private_avg' => 310000],
                ['year' => '2024', 'public_avg' => 160000, 'private_avg' => 355000],
                ['year' => '2025', 'public_avg' => 175000, 'private_avg' => 410000],
                ['year' => '2026', 'public_avg' => 195000, 'private_avg' => 465000],
            ],
        ];
    }

    /**
     * 15. System Settings and Audit Logs.
     */
    public function getSettingsAndAuditLogs(): array
    {
        $settings = [
            'crawler_concurrency' => 8,
            'max_crawl_depth' => 4,
            'api_rate_limit_per_minute' => 600,
            'require_hec_commissioner_approval_for_fee_hike' => true,
            'immutable_audit_logging_enabled' => true,
            'daily_backup_to_national_archive' => true,
        ];

        $auditLogs = [
            ['timestamp' => '2026-11-04 12:45:10 PKT', 'user' => 'Prof. Dr. Mukhtar Ahmed', 'role' => 'Federal Commissioner', 'action' => 'Endorsed NUST Fall 2026 audited fee gazette', 'ip' => '111.68.96.1'],
            ['timestamp' => '2026-11-04 10:14:22 PKT', 'user' => 'Automated Crawler Engine', 'role' => 'System Daemon', 'action' => 'Indexed 48 pages from NUST admissions portal', 'ip' => '127.0.0.1'],
            ['timestamp' => '2026-11-03 16:30:00 PKT', 'user' => 'Dr. Tariq Rafi', 'role' => 'Inspection Lead', 'action' => 'Filed inspection report for FAST Multan campus', 'ip' => '202.83.172.5'],
            ['timestamp' => '2026-11-02 09:12:44 PKT', 'user' => 'QAA Compliance Desk', 'role' => 'Auditor', 'action' => 'Verified HEC SRO 45(I)/2026 remedial math ruleset', 'ip' => '111.68.96.8'],
        ];

        return [
            'settings' => session()->get('uniguide_national_settings', $settings),
            'audit_logs' => $auditLogs,
        ];
    }

    public function updateSettings(array $data): void
    {
        $current = $this->getSettingsAndAuditLogs();
        $updated = array_merge($current['settings'], $data);
        session()->put('uniguide_national_settings', $updated);
    }
}
