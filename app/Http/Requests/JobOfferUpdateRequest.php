<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobOfferUpdateRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'is_validated' => 'sometimes|boolean',
            'contract_id' => 'sometimes|exists:types_contracts,id',
            'year_id' => 'sometimes|exists:years_experiences,id',
            'location_id' => 'sometimes|exists:locations,id',
            'type_id' => 'sometimes|exists:types_developers,id',
            // 'company_id' is not included since it's unlikely to change in an update
        ];
    }
}
