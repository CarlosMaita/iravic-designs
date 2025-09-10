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
            'dni.required' => 'El campo C.I es obligatorio.',
            'dni.regex' => 'El campo C.I debe contener solo números y puede incluir puntos y guiones.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El campo nombre debe tener por lo menos :min caracteres.',
            'email.email' => 'El campo de correo electrónico debe ser tipo email. Ej:email@example.com.',
            'qualification.required' => 'El campo calificación del cliente es obligatorio.',
            'qualification.in' => 'El campo de calificación seleccionado no es válido.',
            'cellphone.max' => 'El campo teléfono celular debe ser menor a :max caracteres.',
            'cellphone.regex' => 'Ingresa un número de teléfono celular válido. Solo se permiten números, el signo + al inicio y espacios.',
            'shipping_agency.max' => 'El campo agencia de envío debe ser menor a :max caracteres.',
            'shipping_agency_address.max' => 'El campo dirección de agencia debe ser menor a :max caracteres.',
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