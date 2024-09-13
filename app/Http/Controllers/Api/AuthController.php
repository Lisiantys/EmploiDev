<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Connexion réussie', 'user' => Auth::user()]);
        }

        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
