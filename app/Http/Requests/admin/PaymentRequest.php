<?php

namespace App\Http\Requests\admin;

use App\Models\Customer;
use App\Repositories\Eloquent\BoxRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public $boxRepository;

    public function __construct(BoxRepository $boxRepository)
    {
        $this->boxRepository = $boxRepository;
    }

    public function messages()
    {
        return [
            'amount.required' => 'El campo monto es obligatorio.',
            'amount.min' => 'El monto debe ser mayor a :min',
            'amount.numeric' => 'El monto debe ser un valor numérico.',
            'customer_id.required' => 'El cliente es obligatorio.',
            'customer_id.exists' => 'El cliente seleccionado no existe en nuestra BD.',
            'payment_method.required' => 'El método de pago es obligatorio.',
            'payment_method.in' => 'El método de pago seleccionado no es válido.',
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
            'amount' => 'required|numeric|min:1'
        ];
    }

    /**
     * 
     */
    public function withValidator($validator)
    {
        
        if (isset($this->customer_id) && isset($this->amount) && $customer = Customer::find($this->customer_id)) {
            if ($this->isMethod('POST') && $this->amount > $customer->getTotalDebt()) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('debt', 'El monto registrado es mayor a la deuda actual del cliente.');
                });
            } else if ($this->isMethod('PUT')) {
                $payment = $this->route('pago');
                $debt = $customer->getTotalDebt();
                $new_debt =  ($debt + $payment->amount);

                if ($this->amount > $new_debt) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('debt', 'El monto registrado es mayor a la deuda actual del cliente.');
                    });
                }
            }
        }

        if ($this->isMethod('POST')) {
            if (!$this->box_id) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('box', 'No posee una caja abierta en la cual se puedan registrar pagos.');
                });
            }
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
        $box = $this->boxRepository->getOpenByUserId($user->id);

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
