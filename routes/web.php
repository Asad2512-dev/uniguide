<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DesignSystemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShellPreviewController;
use App\Http\Controllers\UniversityController;
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
