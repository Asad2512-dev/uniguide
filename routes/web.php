<?php

use App\Http\Controllers\DesignSystemController;
use App\Http\Controllers\ShellPreviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Central routes for UniGuide Pakistan.
| Establishes named routes, view models, and layout shell previews.
|
*/

// Root redirects to Design System Living Catalog & Overview
Route::get('/', [DesignSystemController::class, 'index'])->name('home');
Route::get('/design-system', [DesignSystemController::class, 'index'])->name('design-system');

// Four Independent Application Layout Shell Previews
Route::prefix('preview')->name('preview.')->group(function () {
    Route::get('/public', [ShellPreviewController::class, 'public'])->name('public');
    Route::get('/student', [ShellPreviewController::class, 'student'])->name('student');
    Route::get('/university-admin', [ShellPreviewController::class, 'universityAdmin'])->name('university_admin');
    Route::get('/national-admin', [ShellPreviewController::class, 'nationalAdmin'])->name('national_admin');
});
