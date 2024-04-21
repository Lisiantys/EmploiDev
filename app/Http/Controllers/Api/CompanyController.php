<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        return response()->json($companies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyStoreRequest $request)
    {
        $company = Company::create($request->validated());
        return response()->json($company, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, Company $company)
    {
        $company->update($request->validated());
        return response()->json($company, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json(null, 204);
    }

    /* ===== Customs methods  ===== */

    /**
     * L'entreprise consulte ses offres d'emploies
     */
    public function jobOffers(Company $company)
    {
        $jobOffers = $company->jobOffers()->get();
        return response()->json($jobOffers);
    }

    /**
     * Affiche les candidatures associées à l'offre d'emploi
     */
    public function jobOfferApplications(Company $company, JobOffer $jobOffer)
    {
        // Vérification que l'offre d'emploi appartient bien à l'entreprise
        if ($jobOffer->company_id !== $company->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Récupération des candidatures associées à l'offre d'emploi spécifique
        $applications = $jobOffer->applications;

        return response()->json($applications);
    }
}
