<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Company;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;

class CompanyController extends Controller
{

    /**
     * Création d'un user + entreprise
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
     * Met à jour les informations d'une entreprise.
     */
    public function update(CompanyUpdateRequest $request, Company $company)
{
    $this->authorize('update', $company);

    // Journalisation des données de la requête entrante
    Log::info('Requête de mise à jour reçue pour l\'entreprise :', ['id' => $company->id, 'data' => $request->all()]);

    // Préparation des données de l'entreprise à mettre à jour
    $companyData = [
        'name' => $request->input('name'),
        'description' => $request->input('description'),
    ];

    // Gestion de l'image de profil
    $companyData['profil_image'] = $request->hasFile('profil_image') ? $request->file('profil_image')->store('images') : $company->profil_image;

    // Effectuer la mise à jour sur le modèle de l'entreprise
    $company->update($companyData);

    // Mise à jour de l'utilisateur si nécessaire
    if ($request->has('email') || $request->has('password')) {
        $userData = $request->only('email', 'password');

        if (!empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $company->user->update($userData);
    }

    return response()->json([
        'message' => 'Entreprise / utilisateur mis à jour avec succès',
        'company' => $company
    ], 200);
}


    /**
     * Supprime une entreprise et l'utilisateur associé.
     */
    public function destroy(Company $company, Request $request)
    {
        $this->authorize('delete', $company);

        $user = $request->user();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $user->delete();

        return response()->json(['message' => 'Entreprise supprimée avec succès']);
    }

    /* ===== Customs methods  ===== */

    //Affiche les offres d'emplois de l'entreprise
    public function jobOffersCompany(Company $company)
    {
        $this->authorize('view', $company);

        // Récupération de toutes les offres d'emploi de l'entreprise
        $jobOffers = $company->jobOffers;

        return response()->json([
            'message' => 'Offres d\'emploi récupérées avec succès.',
            'job_offers' => $jobOffers,
        ], 200);
    }

    // Affiche les candidatures reçues sur une offre d'emploi
    public function jobOfferApplications(JobOffer $jobOffer)
    {
        $this->authorize('view', $jobOffer->company);

        // Récupération des candidatures pour l'offre d'emploi, filtrées par statut
        $applications = $jobOffer->applications()->whereIn('status', ['pending', 'accepted'])->get();

        return response()->json([
            'message' => 'Candidatures récupérées avec succès.',
            'applications' => $applications,
        ], 200);
    }
}
