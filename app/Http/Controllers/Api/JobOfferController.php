<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOffer;
use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\JobOfferStoreRequest;
use App\Http\Requests\JobOfferFilterRequest;

class JobOfferController extends Controller
{
    use FilterableTrait;

    /**
     * Affiche toutes les offres d'emploi validées.
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

        // Création de l'offre d'emploi avec l'ID de l'entreprise 
        $jobOffer = JobOffer::create(array_merge($request->validated(), [
            'company_id' => $companyId,
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
        $this->authorize('view', $jobOffer);

        return response()->json([
            'message' => 'Offre d\'emploi récupérée avec succès.',
            'job_offer' => $jobOffer
        ], 200);
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

    //Récupère les offres d'emploi personnelles de l'entreprise qui en fait la requête 
    public function getCompanyJobOffers()
    {
        $user = Auth::user();

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

        // Vérifier que l'entreprise est bien l'auteur associée à l'offre
        if ($user->company->id !== $jobOffer->company_id) {
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
     * Filtrage des offres d'emploi validées
     */
    public function filterForm(JobOfferFilterRequest $request)
    {
        $jobOffersQuery = JobOffer::where('is_validated', 1);

        // Appliquer les filtres via le trait FilterableTrait
        $jobOffers = $this->filterResources($jobOffersQuery, $request, 'job_offers')->paginate(9);

        return response()->json($jobOffers, 200);
    }
}
