<?php

namespace App\Http\Traits;

trait FilterableTrait
{
    /**
     * Filtre les ressources en fonction des critères fournis.
     */
    protected function filterResources($query, $request)
    {
        // Filtrage par type de contrat
        if ($request->filled('contract_id')) {
            $query->whereIn('contract_id', $request->contract_id);
        }

        // Filtrage par années d'expérience
        if ($request->filled('year_id')) {
            $query->whereIn('year_id', $request->year_id);
        }

        // Filtrage par localisation
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filtrage par langages de programmation
        if ($request->filled('programming_languages')) {
            $query->whereHas('programmingLanguages', function ($query) use ($request) {
                $query->whereIn('language_id', $request->programming_languages);
            });
        }

        // Filtrage par type de développeur
        if ($request->filled('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        return $query;
    }
}
