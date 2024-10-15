<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
    */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté et a le role_id = 3 (Administrateur)
        if (Auth::check() && Auth::user()->role_id === 3) {
            return $next($request);
        }

        // Si l'utilisateur n'est pas administrateur, retourner une réponse d'erreur
        return response()->json(['message' => 'Unauthorized.'], 403);
    }
}
