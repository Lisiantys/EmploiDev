<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobOfferStoreRequest;
use App\Http\Requests\JobOfferUpdateRequest;

class JobOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobOffers = JobOffer::all();
        return response()->json($jobOffers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobOfferStoreRequest $request)
    {
        $jobOffer = JobOffer::create($request->validated());
        return response()->json($jobOffer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobOffer $jobOffer)
    {
        return response()->json($jobOffer);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(JobOfferUpdateRequest $request, JobOffer $jobOffer)
    {
        $jobOffer->update($request->validated());
        return response()->json($jobOffer, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobOffer $jobOffer)
    {
        $jobOffer->delete();
        return response()->json(null, 204);
    }
}
