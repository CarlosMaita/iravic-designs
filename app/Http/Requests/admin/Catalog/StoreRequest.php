<?php

namespace App\Http\Requests\admin\Catalog;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'store_type_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre es requerido',
            'store_type_id.required' => 'El campo Tipo de Deposito es requerido',
            'store_type_id.exists' => 'El Tipo de Deposito seleccionado no es válido',
        ];
    }   
}
