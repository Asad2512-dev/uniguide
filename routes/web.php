<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DesignSystemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ShellPreviewController;
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
