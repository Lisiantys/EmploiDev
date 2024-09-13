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
            'email' => 'sometimes|email|unique:users,email,' . $this->developer->user_id, // Permet la mise à jour, mais garde la validation d'unicité sauf pour cet utilisateur
            'password' => [
                'sometimes',
                Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
            ],
            'profil_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Image non obligatoire
            'name' => 'sometimes|string|max:50',
            'description' => 'required|string|max:255',
            // 'user_id' est automatiquement assigné dans le controller
        ];
    }
}
