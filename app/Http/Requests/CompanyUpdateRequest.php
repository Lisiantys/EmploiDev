<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'is_validated' => 'sometimes|boolean',
            'contract_id' => 'required|exists:types_contracts,id',
            'year_id' => 'required|exists:years_experiences,id',
            'location_id' => 'required|exists:locations,id',
            'type_id' => 'required|exists:types_developers,id',
            'company_id' => 'required|exists:companies,id',
        ];
    }
}
