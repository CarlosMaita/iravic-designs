<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function messages()
    {
        return [
            'address.required' => 'El campo dirección es obligatorio',
            // 'contact_name',
            // 'contact_telephone',
            // 'contact_dni',
            'dni.required' => 'El campo C.I es obligatorio.',
            'dni_picture.required' => 'La foto de la C.I es obligatoria.',
            'max_credit.required' => 'El campo Crédito Máximo es obligatorio.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El campo nombre debe tener por lo menos :min caracteres.',
            // 'receipt_picture',
            'qualification.required' => 'El campo calificación del cliente es obligatorio.',
            'qualification.in' => 'El campo de calificación seleccionado no es válido.',
            'telephone.required' => 'El campo teléfono es obligatorio.',
            'zone_id.required' => 'El campo zona es obligatorio.',
            'zone_id.exists' => 'La zona seleccionada no existe en nuestra BD.'
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
            'address' => 'required',
            // 'contact_name' => '',
            // 'contact_telephone' => '',
            // 'contact_dni' => '',
            'dni' => 'required',
            // 'dni_picture.required' => '',
            'max_credit' => 'required|numeric',
            'name' => 'required|min:2',
            // 'receipt_picture' => '',
            'qualification' => ['required', Rule::in(['Bueno', 'Malo', 'Muy Malo'])],
            'telephone' => 'required',
            'zone_id' => 'required|exists:zones,id'
        ];
    }

    /**
     * 
     */
    public function withValidator($validator)
    {
        /**
         * Request should have latitude and longitude from address
         */
        if ($this->address && (!$this->latitude || !$this->longitude)) {
            $validator->after(function ($validator) {
                $validator->errors()->add('coords', 'No se ha encontrado latitud y longitud para la dirección.');
            });
        }
    }
}
