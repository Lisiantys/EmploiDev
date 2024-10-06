<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Company;
use App\Models\JobOffer;
use App\Models\Developer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    //Récupère les offres d'emploi en attente de validation.
    public function pendingJobOffers()
    {
        $jobOffers = JobOffer::where('is_validated', 0)->paginate(15);

        return response()->json([
            'message' => 'Offres d\'emploi en attente récupérées avec succès.',
            'job_offers' => $jobOffers,
        ], 200);
    }

    //Récupère les développeurs en attente de validation.
    public function pendingDevelopers()
    {
        $developers = Developer::where('is_validated', 0)->paginate(15);

        return response()->json([
            'message' => 'Développeurs en attente récupérés avec succès.',
            'developers' => $developers,
        ], 200);
    }
}
