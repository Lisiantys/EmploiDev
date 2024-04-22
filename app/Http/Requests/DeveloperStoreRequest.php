<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeveloperStoreRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email', //user
            'password' => 'required|string|min:8',//user ajouter criteres
            'profil_image' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'cv' => 'required|string|max:255',
            'cover_letter' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'is_free' => 'required|boolean',
            'is_validated' => 'required|boolean',
            'contract_id' => 'required|exists:types_contracts,id',
            'year_id' => 'required|exists:years_experiences,id',
            'location_id' => 'required|exists:locations,id',
            'type_id' => 'required|exists:types_developers,id',
            'programming_languages' => 'required|array', // le champ doit être un tableau
            'programming_languages.*' => 'exists:programming_languages,id', // S'assure que chaque élément du tableau existe dans la table des langages de programmation
            // Note : 'user_id' est inséré depuis le controlleur
        ];
    }
}
