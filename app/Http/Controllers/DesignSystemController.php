<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DesignSystemController extends Controller
{
    /**
     * Display the comprehensive living design system showcase.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('home');
    }
}
