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
        if ($request->has('contract_type')) {
            $query->where('contract_id', $request->contract_type);
        }

        // Filtrer par années d'expérience
        if ($request->has('experience_years')) {
            $query->where('year_id', $request->experience_years);
        }

        // Filtrer par code postal ou département
        if ($request->has('location')) {
            $query->whereHas('location', function ($query) use ($request) {
                $query->where('city', 'like', '%' . $request->location . '%')
                      ->orWhere('postal_code', $request->location);
            });
        }

        // Filtrer par langages de programmation
        if ($request->has('programming_languages')) {
            $query->whereHas('programmingLanguages', function ($query) use ($request) {
                $query->whereIn('id', $request->programming_languages);
            });
        }

        // Filtrer par type de développeur
        if ($request->has('developer_type')) {
            $query->where('type_id', $request->developer_type);
        }

        // Filtrer par date de création
        if ($request->has('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }

        return $query;
    }
}
