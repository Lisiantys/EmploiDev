<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Company;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
        // Création de l'utilisateur
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2, // Assigne explicitement le role_id pour les entreprises
        ]);

        // Création de l'entreprise
        $company = $user->company()->create($request->validated());

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
        // Mettre à jour les détails de l'entreprise
        $company->update($request->validated());

        // Mettre à jour l'utilisateur associé
        $company->user->update($request->only('email', 'password'));

        return response()->json($company, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Company $company)
    // {
    //     $company->delete();
    //     return response()->json($company, 200);
    // }

    /* ===== Customs methods  ===== */

    /**
     * L'entreprise consulte ses offres d'emploies
     */
    public function jobOffers(Company $company) //OK
    {
        $jobOffers = $company->jobOffers()->get();
        return response()->json($jobOffers);
    }

    /**
     * Affiche les candidatures associées à l'offre d'emploi + leurs candidature
     */
    public function jobOfferApplications(Company $company, JobOffer $jobOffer) //OK
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
