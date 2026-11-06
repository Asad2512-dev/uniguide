<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $remember = $request->boolean('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            /** @var User $user */
            $user = Auth::user();

            session([
                'uniguide_user_role' => $user->role,
                'uniguide_admin_institution' => $user->institution_slug ?? 'nust',
            ]);

            return match ($user->role) {
                'university_admin', 'university_staff' => redirect()->route('uni_admin.dashboard')
                    ->with('toast_success', 'Welcome to '.($user->institution_slug ? strtoupper($user->institution_slug).' Institutional Console' : 'University Console').', '.$user->name.'.'),
                'national_admin' => redirect()->route('national_admin.dashboard')
                    ->with('toast_success', 'HEC National Regulatory Command authenticated. Welcome, '.$user->name.'.'),
                default => redirect()->route('student.dashboard')
                    ->with('toast_success', 'Welcome back, '.$user->name.'! Fall 2026 application cycle is active.'),
            };
        }

        return back()
            ->withInput($request->only('email', 'role'))
            ->withErrors(['email' => 'These credentials do not match our verified records.']);
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
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25'],
            'stream' => ['required', 'string'],
            'province' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'province' => $validated['province'],
            'role' => 'student',
            'status' => 'active',
        ]);

        // Create student profile
        StudentProfile::create([
            'user_id' => $user->id,
            'applicant_id' => 'UG-2026-88'.str_pad($user->id, 2, '0', STR_PAD_LEFT),
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'domicile_province' => $validated['province'],
            'stream' => $validated['stream'],
            'onboarding_completed' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        session(['uniguide_user_role' => 'student']);

        return redirect()->route('student.onboarding')
            ->with('toast_success', 'Account registered successfully! Welcome to UniGuide Pakistan.');
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

        return redirect()->route('auth.login')->with('toast_success', 'Password reset instructions sent to '.$request->input('email').'. Please check your inbox.');
    }

    /**
     * Log the user out and redirect to home.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('toast_info', 'You have been safely signed out of UniGuide Pakistan.');
    }
}
