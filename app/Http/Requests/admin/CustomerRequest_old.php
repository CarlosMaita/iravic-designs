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
            'dni.required' => 'El campo C.I es obligatorio.',
            'dni.min' => 'El campo C.I debe ser mayor a :min caracteres',
            'dni.max' => 'El campo C.I debe ser menor a :max caracteres',
            'dni.regex' => 'El campo C.I debe contener solo números y puede incluir puntos y guiones.',
            'contact_dni.min' => 'El campo C.I de contacto debe ser mayor a :min caracteres',
            'contact_dni.max' => 'El campo C.I de contacto debe ser menor a :max caracteres',
            'contact_dni.regex' => 'El campo C.I de contacto debe contener solo números y puede incluir puntos y guiones.',
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
            'telephone.regex' => 'Ingresa un número de teléfono válido. Solo se permiten números, el signo + al inicio y espacios.',
            'cellphone.max' => 'El campo teléfono celular debe ser menor a :max caracteres.',
            'cellphone.regex' => 'Ingresa un número de teléfono celular válido. Solo se permiten números, el signo + al inicio y espacios.',
            'contact_telephone.max' => 'El campo teléfono de contacto debe ser menor a :max caracteres.',
            'contact_telephone.regex' => 'Ingresa un número de teléfono de contacto válido. Solo se permiten números, el signo + al inicio y espacios.',
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
            'collection_day.required' => 'El campo día de cobro es obligatorio.',
            'collection_frequency.required' => 'El campo frecuencia de cobro es obligatorio.',
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
            'dni' => 'required|regex:/^[0-9.-]+$/u|min:3|max:30',
            'cellphone' => 'regex:/^\+?\d[\d\s]+$/u|max:20|nullable',
            'qualification' => ['required', Rule::in(CustomerConstants::QUALIFICATIONS)],
            'shipping_agency' => 'nullable|string|max:255',
            'shipping_agency_address' => 'nullable|string|max:500',
        ];

        if ($this->isMethod('POST')) {
            $rules['email'] = 'sometimes|email|unique:customers,email,NULL,id|nullable';
        } else {
            $rules['email'] = [
                'sometimes',
                'email',
                'nullable',
                Rule::unique('customers', 'email')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })->ignore($this->route('cliente')->id),
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
        }
    }
}
