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
            'contract_id' => 'nullable|array',
            'contract_id.*' => 'integer|exists:types_contracts,id',
            'year_id' => 'nullable|array',
            'year_id.*' => 'integer|exists:years_experiences,id',
            'location_id' => 'nullable|integer|exists:locations,id',
            'type_id' => 'nullable|integer|exists:types_developers,id',
            'programming_languages' => 'nullable|array',
            'programming_languages.*' => 'integer|exists:programming_languages,id',
        ];
    }
}
