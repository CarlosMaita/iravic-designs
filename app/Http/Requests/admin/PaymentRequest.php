<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function messages()
    {
        return [
            'customer_id.required' => 'El cliente es obligatorio.',
            'customer_id.exists' => 'El cliente seleccionado no existe en nuestra BD.',
            'payment_method.required' => 'El método de pago es obligatorio.',
            'payment_method.in' => 'El método de pago seleccionado no es válido.',
            'products.required' => 'Debe seleccionar por lo menos 1 producto.',
            'products.array' => 'Los productos deben ser enviados en array.',
            'products.min' => 'Debe seleccionar por lo menos 1 producto.',
            'qtys.required' => 'required',
            'qtys.array' => 'array'
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
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => ['required', Rule::in(['bankwire', 'card', 'cash'])],
            'amount' => 'required|array|min:1'
        ];
    }

    /**
     * 
     */
    public function withValidator($validator)
    {
        if (!$this->box_id) {
            $validator->after(function ($validator) {
                $validator->errors()->add('box', 'El usuario no tiene una caja abierta en este momento.');
            });
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $user = Auth::user();
        $box = $user->boxes()->where('closed', 0)->first();

        $this->merge([
            'box_id'            => $box ? $box->id : null,
            'date'              => now(),
            'payed_bankwire'    => isset($this->payment_method) && $this->payment_method == 'bankwire' ? 1 : 0,
            'payed_card'        => isset($this->payment_method) && $this->payment_method == 'card' ? 1 : 0, 
            'payed_cash'        => isset($this->payment_method) && $this->payment_method == 'cash' ? 1 : 0,
            'user_id'           => $user->id
        ]);
    }
}
