<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeCtaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'cta_text' => 'required|string|max:255',
            'cta_url' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'cta_text.required' => 'El texto del CTA es obligatorio.',
            'cta_url.required' => 'La URL del CTA es obligatoria.',
        ];
    }
}
