<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Developer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\DeveloperStoreRequest;
use App\Http\Requests\DeveloperUpdateRequest;
use Illuminate\Support\Facades\Log;


class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $developers = Developer::all();
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
}
