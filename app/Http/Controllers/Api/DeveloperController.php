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
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 1, // Assigne explicitement le role_id pour les développeurs
        ]);

        $developerData = $request->validated();
        $developerData['user_id'] = $user->id;

        $developer = Developer::create($developerData);

        return response()->json($developer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Developer $developer)
    {
        return response()->json($developer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeveloperUpdateRequest  $request, Developer $developer)
    {
        $developer->update($request->validated());
        return response()->json($developer, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Developer $developer)
    {
        $developer->delete();
        return response()->json(null, 204);
    }


    /* ===== Customs methods  ===== */

    /**
     * Filter developers based on the provided criteria.
     */
    public function filterForm(Request $request)
    {
        $developers = $this->filterResources(Developer::where('is_validated', 1), $request)->get();
        return response()->json($developers);
    }

    /**
     * Affiche les candidatures crées par le développeur
     */
    public function developerApplications(Developer $developer)
    {
        // Récupération des candidatures créées par le développeur spécifique
        $applications = $developer->applications;

        return response()->json($applications);
    }
}
