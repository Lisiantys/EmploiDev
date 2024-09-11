<?php

namespace App\Http\Controllers\Api;

use App\Models\Developer;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationStoreRequest;
use App\Http\Requests\ApplicationUpdateRequest;

class ApplicationController extends Controller
{

    //APPLICATION C'EST LA CANDIDATURE 
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = Application::all();
        return response()->json($applications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApplicationStoreRequest $request)
    {
        $application = Application::create($request->validate());
        return response()->json($application, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        return response()->json($application);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        $application->delete();
        return response()->json(null, 204);
    }
}
