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
     * Affiche les développeurs validés,  libres en premier puis les non libres.
     */
    public function index()
{
    $developers = Developer::where('is_validated', 1)
        ->orderBy('is_free', 'desc')
        ->paginate(8);

    $developers->getCollection()->transform(function ($developer) {
        $developer->profil_image = Storage::url($developer->profil_image);
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
        // Gestion des fichiers
$imagePath = $request->hasFile('profil_image')
? $request->file('profil_image')->store('images', 'public')
: 'images/user.jpg';

$cvPath = $request->file('cv')->store('cv', 'public');
$coverLetterPath = $request->file('cover_letter')->store('cover_letters', 'public');

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

    //Met à jour un développeur
    public function update(DeveloperUpdateRequest $request, Developer $developer)
    {
        $this->authorize('update', $developer);

        // Prepare the developer data to update
        $developerData = [
            'first_name' => $request->input('first_name'),
            'surname' => $request->input('surname'),
            'description' => $request->input('description'), // Keep current description if not provided
            'is_free' => $request->input('is_free'),
            'contract_id' => $request->input('contract_id'),
            'year_id' => $request->input('year_id'),
            'location_id' => $request->input('location_id'),
            'type_id' => $request->input('type_id'),
        ];

        // Keep current CV, cover letter, and image if not updated
        $developerData['cv'] = $request->hasFile('cv') ? $request->file('cv')->store('cv') : $developer->cv;
        $developerData['cover_letter'] = $request->hasFile('cover_letter') ? $request->file('cover_letter')->store('cover_letters') : $developer->cover_letter;
        $developerData['profil_image'] = $request->hasFile('profil_image') ? $request->file('profil_image')->store('images') : $developer->profil_image;

        // Perform the update on the developer model
        $developer->update($developerData);

        // Update user if necessary
        if ($request->has('email') || $request->has('password')) {
            $userData = $request->only('email', 'password');

            if (!empty($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            } else {
                unset($userData['password']);
            }

            $developer->user->update($userData);
        }

        if ($request->has('programming_languages')) {
            $developer->programmingLanguages()->sync($request->programming_languages);
        }

        return response()->json([
            'message' => 'Développeur / user mis à jour',
            'developer' => $developer
        ], 200);
    }

    /**
     * Affiche les détails d'un développeur validé pour la consultation d'un profil public.
     */
    public function show(Developer $developer)
    {
        $this->authorize('view', $developer);

        $developer->profil_image = Storage::url($developer->profil_image);

        return response()->json([
            'message' => 'Développeur récupéré avec succès.',
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
     * Pour l'affichage du profil personnel du développeur
     */
    public function profile()
    {
        $user = Auth::user(); // Récupérer l'utilisateur authentifié

        // Charger les informations liées au rôle
        if ($user->role_id == 1) { // Développeur
            $developer = $user->developer; // Le modèle Developer inclut déjà les données utilisateur
            return response()->json($developer, 200);
        } else if ($user->role_id == 2) { // Entreprise
            $company = $user->company; // Charge les données de l'entreprise
            return response()->json($company, 200);
        }

        // Si l'utilisateur n'est ni développeur ni entreprise
        return response()->json(['message' => 'Role not found.'], 404);
    }

    /**
     * Filtre les développeurs
     */
    public function filterForm(DeveloperFilterRequest $request)
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

    public function downloadCv(Developer $developer)
    {
        // Vérifier si le fichier existe
        if (!$developer->cv || !Storage::exists($developer->cv)) {
            return response()->json(['message' => 'CV non disponible.'], 404);
        }

        return Storage::download($developer->cv);
    }

    public function downloadCoverLetter(Developer $developer)
    {
        // Vérifier si le fichier existe
        if (!$developer->cover_letter || !Storage::exists($developer->cover_letter)) {
            return response()->json(['message' => 'Lettre de motivation non disponible.'], 404);
        }

        return Storage::download($developer->cover_letter);
    }
}
