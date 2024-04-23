<?php

namespace App\Policies;

use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobOfferPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JobOffer $jobOffer): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role_id === 2;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobOffer $jobOffer): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobOffer $jobOffer)
    {
        // Vérifier si l'utilisateur est l'entreprise qui a créé l'offre
        if ($user->role_id === 2) {
            return $user->id === $jobOffer->company->user_id;
        }
        
        // Vérifier si l'utilisateur est un administrateur
        if ($user->role_id === 3) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JobOffer $jobOffer): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JobOffer $jobOffer): bool
    {
        //
    }
}
