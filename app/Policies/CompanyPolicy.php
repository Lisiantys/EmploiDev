<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;

class CompanyPolicy
{
    /**
     * Détermine si l'utilisateur peut mettre à jour une entreprise.
     */
    public function update(User $user, Company $company): bool
    {
        return $user->id === $company->user_id || $user->role_id === 3;
    }

    /**
     * Détermine si l'utilisateur peut supprimer une entreprise.
     */
    public function delete(User $user, Company $company): bool
    {
        return $user->id === $company->user_id || $user->role_id === 3; 
    }
}
