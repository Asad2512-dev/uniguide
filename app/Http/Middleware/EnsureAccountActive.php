<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAccountActive
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('auth.login')->withErrors(['email' => 'This account is inactive.']);
        }
        $request->user()?->notifications()->where('status', 'scheduled')->where('dispatched_at', '<=', now())->update(['status' => 'active']);

        return $next($request);
    }
}
