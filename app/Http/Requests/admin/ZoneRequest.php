<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class ZoneRequest extends FormRequest
{
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El campo nombre no puede tener mas de :max caracteres.',
            'name.unique' => 'Ya existe una zona con el nombre ingresado.'
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
        $rules = array();

        if ($this->isMethod('POST')) {
            $rules['name'] = 'required|max:100|unique:zones,name,NULL,id,deleted_at,NULL';
        } else {
            $rules['name'] = 'required|max:100|unique:zones,name,' . $this->route('zona')->id;
        }

        return $rules;
    }
}
