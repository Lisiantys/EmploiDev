<?php

namespace App\Policies;

use Log;
use App\Models\User;
use App\Models\Developer;
use App\Models\Application;

class ApplicationPolicy
{
    /**
     * Seul les développeurs peuvent voir leurs candidatures en detail.
     */
    public function view(User $user, Application $application): bool
    {
        return $user->developer && $user->developer->id === $application->developer_id;
    }

    /**
     * Assure que l'utilisateur est une entreprise et qu'il a le droit d'accepter
     */
    public function accept(User $user, Application $application): bool
    {
        return $user->company->id === $application->jobOffer->company_id;
    }

    /**
     * Assure que l'utilisateur est une entreprise et qu'il a le droit de refuser
     */
    public function refuse(User $user, Application $application): bool
    {
        return $user->company->id === $application->jobOffer->company_id;
    }

    /**
     * Seul les développeurs peuvent créer une candidature
     */
    public function create(User $user): bool
    {
        return $user->role_id === 1;
    }

    /**
     * Seul le développeur peut supprimer sa propre candidature
     */
    public function delete(User $user, Application $application): bool
    {
        return $user->developer && $user->developer->id === $application->developer_id;
    }
}
