<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ZoneRequest extends FormRequest
{
    public function messages()
    {
        return [
            'address_destination.required' => 'El campo dirección destino es obligatorio.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El campo nombre no puede tener mas de :max caracteres.',
            'name.unique' => 'Ya existe una zona con el nombre ingresado.',
            'position.max' => 'El campo de posición debe tener máximo :max caracteres.'
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
        $rules = [
            'address_destination' => 'required',
            'position'            => 'max:9'
        ];

        if ($this->isMethod('POST')) {
            $rules['name'] = 'required|max:100|unique:zones,name,NULL,id,deleted_at,NULL';
        } else {
            $rules['name'] = [
                'required',
                'max:100',
                Rule::unique('zones', 'name')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })->ignore($this->route('zona')->id),
            ];
        }

        return $rules;
    }

    /**
     * 
     */
    public function withValidator($validator)
    {
        /**
         * Request should have latitude and longitude from address
         */
        if ($this->address_destination && (!$this->latitude_destination || !$this->longitude_destination)) {
            $validator->after(function ($validator) {
                $validator->errors()->add('coords', 'No se ha encontrado latitud y longitud para la dirección destino.');
            });
        }
    }
}
