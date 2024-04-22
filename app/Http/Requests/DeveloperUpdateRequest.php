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
            'email' => 'sometimes|email|unique:users,email,' . $this->developer->user_id, // Permet la mise à jour, mais garde la validation d'unicité sauf pour cet utilisateur
            'password' => 'sometimes|string|min:8', // La mise à jour du mot de passe est facultative
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
            'programming_languages' => 'sometimes|array', // le champ doit être un tableau
            'programming_languages.*' => 'exists:programming_languages,id', // S'assure que chaque élément du tableau existe dans la table des langages de programmation
            // Note: 'user_id' is not included since it's unlikely to change in an update
        ];
    }
}
