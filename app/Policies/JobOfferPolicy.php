<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobOffer;

class JobOfferPolicy
{
    /**
     * Détermine si l'utilisateur peut créer une offre d'emploi.
     */
    public function create(User $user): bool
    {
        return $user->role_id === 2;
    }

    /**
     * Détermine si l'utilisateur peut supprimer une offre d'emploi.
     */
    public function delete(User $user, JobOffer $jobOffer): bool
    {
        return $user->id === $jobOffer->company->user_id || $user->role_id === 3; // 3 est l'ID du rôle admin
    }

}
