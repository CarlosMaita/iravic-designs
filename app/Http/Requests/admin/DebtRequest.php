<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DebtRequest extends FormRequest
{

    public function messages()
    {
        return [
            'amount.required' => 'El campo monto es obligatorio.',
            'amount.min' => 'El monto debe ser mayor a :min',
            'amount.numeric' => 'El monto debe ser un valor numérico.',
            'customer_id.required' => 'El cliente es obligatorio.',
            'customer_id.exists' => 'El cliente seleccionado no existe en nuestra BD.'
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
            'amount' => 'required|numeric|min:1',
            'customer_id' => 'required|exists:customers,id'
        ];
    }

    public function withValidator($validator)
    {
        // Box validation removed - debts can be created without box restrictions
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $user = Auth::user();

        $this->merge([
            'date'      => now(),
            'user_id'   => $user->id
        ]);
    }
}
