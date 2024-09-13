<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
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
            'password' => [
                'required',
                Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
            ],
            'profil_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Image non obligatoire
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // CV obligatoire (pdf, doc, docx, max 5 Mo)
            'cover_letter' => 'required|file|mimes:pdf,doc,docx|max:5120', // Lettre de motivation obligatoire (pdf, doc, docx, max 5 Mo)
            'description' => 'required|string|max:255',
            'is_free' => 'required|boolean',
            'contract_id' => 'required|exists:types_contracts,id',
            'year_id' => 'required|exists:years_experiences,id',
            'location_id' => 'required|exists:locations,id',
            'type_id' => 'required|exists:types_developers,id',
            'programming_languages' => 'required|array', // le champ doit être un tableau
            'programming_languages.*' => 'exists:programming_languages,id', // S'assure que chaque élément du tableau existe dans la table des langages de programmation
            // Note : 'user_id' est inséré depuis le controlleur
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',

            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'password.mixedCase' => 'Le mot de passe doit contenir des lettres majuscules et minuscules.',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un symbole.',

            'profil_image.image' => 'Le fichier doit être une image.',
            'profil_image.mimes' => 'L\'image doit être de type JPG, JPEG ou PNG.',
            'profil_image.max' => 'L\'image ne doit pas dépasser 2 Mo.',

            'first_name.required' => 'Le prénom est obligatoire.',
            'first_name.string' => 'Le prénom doit être une chaîne de caractères.',
            'first_name.max' => 'Le prénom ne doit pas dépasser :max caractères.',

            'surname.required' => 'Le nom de famille est obligatoire.',
            'surname.string' => 'Le nom de famille doit être une chaîne de caractères.',
            'surname.max' => 'Le nom de famille ne doit pas dépasser :max caractères.',

            'cv.required' => 'Le CV est obligatoire.',
            'cv.mimes' => 'Le CV doit être au format PDF, DOC ou DOCX.',
            
            'cover_letter.required' => 'La lettre de motivation est obligatoire.',
            'cover_letter.mimes' => 'La lettre de motivation doit être au format PDF, DOC ou DOCX.',
   
            'description.required' => 'La description est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne doit pas dépasser :max caractères.',

            'is_free.required' => 'Le statut de disponibilité est obligatoire.',
            'is_free.boolean' => 'Le statut de disponibilité doit être vrai ou faux.',

            'contract_id.required' => 'Le type de contrat est obligatoire.',
            'contract_id.exists' => 'Le type de contrat sélectionné est invalide.',

            'year_id.required' => 'Les années d\'expérience sont obligatoires.',
            'year_id.exists' => 'L\'année d\'expérience sélectionnée est invalide.',

            'location_id.required' => 'La localisation est obligatoire.',
            'location_id.exists' => 'La localisation sélectionnée est invalide.',

            'type_id.required' => 'Le type de développeur est obligatoire.',
            'type_id.exists' => 'Le type de développeur sélectionné est invalide.',

            'programming_languages.required' => 'Les langages de programmation sont obligatoires.',
            'programming_languages.array' => 'Les langages de programmation doivent être un tableau.',
            'programming_languages.*.exists' => 'Le langage de programmation sélectionné est invalide.',
        ];
    }
}
