<?php

namespace App\Policies;

use App\Models\User;

class NationalAdminPolicy
{
    /**
     * Determine whether the user has national regulatory authority.
     */
    public function manageNational(User $user): bool
    {
        $role = $user->role ?? session()->get('uniguide_user_role', 'admin');

        return in_array($role, ['admin', 'super_admin', 'hec_regulator']);
    }
}
