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
            'contract_id' => 'nullable|integer|exists:types_contracts,id',
            'year_id' => 'nullable|integer|exists:years_experiences,id',
            'location_id' => 'nullable|integer|exists:locations,id',
            'type_id' => 'nullable|integer|exists:types_developers,id', 
            'created_at' => 'nullable|date', 
            'programming_languages' => 'nullable|array', 
            'programming_languages.*' => 'exists:programming_languages,id',
        ];
    }
}
