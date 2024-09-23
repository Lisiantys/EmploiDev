<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Company;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;

class CompanyController extends Controller
{

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

        // Gestion de l'image de profil
        $imagePath = $request->hasFile('profil_image')
            ? $request->file('profil_image')->store('public/images')
            : 'public/images/company.jpg'; // Image par défaut si non fournie

        // Création de l'entreprise
        $company = $user->company()->create(array_merge(
            $request->validated(),
            [
                'profil_image' => $imagePath,
            ]
        ));

        // On connecte l'utilisateur
        Auth::login($user);

        // Régénérer la session après la connexion
        $request->session()->regenerate();

        // Retourner la réponse
        return response()->json([
            'message' => 'Entreprise créée avec succès et vous êtes connecté.',
            'company' => $company,
            'user' => $user
        ], 201);
    }


    /**
     * Affiche les détails d'une entreprise + offres d'emplois validés.
     */
    public function show(Company $company)
    {
        $validatedJobOffers = $company->jobOffers()->where('is_validated', 1)->get();

        return response()->json([
            'message' => 'Compagnie récupérée avec succès.',
            'company' => $company,
            'job_offers' => $validatedJobOffers
        ], 200);
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
