<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNationalAdminAuthorized
{
    /**
     * Handle an incoming request.
     * Enforces that only users with Federal Regulatory Authority / National Super Admin credentials
     * can access the national operations command center.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $role = $user ? $user->role : session()->get('uniguide_user_role', 'admin');

        // Check if explicit role parameter is provided (e.g., student attempting to access national admin)
        $requestedRole = $request->query('auth_role') ?? $request->input('auth_role');

        $allowedRoles = ['admin', 'super_admin', 'hec_regulator', 'national_admin'];

        if ($requestedRole && ! in_array($requestedRole, $allowedRoles)) {
            abort(403, 'Unauthorized Access: You do not possess Federal Regulatory Clearance for HEC National Command.');
        }

        if (! in_array($role, $allowedRoles)) {
            abort(403, 'Unauthorized Access: You do not possess Federal Regulatory Clearance for HEC National Command.');
        }

        return $next($request);
    }
}
