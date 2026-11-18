<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class ShellPreviewController extends Controller
{
    /**
     * Preview Shell 1: Public Marketing & Discovery
     */
    public function public(): RedirectResponse
    {
        return redirect()->route('home');
    }

    /**
     * Preview Shell 2: Authenticated Student Portal
     */
    public function student(): RedirectResponse
    {
        return redirect()->route('student.dashboard');
    }

    /**
     * Preview Shell 3: University Administrator Console
     */
    public function universityAdmin(): RedirectResponse
    {
        return redirect()->route('uni_admin.dashboard');
    }

    /**
     * Preview Shell 4: Super Administrator Command
     */
    public function nationalAdmin(): RedirectResponse
    {
        return redirect()->route('national_admin.dashboard');
    }
}
