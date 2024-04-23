<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOffer;
use App\Models\Developer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableTrait;
use App\Http\Requests\JobOfferStoreRequest;
use App\Http\Requests\JobOfferFilterRequest;
use App\Http\Requests\JobOfferUpdateRequest;
use App\Http\Requests\DeveloperFilterRequest;

class JobOfferController extends Controller
{
    use FilterableTrait;
    /**
     * Affiche les offres d'emplois validés
     */
    public function index()
    {
        $jobOffers = JobOffer::where('is_validated', 1)->get();
        return response()->json($jobOffers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobOfferStoreRequest $request)
    {
        $jobOffer = JobOffer::create($request->validated());

        // Attachement des langages de programmation
        $jobOffer->programmingLanguages()->attach($request->programming_languages);
        return response()->json($jobOffer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobOffer $jobOffer)
    {
        return response()->json($jobOffer);
    }
    /**
     * Update the specified resource in storage. VOIR SI ON FAIT LUPDATE OU NON + POLICIES 
     */ 
    public function update(JobOfferUpdateRequest $request, JobOffer $jobOffer)
    {
        $validatedData = $request->validated();

        // Supprimer les anciens langages de programmation et associer les nouveaux
        if (isset($validatedData['programming_languages'])) {
            $jobOffer->programmingLanguages()->sync($validatedData['programming_languages']);
            unset($validatedData['programming_languages']); // Retirer les langages de programmation du tableau pour ne pas les passer à la méthode update
        }

        // Mettre à jour les autres champs de l'offre d'emploi
        $jobOffer->update($validatedData);

        // Mise à jour des langages du développeur
        if ($request->has('programming_languages')) {
            $jobOffer->programmingLanguages()->sync($request->programming_languages);
        }

        return response()->json($jobOffer->fresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobOffer $jobOffer)
    {
        $this->authorize('delete', $jobOffer);

        $jobOffer->delete();

        return response()->json($jobOffer, 200);
    }


    /* ===== Customs methods  ===== */

    /**
     * Filter Jobs offers based on the provided criteria.
     */
    public function filterForm(JobOfferFilterRequest $request) //OK
    {
        $jobOffers = $this->filterResources(JobOffer::where('is_validated', 1), $request)->get();
      // dd($jobOffers);
        return response()->json($jobOffers);
    }
}
