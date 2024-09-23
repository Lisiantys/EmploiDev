<?php

namespace App\Http\Controllers\Api;

use App\Models\Developer;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApplicationStoreRequest;
use App\Http\Requests\ApplicationUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;

class ApplicationController extends Controller
{

    public function index(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Vérifier si l'utilisateur est un développeur
        if ($user->developer) {
            // Récupérer toutes les applications du développeur
            $applications = Application::where('developer_id', $user->developer->id)->get();

            return response()->json([
                'success' => true,
                'data' => $applications
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only developers can view their applications.'
            ], 403);
        }
    }
    


    // Déposer une candidature
    public function store(ApplicationStoreRequest $request, Application $application)
    {
        $this->authorize('create', $application);

        $application = Application::create([
            'description' => $request->description,
            'job_id' => $request->job_id,
            'developer_id' => Auth::id(),
            'cv' => $request->cv,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Candidature déposée avec succès.',
            'application' => $application,
        ], 201);
    }

    // Accepter une candidature
    public function acceptApplication(Application $application)
    {
        $this->authorize('accept', $application);

        $application->status = 'accepted';
        $application->save();

        return response()->json([
            'message' => 'Candidature acceptée avec succès.',
        ], 200);
    }

    // Refuser une candidature
    public function refuseApplication(Application $application)
    {
        $this->authorize('refuse', $application);

        $application->status = 'rejected';
        $application->save();

        return response()->json([
            'message' => 'Candidature refusée avec succès.',
        ], 200);
    }

    // Afficher une candidature en détail
    public function show(Application $application)
    {
        $this->authorize('view', $application);

        return response()->json([
            'message' => 'Candidature récupérée avec succès.',
            'application' => $application,
        ], 200);
    }

    // Supprimer une candidature
    public function destroy(Application $application)
    {
        $this->authorize('delete', $application);

        $application->delete();

        return response()->json([
            'message' => 'Candidature supprimée avec succès.',
        ], 200);
    }
}
