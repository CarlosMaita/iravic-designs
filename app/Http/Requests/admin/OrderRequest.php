<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Product;

class OrderRequest extends FormRequest
{
    public function messages()
    {
        $messages = [
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

        if (isset($this->products) && is_array($this->products)) {
            foreach ($this->products as $key => $product) {
                $messages['products.'. $product . '.exists'] = 'El producto ' . ($key + 1) . ' no se encuentra en la BD.';


                if (isset($this->qtys[$product])) {
                    $messages['qtys.' . $product . '.numeric'] = 'La cantidad para el producto ' . ($key + 1) . ' no es válida.';
                }
            }
        }

        return $messages;
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
            'payment_method' => ['required', Rule::in(['bankwire', 'card', 'cash', 'credit'])],
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'qtys' => 'required|array',
            'qtys.*' => 'numeric'
        ];
    }
}
