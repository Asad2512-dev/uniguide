<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the shared authentication login screen.
     */
    public function showLogin(Request $request): View
    {
        $role = $request->query('role', 'student');

        return view('pages.auth.login', [
            'initialRole' => in_array($role, ['student', 'university', 'admin']) ? $role : 'student',
        ]);
    }

    /**
     * Process authentication login request.
     */
    public function processLogin(Request $request): RedirectResponse
    {
        $role = $request->input('role', 'student');

        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        // Demo redirect based on selected role
        return match($role) {
            'university' => redirect()->route('preview.university_admin')->with('toast_success', 'Welcome to NUST Institutional Console, Dr. Tariq Mahmood.'),
            'admin' => redirect()->route('preview.national_admin')->with('toast_success', 'HEC National Regulatory Command authenticated.'),
            default => redirect()->route('preview.student')->with('toast_success', 'Welcome back, Ahmad Raza Khan! Fall 2026 application cycle is active.'),
        };
    }

    /**
     * Display the student & candidate registration screen.
     */
    public function showRegister(Request $request): View
    {
        return view('pages.auth.register');
    }

    /**
     * Process registration request.
     */
    public function processRegister(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:25'],
            'stream' => ['required', 'string'],
            'province' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        return redirect()->route('preview.student')->with('toast_success', 'Account registered successfully! Welcome to UniGuide Pakistan.');
    }

    /**
     * Display the forgot password screen.
     */
    public function showForgotPassword(): View
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Process forgot password request.
     */
    public function processForgotPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        return redirect()->route('auth.login')->with('toast_success', 'Password reset instructions sent to ' . $request->input('email') . '. Please check your inbox.');
    }

    /**
     * Log the user out and redirect to home.
     */
    public function logout(): RedirectResponse
    {
        return redirect()->route('home')->with('toast_info', 'You have been safely signed out of UniGuide Pakistan.');
    }
}
