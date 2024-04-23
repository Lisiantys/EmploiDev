<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
            'profil_image' => 'required|url',
            'name' => 'required|string|max:255',
            // 'user_id' est automatiquement assigné dans le controller
        ];
    }
}
