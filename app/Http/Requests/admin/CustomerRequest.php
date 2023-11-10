<?php

namespace App\Http\Requests\admin;

use App\Constants\CustomerConstants;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    /**
     * 
     */
    public function messages()
    {
        return [
            'address.required' => 'El campo dirección es obligatorio.',
            'contact_name.required' => 'El campo Nombre de contacto es obligatorio.',
            'contact_telephone.required' => 'El campo teléfono de contacto es obligatorio.',
            'contact_dni.required' => 'El campo C.I de contacto es obligatorio.',
            'days_to_notify_debt.required' => 'La cantidad de días para ser moroso es obligatorio.',
            'days_to_notify_debt.integer' => 'La cantidad de días para ser moroso debe ser un número entero.',
            'days_to_notify_debt.min' => 'La cantidad mínima de días para ser moroso es de :min .',
            'days_to_notify_debt.max' => 'La cantidad máxima de días para ser moroso es de :max .',
            'dni.required' => 'El campo C.I es obligatorio.',
            'dni.min' => 'El campo C.I debe ser mayor a :min caracteres',
            'dni.max' => 'El campo C.I debe ser menor a :max caracteres',
            'contact_dni.min' => 'El campo C.I de contacto debe ser mayor a :min caracteres',
            'contact_dni.max' => 'El campo C.I de contacto debe ser menor a :max caracteres',
            'dni_picture.required' => 'La foto de la C.I es obligatoria.',
            'max_credit.required' => 'El campo Crédito Máximo es obligatorio.',
            'max_credit.min' => 'La cantidad mínima de Crédito Máximo es de :min ',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El campo nombre debe tener por lo menos :min caracteres.',
            'email.email' => 'El campo de correo electrónico debe ser tipo email. Ej:email@axample.com.',
            'receipt_picture.required' => 'La foto del recibo de sueldo es obligatoria.',
            'qualification.required' => 'El campo calificación del cliente es obligatorio.',
            'qualification.in' => 'El campo de calificación seleccionado no es válido.',
            'telephone.required' => 'El campo teléfono es obligatorio.',
            'telephone.max' => 'El campo teléfono debe ser menor a :max caracteres.',
            'cellphone.max' => 'El campo teléfono celular debe ser menor a :max caracteres.',
            'contact_telephone.max' => 'El campo teléfono de contacto debe ser menor a :max caracteres.',
            'zone_id.required' => 'El campo zona es obligatorio.',
            'zone_id.exists' => 'La zona seleccionada no existe en nuestra BD.',
            'dni_picture.image' => 'La foto de la C.I debe ser una imagen.',
            'receipt_picture.image' => 'La foto del recibo debe ser una imagen.',
            'card_front.image' => 'La foto del frente de la tarjeta  debe ser una imagen.',
            'card_back.image' => 'La foto del dorso de la tarjeta debe ser una imagen.',
            'address_picture.image' => 'La foto del frente de la casa debe ser una imagen.',
            'dni_picture.mimes' => 'La foto de la C.I  debe ser de tipo jpeg, jpg, png o webp.',
            'receipt_picture.mimes' => 'La foto del recibo debe debe ser de tipo jpeg, jpg, png o webp.',
            'card_front.mimes' => 'la foto del frente de la tarjeta  debe debe ser de tipo jpeg, jpg, png o webp.',
            'card_back.mimes' => 'La foto del dorso de la tarjeta debe debe ser de tipo jpeg, jpg, png o webp.',
            'address_picture.mimes' => 'La foto del frente de la casa debe debe ser de tipo jpeg, jpg, png o webp.',
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
            'name' => 'required|min:2',
            'email' => 'email|nullable',
            'dni' => 'required|min:3|max:30',
            'contact_dni' => 'min:3|max:30|nullable',
            'telephone' => 'max:20',
            'cellphone' => 'max:20',
            'contact_telephone' => 'max:20',
            'address' => 'required',
            'contact_name' => 'required',
            'max_credit' => 'required|numeric|min:0',
            'days_to_notify_debt' => 'required|integer|min:0|max:500',
            'qualification' => ['required', Rule::in(CustomerConstants::QUALIFICATIONS)],
            'zone_id' => 'required|exists:zones,id',
        ];
        #validacion de imagenes 
        $rules['dni_picture'] = 'mimetypes:image/jpeg,image/jpg,image/png,image/webp';
        $rules['receipt_picture'] = 'mimetypes:image/jpeg,image/jpg,image/png,image/webp';
        $rules['card_front'] = 'mimetypes:image/jpeg,image/jpg,image/png,image/webp';
        $rules['card_back'] = 'mimetypes:image/jpeg,image/jpg,image/png,image/webp';
        $rules['address_picture'] = 'mimetypes:image/jpeg,image/jpg,image/png,image/webp';

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
        if ($this->address && (!$this->latitude || !$this->longitude)) {
            $validator->after(function ($validator) {
                $validator->errors()->add('coords', 'No se ha encontrado latitud y longitud para la dirección.');
            });
        }

        /**
         * Custom Unique rule for DNI
         */
        if ($this->dni) {
            $customer = $this->route('cliente');
            $existing_customer = Customer::where('dni', $this->dni)->first();

            if ($existing_customer && (!$customer || $customer->id != $existing_customer->id)) {
                $validator->after(function ($validator) use($existing_customer) {
                    $validator->errors()->add('dni_unique', 'El DNI ingresado se encuentra registrado para el cliente ' . $existing_customer->name);
                });
            }

            if ($this->dni == $this->contact_dni) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('dni_unique', 'El DNI del cliente tiene que ser diferente al DNI de la persona de contacto.');
                });
            } else if (!isset($this->confirm) && $existing_customer = Customer::where('contact_dni', $this->dni)->first()) {
                $validator->after(function ($validator) use ($existing_customer) {
                    $validator->errors()->add('dni_contact_used', 'El DNI ingresado se encuentra registrado como DNI de contacto para el cliente ' . $existing_customer->name . ' (' . $existing_customer->qualification . ')');
                });
            }
        }

        /** 
         * Telephone can't be equal to contact telephone 
        */
        if ($this->telephone == $this->contact_telephone && !empty($this->telephone) && !empty($this->contact_telephone) ) {
            $validator->after(function ($validator) {
                $validator->errors()->add('dni_unique', 'El teléfono del cliente tiene que ser diferente al teléfono de la persona de contacto.');
            });
        }

        /**
         * There should be a contact name if contact telephone o contact dni is present
         */
        /*
        if (!$this->contact_name && ($this->contact_telephone || $this->contact_dni)) {
            $validator->after(function ($validator) {
                $validator->errors()->add('contact_info', 'Debe ingresar el nombre de la persona de contacto');
            });
        }
        */
    }
}
