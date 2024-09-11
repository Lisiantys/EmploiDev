<?php

namespace App\Policies;

use App\Models\Developer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeveloperPolicy
{

    /**
     * Détermine si l'utilisateur peut voir un développeur.
     */
    public function view(Developer $developer): bool
    {
        return $developer->is_validated === 1;
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
