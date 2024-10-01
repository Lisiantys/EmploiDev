<?php

namespace App\Policies;

use App\Models\Developer;
use App\Models\User;

class DeveloperPolicy
{

    /**
     * Détermine qui à accès au profil personnel de la personne
     */
    public function view(Developer $developer): bool
    {
        return $developer->is_validated === 1;
    }

    public function viewProfil(User $user, Developer $developer)
    {
        return $user->id === $developer->user_id;
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour un développeur.
     */
    public function update(User $user, Developer $developer): bool
    {
        return $user->id === $developer->user_id || $user->role_id === 3;
    }

    /**
     * Détermine si l'utilisateur peut supprimer un développeur.
     */
    public function delete(User $user, Developer $developer): bool
    {
        return $user->id === $developer->user_id || $user->role_id === 3;
    }

    /**
     * Détermine si l'utilisateur peut voir les candidatures du développeur.
     */
    public function viewApplications(User $user, Developer $developer)
    {
        // Seul le développeur lui-même peut voir ses propres candidatures
        return $user->id === $developer->user_id;
    }
}
