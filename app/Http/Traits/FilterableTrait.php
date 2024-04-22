<?php

namespace App\Http\Traits;

trait FilterableTrait
{
    /**
     * Filter resources based on the provided criteria.
     */
    protected function filterResources($query, $request)
    {
        // Filtrer par type de contrat
        if ($request->has('contract_id')) {
            $query->where('contract_id', $request->contract_id);
        }

        // Filtrer par années d'expérience
        if ($request->has('year_id')) {
            $query->where('year_id', $request->year_id);
        }

        // Filtrer par code postal ou département
        if ($request->has('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filtrer par langages de programmation
        if ($request->has('programming_languages')) {
            $query->whereHas('programmingLanguages', function ($query) use ($request) {
                $query->whereIn('id', $request->programming_languages);
            });
        }

        // Filtrer par type de développeur
        if ($request->has('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        // Filtrer par date de création
        if ($request->has('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }

        return $query;
    }
}
