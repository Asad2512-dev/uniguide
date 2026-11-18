<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function password()
    {
        return view('pages.auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate(['current_password' => 'required|current_password', 'password' => 'required|string|min:8|confirmed']);
        $request->user()->update(['password' => Hash::make($data['password']), 'remember_token' => Str::random(60)]);
        $request->session()->regenerate();

        return back()->with('toast_success', 'Password updated.');
    }
}
