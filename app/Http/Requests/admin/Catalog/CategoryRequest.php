<?php

namespace App\Http\Requests\admin\Catalog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.unique' => 'El campo nombre ya se encuentra ocupado por otra categoría',
            'name.max' => 'El campo nombre no puede tener mas de :max caracteres.',
            'base_category_id.required' => 'El campo categoría base es obligatorio.',
            'slug.unique' => 'El valor del campo slug ya está en uso.',
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
        $categoryId = $this->route('categoria') ? $this->route('categoria')->id : null;
        $rules = [
            'name' => 'required|max:100',
            'base_category_id' => 'required|exists:base_categories,id',
            'image_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bg_banner' => 'nullable|string|max:100',
            'slug' => [
                'nullable',
                'min:3',
                'max:191',
                'alpha_dash',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
        ];

        return $rules;
    }
}
