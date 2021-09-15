<?php

namespace App\Http\Requests\admin;

use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RefundRequest extends FormRequest
{
    public $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function messages()
    {
        $messages = [
            'customer_id.required'      => 'El cliente es obligatorio.',
            'customer_id.exists'        => 'El cliente seleccionado no existe en nuestra BD.',
            'payment_method.required'   => 'El método de pago es obligatorio.',
            'payment_method.in'         => 'El método de pago seleccionado no es válido.',
            'products.required'         => 'Debe seleccionar por lo menos 1 producto a llevar.',
            'products.array'            => 'Los productos a llevarse deben ser enviados en array.',
            'products.min'              => 'Debe seleccionar por lo menos 1 producto para llevarse.',
            'products_refund.required'  => 'Debe seleccionar por lo menos 1 producto a llevar.',
            'products_refund.array'     => 'Los productos a llevarse deben ser enviados en array.',
            'products_refund.min'       => 'Debe seleccionar por lo menos 1 producto para llevarse.',
            'qtys.required'             => 'Las cantidades de los productos a llevarse es requerida.',
            'qtys.array'                => 'Las cantidades de los productos a llevarse debe ser enviado en array.',
            'qtys_refund.required'      => 'Las cantidades de los productos a devolver es requerida',
            'qtys_refund.array'         => 'array'
        ];

        if (isset($this->products) && is_array($this->products)) {
            foreach ($this->products as $key => $product) {
                $messages['products.'. $product . '.exists'] = 'El producto ' . ($key + 1) . ' a llevarse no se encuentra en la BD.';

                if (isset($this->qtys[$product])) {
                    $messages['qtys.' . $product . '.numeric'] = 'La cantidad para el producto ' . ($key + 1) . ' a llevarse no es válida.';
                }
            }
        }

        if (isset($this->products_refund) && is_array($this->products_refund)) {
            foreach ($this->products_refund as $key => $product) {
                $messages['products_refund.'. $product . '.exists'] = 'El producto ' . ($key + 1) . ' a devolver no se encuentra en la BD.';

                if (isset($this->qtys[$product])) {
                    $messages['qtys_refund.' . $product . '.numeric'] = 'La cantidad para el producto ' . ($key + 1) . ' a devolver no es válida.';
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
        $rules = [
            'customer_id' => 'required|exists:customers,id',
            'products_refund' => 'required|array|min:1',
            'products_refund.*' => 'exists:orders_products,id',
            'qtys_refund' => 'required|array',
            'qtys_refund.*' => 'numeric'
        ];

        if (!empty($this->products)) {
            $rules['payment_method'] = ['required', Rule::in(['bankwire', 'card', 'cash', 'credit'])];
            $rules['products'] = 'required|array|min:1';
            $rules['products.*'] = 'exists:products,id';
            $rules['qtys'] = 'required|array';
            $rules['qtys.*'] = 'numeric';

        }

        return $rules;
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

        if (!$validator->fails()) {
            $this->merge([
                'date' => now()
            ]);

            if (!empty($this->products)) {
                $totals = $this->getTotal();

                $this->merge([
                    'payed_bankwire'    => isset($this->payment_method) && $this->payment_method == 'bankwire' ? 1 : 0,
                    'payed_card'        => isset($this->payment_method) && $this->payment_method == 'card' ? 1 : 0,
                    'payed_cash'        => isset($this->payment_method) && $this->payment_method == 'cash' ? 1 : 0,
                    'payed_credit'      => isset($this->payment_method) && $this->payment_method == 'credit' ? 1 : 0,
                    'discount'          => $totals['discount'],
                    'subtotal'          => $totals['subtotal'],
                    'total'             => $totals['total']
                ]);
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
        $box = $user->boxes()->where('closed', 0)->first();

        $this->merge([
            'box_id'            => $box ? $box->id : null,
            'stock_type'        => $user->getColumnStock(),
            'user_id'           => $user->id
        ]);
    }

    /**
     * 
     */
    public function getTotal()
    {
        $discount = isset($this->discount) && is_numeric($this->discount) ? $this->discount : 0;
        $subtotal = 0;

        foreach ($this->products as $product_id) {
            if ($product = $this->productRepository->find($product_id)) {
                if (isset($this->qtys[$product_id]) && $this->qtys[$product_id] > 0) {
                    $subtotal += ($product->regular_price * $this->qtys[$product_id]);
                }
            }
        }

        return [
            'discount' => $discount,
            'subtotal' => $subtotal,
            'total' => $subtotal - $discount
        ];
    }
}
