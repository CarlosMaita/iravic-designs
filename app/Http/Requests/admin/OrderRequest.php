<?php

namespace App\Http\Requests\admin;

use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Product;

class OrderRequest extends FormRequest
{
    public $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

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
            'payed_credit'      => isset($this->payment_method) && $this->payment_method == 'credit' ? 1 : 0,
            'stock_type'        => $user->getColumnStock(),
            'total'             => $this->getTotal(),
            'user_id'           => Auth::user()->id
        ]);
    }

    /**
     * 
     */
    public function getTotal()
    {
        $total = 0;

        foreach ($this->products as $product_id) {
            if ($product = $this->productRepository->find($product_id)) {
                if (isset($this->qtys[$product_id]) && $this->qtys[$product_id] > 0) {
                    $total += ($product->regular_price * $this->qtys[$product_id]);
                }
            }
        }

        return $total;
    }
}
