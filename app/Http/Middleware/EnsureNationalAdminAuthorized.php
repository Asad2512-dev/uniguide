<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNationalAdminAuthorized
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('auth.login', ['role' => 'admin']);
        }
        abort_unless($request->user()->isNationalAdmin(), 403, 'Platform administrator access required.');

        return $next($request);
    }
}
