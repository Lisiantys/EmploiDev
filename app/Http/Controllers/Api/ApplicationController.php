<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOffer;
use App\Models\Developer;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApplicationStoreRequest;

class ApplicationController extends Controller
{
    //Récupère les candiatures du développeur pour le développeur
    public function index(Request $request)
    {
        $this->authorize('view', $request);

        $user = Auth::user();

        // Récupérer toutes les applications du développeur
        $applications = Application::where('developer_id', $user->developer->id)->get();

        return response()->json([
            'success' => true,
            'data' => $applications
        ], 200);
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

        // Récupérer le développeur connecté
        $developer = Developer::where('user_id', Auth::id())->first();
        // Récupérer le CV et la lettre de motivation du développeur, si non fournis
        $cv = $request->file('cv') ? $request->file('cv')->store('cv') : $developer->cv;
        $coverLetter = $request->file('cover_letter') ? $request->file('cover_letter')->store('cover_letters') : $developer->cover_letter;

        $application = Application::create([
            'description' => $request->description,
            'job_id' => $request->job_id,
            'developer_id' => $developer->id,
            'cv' => $cv,
            'cover_letter' => $coverLetter,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Candidature posté avec succès.',
            'application' => $application,
        ], 201);
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

    /* ===== Customs methods  ===== */

    //Vérifie l'existence d'une candidature sur une offre d'emploi par rapport à un développeur
    public function checkExistingApplication(Request $request)
    {
        $developer = Developer::where('user_id', Auth::id())->first();

        $jobId = $request->query('job_id');

        $applicationExists = Application::where('job_id', $jobId)
            ->where('developer_id', $developer->id)
            ->exists();

        return response()->json(['has_applied' => $applicationExists]);
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
}
