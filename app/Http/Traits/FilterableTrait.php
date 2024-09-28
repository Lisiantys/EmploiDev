<?php

namespace App\Http\Traits;

trait FilterableTrait
{
    /**
     * Filtre les ressources en fonction des critères fournis.
     *
     * @param $query
     * @param $request
     * @param string $type - Type de modèle (developer ou job_offer)
     * @return mixed
     */
    protected function filterResources($query, $request, $type)
    {
        if ($request->filled('contract_id')) {
            $query->whereIn("{$type}.contract_id", $request->contract_id);
        }

        if ($request->filled('year_id')) {
            $query->whereIn("{$type}.year_id", $request->year_id);
        }

        if ($request->filled('location_id')) {
            $query->where("{$type}.location_id", $request->location_id);
        }

        if ($request->filled('type_id')) {
            $query->where("{$type}.type_id", $request->type_id);
        }

        if ($request->filled('programming_languages')) {
            $query->whereHas('programmingLanguages', function ($query) use ($request) {
                $query->whereIn('programming_languages.id', $request->programming_languages);
            });
        }

        return $query;
    }
}
