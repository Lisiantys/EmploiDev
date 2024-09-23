<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobOfferStoreRequest extends FormRequest
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
            'name' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:20|max:1024',
            'is_validated' => 'sometimes|boolean',
            'contract_id' => 'required|exists:types_contracts,id',
            'year_id' => 'required|exists:years_experiences,id',
            'location_id' => 'required|exists:locations,id',
            'type_id' => 'required|exists:types_developers,id',
            'company_id' => 'required|exists:companies,id',
            'programming_languages' => 'required|array',
            'programming_languages.*' => 'exists:programming_languages,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom de l\'offre est obligatoire.',
            'name.string' => 'Le nom de l\'offre doit être une chaîne de caractères.',
            'name.min' => 'Le nom de l\'offre doit contenir au moins :min caractères.',
            'name.max' => 'Le nom de l\'offre ne doit pas dépasser :max caractères.',

            'description.required' => 'La description est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.min' => 'La description doit contenir au moins :min caractères.',
            'description.max' => 'La description ne doit pas dépasser :max caractères.',

            'is_validated.boolean' => 'Le statut de validation doit être vrai ou faux.',

            'contract_id.required' => 'Le type de contrat est obligatoire.',
            'contract_id.exists' => 'Le type de contrat sélectionné est invalide.',

            'year_id.required' => 'L\'année d\'expérience est obligatoire.',
            'year_id.exists' => 'L\'année d\'expérience sélectionnée est invalide.',

            'location_id.required' => 'La localisation est obligatoire.',
            'location_id.exists' => 'La localisation sélectionnée est invalide.',

            'type_id.required' => 'Le type de développeur est obligatoire.',
            'type_id.exists' => 'Le type de développeur sélectionné est invalide.',

            'programming_languages.required' => 'Les langages de programmation sont obligatoires.',
            'programming_languages.array' => 'Les langages de programmation doivent être un tableau.',
            'programming_languages.*.exists' => 'Le langage de programmation sélectionné est invalide.',
        ];
    }
}
