<?php

namespace App\Http\Controllers\API;

use App\Models\JobOffer;
use App\Models\Developer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    //Récupère les offres d'emploi en attente de validation.
    public function pendingJobOffers()
    {
        $jobOffers = JobOffer::where('is_validated', 0)
            ->with(['company', 'typesContract', 'typesDeveloper', 'programmingLanguages', 'location'])
            ->paginate(15);

        return response()->json([
            'message' => 'Offres d\'emploi en attente récupérées avec succès.',
            'job_offers' => $jobOffers,
        ], 200);
    }

    //Récupère les développeurs en attente de validation.
    public function pendingDevelopers()
    {
        $developers = Developer::where('is_validated', 0)
            ->with(['user', 'location', 'typesDeveloper', 'programmingLanguages', 'typesContract'])
            ->paginate(15);

        $developers->getCollection()->transform(function ($developer) {
            $developer->profil_image = Storage::url($developer->profil_image);
            return $developer;
        });

        return response()->json([
            'message' => 'Développeurs en attente récupérés avec succès.',
            'developers' => $developers,
        ], 200);
    }

    // Valide une offre d'emploi
    public function validateJobOffer(JobOffer $jobOffer)
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est administrateur
        if ($user->role_id !== 3) { // 3 est l'ID du rôle administrateur
            return response()->json(['error' => 'Action non autorisée.'], 403);
        }

        // Vérifier si l'offre d'emploi est déjà validée
        if ($jobOffer->is_validated == 1) {
            return response()->json(['message' => 'Cette offre d\'emploi est déjà validée.'], 200);
        }

        // Valider l'offre d'emploi
        $jobOffer->is_validated = 1;
        $jobOffer->save();

        return response()->json(['message' => 'Offre d\'emploi validée avec succès.'], 200);
    }

    // Valide un développeur
    public function validateDeveloper(Developer $developer)
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est administrateur
        if ($user->role_id !== 3) {
            return response()->json(['error' => 'Action non autorisée.'], 403);
        }

        // Vérifier si le développeur est déjà validé
        if ($developer->is_validated == 1) {
            return response()->json(['message' => 'Ce développeur est déjà validé.'], 200);
        }

        // Valider le développeur
        $developer->is_validated = 1;
        $developer->save();

        return response()->json(['message' => 'Développeur validé avec succès.'], 200);
    }

    //Supprime le développeur et le user 
    public function deleteDeveloper(Developer $developer)
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est administrateur
        if ($user->role_id !== 3) { // 3 est l'ID du rôle administrateur
            return response()->json(['error' => 'Action non autorisée.'], 403);
        }

        // Récupérer l'utilisateur associé au développeur
        $associatedUser = $developer->user;

        // Supprimer le développeur
        $developer->delete();

        // Supprimer l'utilisateur associé, s'il existe
        if ($associatedUser) {
            $associatedUser->delete();
        }

        return response()->json(['message' => 'Développeur supprimé avec succès.'], 200);
    }
}
