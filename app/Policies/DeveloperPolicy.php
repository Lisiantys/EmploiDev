<?php

namespace App\Policies;

use App\Models\Developer;
use App\Models\User;

class DeveloperPolicy
{
    /**
     * Détermine qui à accès au profil personnel de la personne
     */
    public function view(?User $user, Developer $developer): bool
    {
        // Permet à tout le monde de voir les développeurs validées
        if ($developer->is_validated == 1) {
            return true;
        }

        // Seuls les administrateurs peuvent voir les offres non validées
        return $user && $user->role_id === 3;
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour un développeur.
     */
    public function update(User $user, Developer $developer): bool
    {
        return $user->id === $developer->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer un développeur.
     */
    public function delete(User $user, Developer $developer): bool
    {
        return $user->id === $developer->user_id;
    }
}
