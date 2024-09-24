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
use App\Http\Requests\JobOfferUpdateRequest;
use App\Http\Requests\DeveloperFilterRequest;

class JobOfferController extends Controller
{
    use FilterableTrait;

    /**
     * Affiche toutes les offres d'emploi validées, en ordre randomisé.
     */
    public function index()
    {
        $jobOffers = JobOffer::where('is_validated', 1)->inRandomOrder()->get();

        return response()->json([
            'message' => 'Offres d\'emploi validées récupérées avec succès.',
            'job_offers' => $jobOffers,
        ], 200);
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
        if ($jobOffer->is_validated == 1) {
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

    /**
     * Filter Jobs offers based on the provided criteria.
     */
    public function filterForm(JobOfferFilterRequest $request)
    {
        $jobOffersQuery = JobOffer::where('is_validated', 1)
            ->orderBy('created_at', 'desc');

        // Appliquer les filtres via le trait FilterableTrait
        $jobOffers = $this->filterResources($jobOffersQuery, $request)->paginate(8);

        return response()->json([
            'message' => 'Liste des offres d\'emploi filtrée récupérée avec succès.',
            'job_offers' => $jobOffers
        ], 200);
    }
}
