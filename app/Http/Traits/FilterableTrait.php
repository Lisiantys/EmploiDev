<?php

namespace App\Http\Traits;

trait FilterableTrait
{
    /**
    * Filtre les ressources en fonction des critères fournis.
     */
    protected function filterResources($query, $request)
{
    // Filtrer par type de contrat
    if ($request->filled('contract_id')) {
        $query->where('contract_id', $request->contract_id);
    }

    // Filtrer par années d'expérience
    if ($request->filled('year_id')) {
        $query->where('year_id', $request->year_id);
    }

    // Filtrer par code postal ou département
    if ($request->filled('location_id')) {
        $query->where('location_id', $request->location_id);
    }

    // Filtrer par langages de programmation
    if ($request->filled('programming_languages')) {
        $query->whereHas('programmingLanguages', function ($query) use ($request) {
            $query->whereIn('programming_languages.id', $request->programming_languages);
        });
    }

    // Filtrer par type de développeur
    if ($request->filled('type_id')) {
        $query->where('type_id', $request->type_id);
    }

    // Filtrer par date de création
    if ($request->filled('created_at')) {
        $query->whereDate('created_at', $request->created_at);
    }

    return $query;
}
}
