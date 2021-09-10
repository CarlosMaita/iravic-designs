<?php

namespace App\Http\Requests\admin;

use App\Models\Config;
use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Foundation\Http\FormRequest;

class OrderDiscountRequest extends FormRequest
{
    public $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function messages()
    {
        $messages = [
            'discount.required' => 'El monto del descuento es obligatorio.',
            'discount.numeric' => 'El monto del descuento debe ser un valor numérico.',
            'discount.min' => 'El descuento no puede ser menor que :min.',
            'discount_password' => 'La contraseña para descuentos es obligatoria.',
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
            'discount' => 'required|numeric|min:0',
            'discount_password' => 'required',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'qtys' => 'required|array',
            'qtys.*' => 'numeric'
        ];
    }

    /**
     * 
     */
    public function withValidator($validator)
    {
        if (!empty($this->discount_password)) {
            $discount_password = Config::getConfig('discount_password');

            if ($discount_password->value && $discount_password->value != $this->discount_password) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('discount_password', 'La contraseña para descuentos es incorrecta.');
                });
            } else if (!$discount_password->value) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('discount_password', 'Los descuentos no estan habilitados.');
                });
            }
        }
    }
}
