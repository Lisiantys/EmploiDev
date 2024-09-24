<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOffer;
use App\Models\Developer;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApplicationStoreRequest;
use App\Http\Requests\ApplicationUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;

class ApplicationController extends Controller
{

    public function index(Request $request)
    {
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
                'message' => 'Unauthorized. Seul un développeur peut voir ses candidatures'
            ], 403);
        }
    }

    // Déposer une candidature
    public function store(ApplicationStoreRequest $request, Application $application)
    {
        $this->authorize('create', $application);

        // Vérifier que l'offre d'emploi est validée
        $jobOffer = JobOffer::find($request->job_id);

        if (!$jobOffer || $jobOffer->is_validated === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas candidater à une offre non validée.'
            ], 403);
        }

        // Vérifier si le développeur a déjà une candidature pour cette offre
        $existingApplication = Application::where('job_id', $request->job_id)
            ->where('developer_id', Auth::id())
            ->first();

        if ($existingApplication) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà envoyé une candidature pour cette offre d\'emploi.'
            ], 409);
        }

        // Récupérer le développeur connecté
        $developer = Developer::where('user_id', Auth::id())->first();

        // Récupérer le CV et la lettre de motivation du développeur, si non fournis
        $cv = $request->file('cv') ? $request->file('cv')->store('cv') : $developer->cv;
        $coverLetter = $request->file('cover_letter') ? $request->file('cover_letter')->store('cover_letters') : $developer->cover_letter;

        $application = Application::create([
            'description' => $request->description,
            'job_id' => $request->job_id,
            'developer_id' => Auth::id(),
            'cv' => $cv,
            'cover_letter' => $coverLetter,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Candidature posté avec succès.',
            'application' => $application,
        ], 201);
    }

    // Accepter une candidature
    public function acceptApplication(Application $application)
    {
        $this->authorize('accept', $application);

        if ($application->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas refuser ou accepter une candidature qui n\'est pas en attente.',
            ], 403);
        }

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

        if ($application->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas refuser ou accepter une candidature qui n\'est pas en attente.',
            ], 403);
        }

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
