<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class CollectionRequest extends FormRequest
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
            'start_date' => 'required',
            'amount_quotas' => 'required | numeric| min:1',
            'frequency' => 'required',
        ];
    }


    /**
     * 
     * Get message for validation
     * @return array
     */
    public function messages()
    {
        return [
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'amount_quotas.required' => 'La cantidad de cuotas es obligatoria.',
            'amount_quotas.min' => 'La cantidad de cuotas debe ser mayor a 0.',
            'amount_quotas.max' => 'La cantidad de cuotas debe ser menor a 30.',
            'frequency.required' => 'La frecuencia es obligatoria.',
        ];
    }
}
