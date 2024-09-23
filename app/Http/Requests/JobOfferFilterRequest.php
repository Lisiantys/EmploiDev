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
            'contract_id' => 'sometimes|exists:types_contracts,id',
            'year_id' => 'sometimes|exists:years_experiences,id',
            'location_id' => 'sometimes|exists:locations,id',
            'type_id' => 'sometimes|exists:types_developers,id',
            'programming_languages' => 'sometimes|array',
            'programming_languages.*' => 'exists:programming_languages,id',
        ];
    }
}
