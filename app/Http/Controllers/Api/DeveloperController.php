<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Location;
use App\Models\Developer;
use Illuminate\Http\Request;
use App\Models\TypesContract;
use App\Models\TypesDeveloper;
use App\Models\YearsExperience;
use App\Models\ProgrammingLanguage;
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
     * Affiche les développeurs validés libres en premier (is_validated = 1) dans un ordre aléatoire, puis les non libres.
     */
    public function index()
    {
        $developers = Developer::where('is_validated', 1)
        ->orderBy('is_free', 'desc')
        ->paginate(8);

        $developers->getCollection()->transform(function ($developer) {
            $developer->profil_image = asset('storage/' . $developer->profil_image);
            return $developer;
        });

        return response()->json($developers, 200);
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
            'role_id' => 1,
        ]);

        // Gestion des fichiers
        $imagePath = $request->hasFile('profil_image')
            ? $request->file('profil_image')->store('public/images')
            : 'public/images/user.jpg';

        $cvPath = $request->file('cv')->store('public/cv');
        $coverLetterPath = $request->file('cover_letter')->store('public/cover_letters');

        // Création du développeur
        $developer = $user->developer()->create(array_merge(
            $request->validated(),
            [
                'profil_image' => $imagePath,
                'cv' => $cvPath,
                'cover_letter' => $coverLetterPath,
            ]
        ));

        // Attacher les langages de programmation au développeur
        $programmingLanguages = $request->input('programming_languages');
        $developer->programmingLanguages()->attach($programmingLanguages);

        Auth::login($user);

        // Régénérer la session après la connexion
        $request->session()->regenerate();

        // Retourner la réponse avec les relations automatiquement chargées
        return response()->json([
            'message' => 'Développeur créé avec succès et vous êtes connecté.',
            'developer' => $developer,
            'programming_languages' => $developer->programmingLanguages,
            'user' => $user,
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
            if ($developer->profil_image !== 'public/images/user.jpg') {
                Storage::delete($developer->profil_image);
            }
            $imagePath = $request->file('profil_image')->store('public/images');
        } else {
            $imagePath = $developer->profil_image;
        }

        // Gestion du CV
        if ($request->hasFile('cv')) {

            Storage::delete($developer->cv);

            $cvPath = $request->file('cv')->store('public/cv');
        } else {
            $cvPath = $developer->cv;
        }

        // Gestion de la lettre de motivation
        if ($request->hasFile('cover_letter')) {
            Storage::delete($developer->cover_letter);
            $coverLetterPath = $request->file('cover_letter')->store('public/cover_letters');
        } else {
            $coverLetterPath = $developer->cover_letter;
        }

        // Invalider le développeur jusqu'à revalidation
        $developer->is_validated = 0;

        // Fusionner les données validées avec les nouveaux chemins des fichiers
        $developerData = array_merge($request->validated(), [
            'profil_image' => $imagePath,
            'cv' => $cvPath,
            'cover_letter' => $coverLetterPath,
        ]);

        // Mettre à jour le développeur
        $developer->update($developerData);

        // Mettre à jour l'utilisateur associé (si nécessaire)
        if ($request->has('email') || $request->has('password')) {
            $userData = $request->only('email', 'password');
            if (isset($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }
            $developer->user->update($userData);
        }

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
    public function destroy(Developer $developer, Request $request)
    {
        $this->authorize('delete', $developer);

        $user = $request->user();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
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
        $developers = $this->filterResources($developersQuery, $request, 'developers')->paginate(8);

        $developers->getCollection()->transform(function ($developer) {
            $developer->profil_image = asset('storage/' . $developer->profil_image);
            return $developer;
        });

        return response()->json($developers, 200);
    }

    /**
     * Affiche les candidatures crées par le développeur
     */
    public function developerApplications(Developer $developer) // OK
    {
        $this->authorize('viewApplications', $developer);

        // Récupération des candidatures associées au développeur
        $applications = $developer->applications;

        // Vérifie si des candidatures existent
        if ($applications->isEmpty()) {
            return response()->json(['message' => 'Aucune candidature trouvée pour ce développeur.'], 404);
        }

        return response()->json([
            'message' => 'Candidatures récupérées avec succès.',
            'applications' => $applications
        ], 200);
    }

    //** Récupération des données pour le formulaire **/
    public function getProgrammingLanguages()
    {
        $languages = ProgrammingLanguage::all();
        return response()->json($languages, 200);
    }

    public function getTypesContracts()
    {
        $contracts = TypesContract::all();
        return response()->json($contracts, 200);
    }

    public function getTypesDevelopers()
    {
        $types = TypesDeveloper::all();
        return response()->json($types, 200);
    }

    public function getYearsExperiences()
    {
        $years = YearsExperience::all();
        return response()->json($years, 200);
    }

    public function getLocations()
    {
        $locations = Location::all();
        return response()->json($locations, 200);
    }

}
