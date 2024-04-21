<?php

namespace App\Http\Controllers\API;

use App\Models\JobOffer;
use App\Models\Developer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Affiche les offres d'emplois non validés
     */
    public function pendingJobOffers()
    {
        $pendingJobOffers = JobOffer::where('is_validated', 0)->get();
        return response()->json($pendingJobOffers);
    }

    /**
     * Affiche les développeurs non validées
     */
    public function pendingDevelopers()
    {
        $pendingDevelopers = Developer::where('is_validated', 0)->get();
        return response()->json($pendingDevelopers);
    }

    /**
     * Accpeter ou refuser une offre d'emploi
     */
    public function handleJobOffer($id, $action)
    {
        $jobOffer = JobOffer::findOrFail($id);

        if ($action === 'accept') {
            $jobOffer->is_validated = 1;
            $jobOffer->save();
            return response()->json(['message' => 'Offre d\'emploi accepté OK'], 200);
        } elseif ($action === 'reject') {
            $jobOffer->delete();
            return response()->json(['message' => 'Offre d\'emploi refusé OK'], 204);
        }

        return response()->json(['message' => 'Action invalide'], 400);
    }

    /**
     * Accepter ou refuser le profil développeur
     */
    public function handleDeveloper($id, $action)
    {
        $developer = Developer::findOrFail($id);

        if ($action === 'accept') {
            $developer->is_validated = 1;
            $developer->save();
            return response()->json(['message' => 'Développeur accepté OK'], 200);
        } elseif ($action === 'reject') {
            $developer->delete();
            return response()->json(['message' => 'Développeur refusé OK'], 204);

        }
        return response()->json(['message' => 'Action invalide'], 400);
    }
}
