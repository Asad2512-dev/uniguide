<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUniversityAdminAuthorized
{
    /**
     * Handle an incoming request.
     * Enforces that an institutional administrator can only view and manage their authorized university.
     * Any attempt to access, tamper with, or override another university's private management records returns 403 Forbidden.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Retrieve the authorized institution for the current session/user
        $authorizedInstitution = session()->get('uniguide_admin_institution', 'nust');

        // Check if an explicit target institution is specified via request input or route parameter
        $targetInstitution = $request->route('institution')
            ?? $request->input('target_institution')
            ?? $request->query('institution');

        if ($targetInstitution && strtolower($targetInstitution) !== strtolower($authorizedInstitution)) {
            abort(403, 'Unauthorized Institutional Access: You do not have administrative clearance to access or modify records for '.htmlspecialchars($targetInstitution).'.');
        }

        return $next($request);
    }
}
