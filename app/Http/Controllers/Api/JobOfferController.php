<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOffer;
use App\Models\Developer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\JobOfferStoreRequest;
use App\Http\Requests\JobOfferFilterRequest;

class JobOfferController extends Controller
{
    use FilterableTrait;

    /**
     * Affiche toutes les offres d'emploi validées, en ordre randomisé.
     */
    public function index()
    {
        $jobOffers = JobOffer::where('is_validated', 1)
        ->paginate(9);

        return response()->json($jobOffers, 200);
    }

    /**
     * Crée une nouvelle offre d'emploi.
     */
    public function store(JobOfferStoreRequest $request)
    {
        $this->authorize('create', JobOffer::class);

        // Récupérer l'entreprise associée à l'utilisateur connecté
        $companyId = Auth::user()->company->id;

        // Création de l'offre d'emploi avec l'ID de l'entreprise connecté
        $jobOffer = JobOffer::create(array_merge($request->validated(), [
            'company_id' => $companyId, // Assigne l'ID de l'entreprise
        ]));

        // Attachement des langages de programmation
        $jobOffer->programmingLanguages()->attach($request->programming_languages);

        return response()->json([
            'message' => 'Offre d\'emploi créée avec succès.',
            'job_offer' => $jobOffer,
        ], 201);
    }

    /**
     * Affiche une offre d'emploi spécifique.
     */
    public function show(JobOffer $jobOffer)
    {
        $user = Auth::user()->role_id;
        if ($jobOffer->is_validated == 1 || $user === 3) {
            return response()->json([
                'message' => 'Offre d\'emploi récupérée avec succès.',
                'job_offer' => $jobOffer
            ], 200);
        } else {
            return response()->json(['message' => 'Offre d\'emploi non validée.'], 403);
        }
    }

    /**
     * Supprime une offre d'emploi.
     */
    public function destroy(JobOffer $jobOffer)
    {
        $this->authorize('delete', $jobOffer);

        $jobOffer->delete();

        return response()->json([
            'message' => 'Offre d\'emploi supprimée avec succès.',
        ], 200);
    }

    /* ===== Customs methods  ===== */


    public function getCompanyJobOffers()
{
    $user = Auth::user();

    if (!$user->company) {
        return response()->json(['error' => 'Aucune entreprise associée à cet utilisateur.'], 404);
    }

    $company = $user->company;

    // Récupérer les offres d'emploi de l'entreprise avec le comptage des candidatures en attente
    $jobOffers = JobOffer::with('location')
        ->where('company_id', $company->id)
        ->withCount(['applications as pending_applications_count' => function ($query) {
            $query->where('status', 'pending');
        }])
        ->get();

    return response()->json($jobOffers);
}


        // Affiche les candidatures reçues sur une offre d'emploi
        public function jobOfferApplications(JobOffer $jobOffer)
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est bien le propriétaire de l'entreprise associée à l'offre
        if (!$user->company || $user->company->id !== $jobOffer->company_id) {
            return response()->json(['error' => 'Action non autorisée.'], 403);
        }

        // Récupération des candidatures pour l'offre d'emploi, filtrées par statut (sauf 'rejected')
        $applications = $jobOffer->applications()
            ->whereIn('status', ['pending', 'accepted'])
            ->with('developer.user') // Inclure les informations du développeur
            ->get();

        return response()->json([
            'message' => 'Candidatures récupérées avec succès.',
            'jobOffer' => $jobOffer,
            'applications' => $applications,
        ], 200);
    }

    /**
     * Filter Jobs offers based on the provided criteria.
     */
    public function filterForm(JobOfferFilterRequest $request) {

        $jobOffersQuery = JobOffer::where('is_validated', 1);

        // Appliquer les filtres via le trait FilterableTrait
        $jobOffers = $this->filterResources($jobOffersQuery, $request, 'job_offers')->paginate(9);

        return response()->json($jobOffers, 200);

    }

}
