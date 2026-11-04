<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\DesignSystemController;
use App\Http\Controllers\EligibilityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\NationalAdminController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShellPreviewController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UniversityAdminController;
use App\Http\Controllers\UniversityController;
use App\Http\Middleware\EnsureNationalAdminAuthorized;
use App\Http\Middleware\EnsureUniversityAdminAuthorized;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — UniGuide Pakistan
|--------------------------------------------------------------------------
|
| Central routes for UniGuide Pakistan's marketing, discovery,
| authentication, design system, and administrative shell previews.
|
*/

// Primary Marketing & University Discovery Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// National University Directory & Campus Explorer (Phase 02)
Route::get('/universities', [UniversityController::class, 'index'])->name('universities.index');
Route::get('/universities/{slug}', [UniversityController::class, 'show'])->name('universities.show');
Route::get('/universities/{slug}/campuses/{campusSlug}', [UniversityController::class, 'campus'])->name('universities.campuses.show');

// Degree Program Catalog & Program Detail (Phase 02)
Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/programs/{slug}', [ProgramController::class, 'show'])->name('programs.show');

// Admissions, Deadlines Calendar & Document Checklist (Phase 03)
Route::get('/admissions', [AdmissionController::class, 'index'])->name('admissions.index');
Route::get('/admissions/calendar', [AdmissionController::class, 'calendar'])->name('admissions.calendar');
Route::get('/admissions/checklist', [AdmissionController::class, 'checklist'])->name('admissions.checklist');
Route::get('/admissions/{slug}', [AdmissionController::class, 'show'])->name('admissions.show');

// Scholarship Explorer & Detail (Phase 03)
Route::get('/scholarships', [ScholarshipController::class, 'index'])->name('scholarships.index');
Route::get('/scholarships/{slug}', [ScholarshipController::class, 'show'])->name('scholarships.show');

// University Comparison Engine (Phase 06)
Route::prefix('compare')->name('compare.')->group(function () {
    Route::get('/', [ComparisonController::class, 'results'])->name('index');
    Route::get('/builder', [ComparisonController::class, 'builder'])->name('builder');
    Route::get('/results', [ComparisonController::class, 'results'])->name('results');
});

// Academic Eligibility Checker & Rules Engine (Phase 06)
Route::prefix('eligibility')->name('eligibility.')->group(function () {
    Route::get('/', [EligibilityController::class, 'wizard'])->name('index');
    Route::get('/results', [EligibilityController::class, 'results'])->name('results');
    Route::get('/rules/{ruleId?}', [EligibilityController::class, 'rule'])->name('rule');
    Route::get('/shortlist', [EligibilityController::class, 'shortlist'])->name('shortlist');
    Route::post('/shortlist', [EligibilityController::class, 'addToShortlist'])->name('shortlist.add');
});

// AI University Counselor Experience (Phase 04)
Route::prefix('counselor')->name('counselor.')->group(function () {
    Route::get('/', [CounselorController::class, 'index'])->name('index');
    Route::get('/chat/{id?}', [CounselorController::class, 'chat'])->name('chat');
    Route::post('/chat/message', [CounselorController::class, 'sendMessage'])->name('message');
    Route::get('/guided', [CounselorController::class, 'guided'])->name('guided');
    Route::get('/recommendations', [CounselorController::class, 'recommendations'])->name('recommendations');
    Route::get('/search', [CounselorController::class, 'search'])->name('search');
    Route::get('/evidence/{id?}', [CounselorController::class, 'evidence'])->name('evidence');
    Route::get('/history', [CounselorController::class, 'history'])->name('history');
    Route::delete('/history/{id}', [CounselorController::class, 'destroy'])->name('destroy');
});

// Authenticated Student Experience (Phase 05)
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/onboarding', [StudentController::class, 'onboarding'])->name('onboarding');
    Route::post('/onboarding', [StudentController::class, 'processOnboarding'])->name('onboarding.submit');
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::post('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    Route::get('/recommendations', [StudentController::class, 'recommendations'])->name('recommendations');

    // Saved Collections
    Route::get('/saved/universities', [StudentController::class, 'savedUniversities'])->name('saved.universities');
    Route::delete('/saved/universities/{slug}', [StudentController::class, 'removeSavedUniversity'])->name('saved.universities.destroy');
    Route::get('/saved/programs', [StudentController::class, 'savedPrograms'])->name('saved.programs');
    Route::delete('/saved/programs/{slug}', [StudentController::class, 'removeSavedProgram'])->name('saved.programs.destroy');
    Route::get('/saved/scholarships', [StudentController::class, 'savedScholarships'])->name('saved.scholarships');
    Route::delete('/saved/scholarships/{slug}', [StudentController::class, 'removeSavedScholarship'])->name('saved.scholarships.destroy');

    // Admission Tracker
    Route::get('/tracker', [StudentController::class, 'tracker'])->name('tracker');
    Route::post('/tracker', [StudentController::class, 'storeTracker'])->name('tracker.store');
    Route::put('/tracker/{id}', [StudentController::class, 'updateTracker'])->name('tracker.update');
    Route::delete('/tracker/{id}', [StudentController::class, 'deleteTracker'])->name('tracker.destroy');

    // Notifications Center
    Route::get('/notifications', [StudentController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{id}/read', [StudentController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [StudentController::class, 'markAllNotificationsRead'])->name('notifications.read_all');

    // Account Settings
    Route::get('/settings', [StudentController::class, 'settings'])->name('settings');
    Route::post('/settings', [StudentController::class, 'updateSettings'])->name('settings.update');
});

// University Administration Portal (Phase 07)
Route::prefix('admin/university')
    ->name('uni_admin.')
    ->middleware(EnsureUniversityAdminAuthorized::class)
    ->group(function () {
        Route::get('/', [UniversityAdminController::class, 'dashboard'])->name('index');
        Route::get('/dashboard', [UniversityAdminController::class, 'dashboard'])->name('dashboard');

        // Profile Management
        Route::get('/profile', [UniversityAdminController::class, 'profile'])->name('profile');
        Route::post('/profile', [UniversityAdminController::class, 'updateProfile'])->name('profile.update');

        // Campus Management
        Route::get('/campuses', [UniversityAdminController::class, 'campuses'])->name('campuses');
        Route::post('/campuses', [UniversityAdminController::class, 'storeCampus'])->name('campuses.store');
        Route::put('/campuses/{id}', [UniversityAdminController::class, 'updateCampus'])->name('campuses.update');

        // Department Management
        Route::get('/departments', [UniversityAdminController::class, 'departments'])->name('departments');
        Route::post('/departments', [UniversityAdminController::class, 'storeDepartment'])->name('departments.store');

        // Program Management
        Route::get('/programs', [UniversityAdminController::class, 'programs'])->name('programs');
        Route::post('/programs', [UniversityAdminController::class, 'storeProgram'])->name('programs.store');
        Route::put('/programs/{id}', [UniversityAdminController::class, 'updateProgram'])->name('programs.update');

        // Fee Management
        Route::get('/fees', [UniversityAdminController::class, 'fees'])->name('fees');
        Route::post('/fees', [UniversityAdminController::class, 'updateFees'])->name('fees.update');

        // Admission Management
        Route::get('/admissions', [UniversityAdminController::class, 'admissions'])->name('admissions');
        Route::post('/admissions', [UniversityAdminController::class, 'updateAdmissions'])->name('admissions.update');

        // Eligibility Rules Management
        Route::get('/eligibility', [UniversityAdminController::class, 'eligibility'])->name('eligibility');
        Route::post('/eligibility', [UniversityAdminController::class, 'updateEligibility'])->name('eligibility.update');

        // Scholarship Management
        Route::get('/scholarships', [UniversityAdminController::class, 'scholarships'])->name('scholarships');
        Route::post('/scholarships', [UniversityAdminController::class, 'storeScholarship'])->name('scholarships.store');

        // Document Management
        Route::get('/documents', [UniversityAdminController::class, 'documents'])->name('documents');
        Route::post('/documents/verify/{id}', [UniversityAdminController::class, 'verifyDocument'])->name('documents.verify');
        Route::post('/documents/upload', [UniversityAdminController::class, 'uploadDocument'])->name('documents.upload');

        // AI Data Verification
        Route::get('/ai-verification', [UniversityAdminController::class, 'aiVerification'])->name('ai_verification');
        Route::post('/ai-verification/{id}/approve', [UniversityAdminController::class, 'approveAiVerification'])->name('ai_verification.approve');
        Route::post('/ai-verification/{id}/reject', [UniversityAdminController::class, 'rejectAiVerification'])->name('ai_verification.reject');
        Route::post('/ai-verification/{id}/edit', [UniversityAdminController::class, 'editAiVerification'])->name('ai_verification.edit');

        // Student Inquiry Management
        Route::get('/inquiries', [UniversityAdminController::class, 'inquiries'])->name('inquiries');
        Route::post('/inquiries/{id}/reply', [UniversityAdminController::class, 'replyInquiry'])->name('inquiries.reply');
        Route::put('/inquiries/{id}/status', [UniversityAdminController::class, 'updateInquiryStatus'])->name('inquiries.status');

        // Staff and Settings
        Route::get('/settings', [UniversityAdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [UniversityAdminController::class, 'updateSettings'])->name('settings.update');
    });

// National Super Administration Platform (Phase 08)
Route::prefix('admin/national')
    ->name('national_admin.')
    ->middleware(EnsureNationalAdminAuthorized::class)
    ->group(function () {
        Route::get('/', [NationalAdminController::class, 'dashboard'])->name('index');
        Route::get('/dashboard', [NationalAdminController::class, 'dashboard'])->name('dashboard');

        // University Registry
        Route::get('/universities', [NationalAdminController::class, 'universities'])->name('universities');

        // University Approval Center
        Route::get('/approvals', [NationalAdminController::class, 'approvals'])->name('approvals');
        Route::post('/approvals/{id}', [NationalAdminController::class, 'processApproval'])->name('approvals.process');

        // Campus and Program Registry
        Route::get('/programs', [NationalAdminController::class, 'programs'])->name('programs');

        // Official Data Source Management
        Route::get('/sources', [NationalAdminController::class, 'sources'])->name('sources');
        Route::post('/sources', [NationalAdminController::class, 'createSource'])->name('sources.create');

        // Web Scraping Control Center
        Route::get('/scraping', [NationalAdminController::class, 'scraping'])->name('scraping');
        Route::post('/scraping/trigger', [NationalAdminController::class, 'triggerCrawl'])->name('scraping.trigger');
        Route::post('/scraping/retry-failed', [NationalAdminController::class, 'retryFailedCrawls'])->name('scraping.retry');

        // Scraping Job History
        Route::get('/scraping/history', [NationalAdminController::class, 'scrapingHistory'])->name('scraping_history');

        // AI Extraction Review
        Route::get('/ai-extraction', [NationalAdminController::class, 'aiExtraction'])->name('ai_extraction');
        Route::post('/ai-extraction/{id}', [NationalAdminController::class, 'processExtraction'])->name('ai_extraction.process');

        // Data Verification Queue
        Route::get('/verification', [NationalAdminController::class, 'verification'])->name('verification');
        Route::post('/verification/{id}', [NationalAdminController::class, 'processVerification'])->name('verification.process');

        // Knowledge Base Management (Vector Store)
        Route::get('/knowledge-base', [NationalAdminController::class, 'knowledgeBase'])->name('knowledge_base');
        Route::post('/knowledge-base/reindex', [NationalAdminController::class, 'reindexKnowledgeBase'])->name('knowledge_base.reindex');

        // AI Chatbot Configuration
        Route::get('/chatbot-config', [NationalAdminController::class, 'chatbotConfig'])->name('chatbot_config');
        Route::post('/chatbot-config', [NationalAdminController::class, 'updateChatbotConfig'])->name('chatbot_config.update');

        // Student and Admin User Management
        Route::get('/users', [NationalAdminController::class, 'users'])->name('users');
        Route::post('/users/invite', [NationalAdminController::class, 'inviteUser'])->name('users.invite');

        // Notifications Management
        Route::get('/notifications', [NationalAdminController::class, 'notifications'])->name('notifications');
        Route::post('/notifications', [NationalAdminController::class, 'createNotification'])->name('notifications.create');

        // Analytics and Reports
        Route::get('/analytics', [NationalAdminController::class, 'analytics'])->name('analytics');

        // System Settings and Audit Logs
        Route::get('/settings', [NationalAdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [NationalAdminController::class, 'updateSettings'])->name('settings.update');
    });

// Unified Multi-Entity Search & API Suggestions
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/api/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// Public Marketing & Platform Explanatory Screens
Route::name('marketing.')->group(function () {
    Route::get('/about', [MarketingController::class, 'about'])->name('about');
    Route::get('/how-it-works', [MarketingController::class, 'howItWorks'])->name('how_it_works');
    Route::get('/features', [MarketingController::class, 'features'])->name('features');
    Route::get('/contact', [MarketingController::class, 'contact'])->name('contact');
    Route::post('/contact', [MarketingController::class, 'submitContact'])->name('contact.submit');
});

// Shared Authentication Entry Points
Route::name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.submit');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot_password');
    Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('forgot_password.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Living Design System Catalog & Specifications Showcase
Route::get('/design-system', [DesignSystemController::class, 'index'])->name('design-system');

// Four Independent Application Layout Shell Previews
Route::prefix('preview')->name('preview.')->group(function () {
    Route::get('/public', [ShellPreviewController::class, 'public'])->name('public');
    Route::get('/student', [ShellPreviewController::class, 'student'])->name('student');
    Route::get('/university-admin', [ShellPreviewController::class, 'universityAdmin'])->name('university_admin');
    Route::get('/national-admin', [ShellPreviewController::class, 'nationalAdmin'])->name('national_admin');
});
