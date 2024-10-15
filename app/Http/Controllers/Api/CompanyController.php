<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
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
            ? $request->file('profil_image')->store('images', 'public')
            : 'images/company.jpg'; // Image par défaut si non fournie

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
     * Met à jour les informations d'une entreprise.
     */
    public function update(CompanyUpdateRequest $request, Company $company)
    {
        $this->authorize('update', $company);

        // Préparation des données de l'entreprise à mettre à jour
        $companyData = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        // Gestion de l'image de profil
        if ($request->hasFile('profil_image')) {
            // Stocker la nouvelle image dans 'images' sur le disque 'public'
            $newImagePath = $request->file('profil_image')->store('images', 'public');

            // Vérifier si l'image actuelle n'est pas l'image par défaut
            if ($company->profil_image && $company->profil_image !== 'images/company.jpg') {
                // Supprimer l'ancienne image
                Storage::disk('public')->delete($company->profil_image);
            }

            // Mettre à jour le chemin de l'image de profil dans les données de l'entreprise
            $companyData['profil_image'] = $newImagePath;
        } else {
            // Si aucune nouvelle image n'est fournie, conserver l'image actuelle
            $companyData['profil_image'] = $company->profil_image;
        }

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

        // Vérifier si l'image de profil n'est pas l'image par défaut
        if ($company->profil_image && $company->profil_image !== 'images/company.jpg') {
            // Supprimer l'image de profil du stockage public
            Storage::disk('public')->delete($company->profil_image);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $company->delete();

        return response()->json(['message' => 'Entreprise supprimée avec succès']);
    }
}
