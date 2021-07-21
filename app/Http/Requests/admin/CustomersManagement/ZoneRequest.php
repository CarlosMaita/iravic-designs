<?php

namespace App\Http\Requests\admin\CustomersManagement;

use Illuminate\Foundation\Http\FormRequest;

class ZoneRequest extends FormRequest
{
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El campo nombre no puede tener mas de :max caracteres.'
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
            'name' => 'required|max:100'
        ];
    }
}
