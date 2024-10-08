<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobOffer;

class JobOfferPolicy
{
    public function view(?User $user, JobOffer $jobOffer)
    {
        // Permet à tout le monde de voir les offres validées
        if ($jobOffer->is_validated == 1) {
            return true;
        }

        // Seuls les administrateurs peuvent voir les offres non validées
        return $user && $user->role_id === 3;
    }

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
