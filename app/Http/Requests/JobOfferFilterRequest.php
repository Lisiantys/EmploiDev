<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobOfferFilterRequest extends FormRequest
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
            'contract_id' => 'nullable|integer',
            'year_id' => 'nullable|integer',
            'location_id' => 'nullable|string',
            'type_id' => 'nullable|integer',
            'created_at' => 'nullable|date',
            'programming_languages' => 'nullable|array', // Les langages de programmation doivent être un tableau
            'programming_languages.*' => 'exists:programming_languages,id', // Assure que chaque ID de langage de programmation existe
        ];
    }
}
