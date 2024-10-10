<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\TypesContract;
use App\Models\TypesDeveloper;
use App\Models\YearsExperience;
use App\Models\ProgrammingLanguage;
use App\Http\Controllers\Controller;

class ResourceController extends Controller
{
    /**
     * Récupération des langages de programmation.
     */
    public function getProgrammingLanguages()
    {
        $languages = ProgrammingLanguage::all();
        return response()->json($languages, 200);
    }

    /**
     * Récupération des types de contrats.
     */
    public function getTypesContracts()
    {
        $contracts = TypesContract::all();
        return response()->json($contracts, 200);
    }

    /**
     * Récupération des types de développeurs.
     */
    public function getTypesDevelopers()
    {
        $types = TypesDeveloper::all();
        return response()->json($types, 200);
    }

    /**
     * Récupération des années d'expérience.
     */
    public function getYearsExperiences()
    {
        $years = YearsExperience::all();
        return response()->json($years, 200);
    }

    /**
     * Récupération des localisations.
     */
    public function getLocations()
    {
        $locations = Location::all();
        return response()->json($locations, 200);
    }
}
