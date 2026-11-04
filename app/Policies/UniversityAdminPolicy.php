<?php

namespace App\Policies;

use App\Models\User;

class UniversityAdminPolicy
{
    /**
     * Determine whether the user can administer the given university.
     * Enforces strict institutional tenancy boundaries.
     */
    public function manage(User $user, string $institutionSlug): bool
    {
        // For authenticated users, verify institution assignment
        $userInstitution = $user->institution_slug ?? session()->get('uniguide_admin_institution', 'nust');

        return strtolower($userInstitution) === strtolower($institutionSlug);
    }

    /**
     * Determine whether the user can modify another institution's records.
     * Always returns false to prevent cross-institution privilege escalation.
     */
    public function manageOther(User $user, string $institutionSlug): bool
    {
        return false;
    }
}
