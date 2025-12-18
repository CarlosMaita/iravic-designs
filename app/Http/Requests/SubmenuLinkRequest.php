<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmenuLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'active' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'título',
            'url' => 'URL',
            'order' => 'orden',
            'active' => 'activo',
        ];
    }
}

