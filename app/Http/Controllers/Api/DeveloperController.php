<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DeveloperStoreRequest;
use App\Http\Requests\DeveloperFilterRequest;
use App\Http\Requests\DeveloperUpdateRequest;


class DeveloperController extends Controller
{
    use FilterableTrait;

    /**
     * Affiche les développeurs validés, libres en premier dans un ordre aléatoire, puis non libres.
     */
    public function index()
    {
        $developers = Developer::where('is_validated', 1)
            ->orderBy('is_free', 'desc')
            ->inRandomOrder()
            ->paginate(8);
        
        return response()->json([
            'message' => 'Liste des développeurs validés récupérée avec succès.',
            'developers' => $developers
        ], 200);
    }

    /**
     * Création d'un utilisateur pour ensuite créer le développeur.
     */
    public function store(DeveloperStoreRequest $request)
    {
        // Création de l'utilisateur
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 1, // Assigne explicitement le role_id pour les développeurs
        ]);

        // Gestion de l'image de profil
        $imagePath = $request->hasFile('profil_image')
        ? $request->file('profil_image')->store('public/images')
        : 'public/images/user.jpg'; // Image par défaut si non fournie

        // Création du développeur associé
        $developer = $user->developer()->create(array_merge(
            $request->validated(),
            ['profil_image' => $imagePath]
        ));

        // Attachement des langages de programmation
        $developer->programmingLanguages()->attach($request->programming_languages);

        return response()->json([
            'message' => 'Développeur créé avec succès.',
            'developer' => $developer
        ], 201);
    }

    /**
     * Affiche les détails d'un développeur validé.
     */
    public function show(Developer $developer)
    {
        if ($developer->is_validated == 1) {
            return response()->json([
                'message' => 'Développeur récupéré avec succès.',
                'developer' => $developer
            ], 200);
        } else {
            return response()->json(['message' => 'Développeur non validé.'], 403);
        }
    }

    /**
     * Met à jour les informations d'un développeur.
     */
    public function update(DeveloperUpdateRequest $request, Developer $developer)
    {
        $this->authorize('update', $developer);

        // Gestion de l'image de profil
        if ($request->hasFile('profil_image')) {
            // Supprimer l'ancienne image du développeur sauf si c'est l'image par défaut
            if ($developer->profil_image !== 'public/images/default.png') {
                Storage::delete($developer->profil_image);
            }

            // Enregistrer la nouvelle image
            $imagePath = $request->file('profil_image')->store('public/images');
        } else {
            // Conserver l'image actuelle si non modifiée
            $imagePath = $developer->profil_image;
        }

        // Invalider le développeur jusqu'à revalidation
        $developer->is_validated = 0;

        // Fusionner les données validées avec le chemin de l'image
        $developerData = array_merge($request->validated(), ['profil_image' => $imagePath]);

        // Mettre à jour le développeur
        $developer->update($developerData);

        // Mettre à jour l'utilisateur associé
        $developer->user->update($request->only('email', 'password'));

        // Mise à jour des langages du développeur
        if ($request->has('programming_languages')) {
            $developer->programmingLanguages()->sync($request->programming_languages);
        }

        return response()->json([
            'message' => 'Développeur mis à jour avec succès et en attente de validation.',
            'developer' => $developer
        ], 200);
    }


    /**
     * Supprime un développeur et l'utilisateur associé.
     */
    public function destroy(Developer $developer)
    {
        $this->authorize('delete', $developer);

        // Suppression de l'utilisateur, ce qui supprime en cascade le développeur
        $developer->user->delete();

        return response()->json(['message' => 'Développeur et utilisateur supprimés avec succès.'], 200);
    }

    /* ===== Customs methods  ===== */

    /**
     * Filtre les développeurs
     */
    public function filterForm(DeveloperFilterRequest $request) //OK
    {
        $developersQuery = Developer::where('is_validated', 1)
        ->orderBy('is_free', 'desc');

        // Appliquer les filtres via le trait FilterableTrait
        $developers = $this->filterResources($developersQuery, $request)->paginate(8);

        return response()->json([
            'message' => 'Liste des développeurs filtrée récupérée avec succès.',
            'developers' => $developers
        ], 200);
    }

    /**
     * Affiche les candidatures crées par le développeur
     */
    public function developerApplications(Developer $developer) //OK
    {
        $this->authorize('viewApplications', $developer);

        // Récupération des candidatures associées au développeur
        $applications = $developer->applications()->get();
    
        return response()->json([
            'message' => 'Candidatures récupérées avec succès.',
            'applications' => $applications
        ], 200);
    }

}
