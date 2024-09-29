<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
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
            'password' => [
                'sometimes',
                Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
            ],
            'first_name' => 'required|string|min:2|max:30',
            'surname' => 'required|string|min:2|max:30',
            'cv' => 'sometimes|file|mimes:pdf|max:5120', // CV obligatoire (pdf, doc, docx, max 5 Mo)
            'cover_letter' => 'sometimes|file|mimes:pdf|max:5120', // Lettre de motivation obligatoire (pdf, doc, docx, max 5 Mo)
            'description' => 'nullable|string|min:10|max:255',
            'is_free' => 'sometimes|boolean',
            'contract_id' => 'sometimes|exists:types_contracts,id',
            'year_id' => 'sometimes|exists:years_experiences,id',
            'location_id' => 'sometimes|exists:locations,id',
            'type_id' => 'sometimes|exists:types_developers,id',
            'programming_languages' => 'sometimes|array', // le champ doit être un tableau
            'programming_languages.*' => 'exists:programming_languages,id', // S'assure que chaque élément du tableau existe dans la table des langages de programmation
            // Note: 'user_id' is not included since it's unlikely to change in an update
        ];

        // Validation conditionnelle pour l'image de profil
        if ($this->hasFile('profil_image')) {
            $rules['profil_image'] = 'image|mimes:jpg,png|max:2048';
        }
    }

    public function messages()
    {
        return [
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',

            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'password.mixedCase' => 'Le mot de passe doit contenir des lettres majuscules et minuscules.',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un symbole.',

            'profil_image.image' => 'Le fichier doit être une image.',
            'profil_image.mimes' => 'L\'image doit être de type JPG ou PNG.',
            'profil_image.max' => 'L\'image ne doit pas dépasser 2 Mo.',

            'first_name.string' => 'Le prénom doit être une chaîne de caractères.',
            'first_name.min' => 'Le prénom doit contenir au moins :min caractères.',
            'first_name.max' => 'Le prénom ne doit pas dépasser :max caractères.',

            'surname.string' => 'Le nom de famille doit être une chaîne de caractères.',
            'surname.min' => 'Le nom de famille doit contenir au moins :min caractères.',
            'surname.max' => 'Le nom de famille ne doit pas dépasser :max caractères.',

            'cv.file' => 'Le CV doit être un fichier.',
            'cv.mimes' => 'Le CV doit être au format PDF.',
            'cv.max' => 'Le CV ne doit pas dépasser 5 Mo.',

            'cover_letter.file' => 'La lettre de motivation doit être un fichier.',
            'cover_letter.mimes' => 'La lettre de motivation doit être au format PDF.',
            'cover_letter.max' => 'La lettre de motivation ne doit pas dépasser 5 Mo.',

            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.min' => 'La description doit contenir au moins :min caractères.',
            'description.max' => 'La description ne doit pas dépasser :max caractères.',

            'is_free.boolean' => 'Le statut de disponibilité doit être vrai ou faux.',

            'is_validated.boolean' => 'Le statut de validation doit être vrai ou faux.',

            'contract_id.exists' => 'Le type de contrat sélectionné est invalide.',

            'year_id.exists' => 'L\'année d\'expérience sélectionnée est invalide.',

            'location_id.exists' => 'La localisation sélectionnée est invalide.',

            'type_id.exists' => 'Le type de développeur sélectionné est invalide.',

            'programming_languages.array' => 'Les langages de programmation doivent être un tableau.',
            'programming_languages.*.exists' => 'Le langage de programmation sélectionné est invalide.',
        ];
    }
}
