<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
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
            'email' => 'sometimes|email|unique:users,email,' . $this->company->user_id,
            'password' => [
                'sometimes',
                Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
            ],
            'profil_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Image non obligatoire
            'name' => 'sometimes|string|min:2|max:50',
            'description' => 'nullable|string|max:255',
            // 'user_id' est automatiquement assigné dans le controller
        ];
    }

    public function messages()
    {
        return [
            'email.sometimes' => 'L\'adresse email est obligatoire si fournie.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',

            'password.sometimes' => 'Le mot de passe est obligatoire si fourni.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'password.mixedCase' => 'Le mot de passe doit contenir des lettres majuscules et minuscules.',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un symbole.',

            'profil_image.image' => 'Le fichier doit être une image.',
            'profil_image.mimes' => 'L\'image doit être de type JPG, JPEG ou PNG.',
            'profil_image.max' => 'L\'image ne doit pas dépasser 2 Mo.',

            'name.sometimes' => 'Le nom de l\'entreprise est obligatoire si fourni.',
            'name.string' => 'Le nom de l\'entreprise doit être une chaîne de caractères.',
            'name.min' => 'Le nom de l\'entreprise doit contenir au moins :min caractères.',
            'name.max' => 'Le nom de l\'entreprise ne doit pas dépasser :max caractères.',

            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.min' => 'La description doit contenir au moins :min caractères.',
            'description.max' => 'La description ne doit pas dépasser :max caractères.',
        ];
    }
}
