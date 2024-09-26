<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeveloperFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'contract_id' => 'nullable|array', // Autoriser plusieurs contrats
            'contract_id.*' => 'integer|exists:types_contracts,id', // Validation de chaque contrat
            'year_id' => 'nullable|array', // Autoriser plusieurs années d'expérience
            'year_id.*' => 'integer|exists:years_experiences,id', // Validation de chaque année d'expérience
            'location_id' => 'nullable|integer|exists:locations,id', // Un seul ID pour la localisation
            'type_id' => 'nullable|integer|exists:types_developers,id', // Un seul type de développeur
            'programming_languages' => 'nullable|array', // Autoriser plusieurs langages de programmation
            'programming_languages.*' => 'exists:programming_languages,id', // Validation de chaque langage
            'postal_code' => 'nullable|string', // Validation pour le code postal ou la ville
        ];
    }
}
