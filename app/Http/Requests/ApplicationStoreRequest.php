<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationStoreRequest extends FormRequest
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
            'description' => 'required|string|min:20|max:1024',
            'job_id' => 'required|exists:job_offers,id',
            'cv' => 'sometimes|file|mimes:pdf|max:5120', 
            'cover_letter' => 'sometimes|file|mimes:pdf|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'La description est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.min' => 'La description doit contenir au moins :min caractères.',
            'description.max' => 'La description ne doit pas dépasser :max caractères.',
            'job_id.required' => 'L\'ID de l\'offre d\'emploi est obligatoire.',
            'job_id.exists' => 'L\'offre d\'emploi spécifiée n\'existe pas.',

            'cv.mimes' => 'Le CV doit être au format PDF.',
            'cover_letter.mimes' => 'La lettre de motivation doit être au format PDF.',
        ];
    }
}
