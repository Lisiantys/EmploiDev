<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {

            // si la connexion fonctionne, on récupère l'utilisateur et on charge son rôle
            $authUser = User::find(Auth::user()->id);
            $authUser->load('role');

            // on renvoie la réponse 
            return response()->json([$authUser, 'Vous êtes connecté']);
        } else {
            // si échec de la connexion, on renvoie un message d'erreur
            return response()->json(['Echec de la connexion.', 'errors' => 'L\'utilisateur n\'existe pas ou le mot de passe est incorrect']);
        }
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        // Optionally, revoke all tokens for security...
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    /**
     * Get the authenticated user.
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
