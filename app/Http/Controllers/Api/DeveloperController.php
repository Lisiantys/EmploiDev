<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\DeveloperStoreRequest;
use App\Http\Requests\DeveloperFilterRequest;
use App\Http\Requests\DeveloperUpdateRequest;


class DeveloperController extends Controller
{
    use FilterableTrait;
    /**
     * Affiche les développeurs validés
     */
    public function index()
    {
        $developers = Developer::where('is_validated', 1)->get();
        return response()->json($developers);
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

        // Création du développeur
        $developer = $user->developer()->create($request->validated());

        // Attachement des langages de programmation
        $developer->programmingLanguages()->attach($request->programming_languages);

        return response()->json($developer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Developer $developer)
    {
        return response()->json($developer, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeveloperUpdateRequest  $request, Developer $developer)
    {
        $developer->update($request->validated());

        // Mettre à jour l'utilisateur associé
        $developer->user->update($request->only('email', 'password'));

        // Mise à jour des langages du développeur
        if ($request->has('programming_languages')) {
            $developer->programmingLanguages()->sync($request->programming_languages);
        }

        return response()->json($developer, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Developer $developer)
    {
        $developer->delete();
        return response()->json($developer, 200);
    }

    /* ===== Customs methods  ===== */

    /**
     * Filtre les développeurs
     */
    public function filterForm(DeveloperFilterRequest $request) //OK
    {
        $developers = $this->filterResources(Developer::where('is_validated', 1), $request)->get();
        $developers = Developer::where('is_validated', 1); // Commencez par le modèle de développeur

        // Appliquer les filtres
        $developers = $this->filterResources($developers, $request)->get();

        return response()->json($developers);
    }

    /**
     * Affiche les candidatures crées par le développeur
     */
    public function developerApplications(Developer $developer) //OK
    {
        // Récupération des candidatures associées au développeur spécifique
        $applications = $developer->applications()->get();

        return response()->json($applications);
    }

}
