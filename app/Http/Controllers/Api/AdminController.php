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

    //Récupère les entreprises
    public function handleCompanies()
    {
        $companies = Company::paginate(15);

        return response()->json([
            'message' => 'Entreprises récupérées avec succès.',
            'companies' => $companies,
        ], 200);
    }

    //Récupère les développeurs
    public function handleDevelopers()
    {
        $developers = Developer::paginate(15);

        return response()->json([
            'message' => 'Développeurs récupérés avec succès.',
            'developers' => $developers,
        ], 200);
    }

    //Filtre les devéloppeurs et entreprises à l'aide des emails.
    public function filterByEmail(Request $request)
    {
        $email = $request->input('email');

        $users = User::where('email', 'like', "%{$email}%")->get();

        return response()->json([
            'message' => 'Résultats filtrés récupérés avec succès.',
            'users' => $users,
        ], 200);
    }
}
