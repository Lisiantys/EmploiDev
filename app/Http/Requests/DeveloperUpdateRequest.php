<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeveloperUpdateRequest extends FormRequest
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
            'profil_image' => 'sometimes|string|max:255',
            'first_name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'cv' => 'sometimes|string|max:255',
            'cover_letter' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
            'is_free' => 'sometimes|boolean',
            'is_validated' => 'sometimes|boolean',
            'contract_id' => 'sometimes|exists:types_contracts,id',
            'year_id' => 'sometimes|exists:years_experiences,id',
            'location_id' => 'sometimes|exists:locations,id',
            'type_id' => 'sometimes|exists:types_developers,id',
        ];
    }
}
