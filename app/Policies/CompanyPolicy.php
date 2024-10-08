<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;

class CompanyPolicy
{
    //J'utilise view pour autoriser l'entreprise à voir toutes ces offres d'emplois non validés + candidatures associées
    public function view(User $user, Company $company): bool
    {
        return $user->id === $company->user_id;
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour une entreprise.
     */
    public function update(User $user, Company $company): bool
    {
        return $user->id === $company->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer une entreprise.
     */
    public function delete(User $user, Company $company): bool
    {
        return $user->id === $company->user_id; 
    }
}
