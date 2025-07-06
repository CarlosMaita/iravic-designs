<?php

namespace App\Http\Requests\admin\Catalog;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.unique' => 'El campo nombre ya se encuentra ocupado por otra categoría',
            'name.max' => 'El campo nombre no puede tener mas de :max caracteres.',
            'base_category_id.required' => 'El campo categoría base es obligatorio.',
        ];
    }

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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'base_category_id' => 'required|exists:base_categories,id',
            'image_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bg_banner' => 'nullable|string|max:100',
        ];
    }
}
