<?php

namespace App\Http\Requests\admin;

use App\Models\Customer;
use App\Repositories\Eloquent\ProductRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            foreach ($this->products as $keyProduct => $product) {
                $messages['products.'. $keyProduct . '.exists'] = 'El producto ' . ($keyProduct + 1) . ' no se encuentra en la BD.';

                if (isset($this->qtys[$keyProduct]) && is_array($this->qtys[$keyProduct])) {
                    foreach ($this->qtys[$keyProduct] as $keyStore => $qty) {
                        $messages['qtys.' . $keyProduct . '.' . $keyStore . '.numeric'] = 'La cantidad para el producto ' . ($keyProduct + 1) . ', deposito ' . ($keyStore + 1) . ' no es válida.';
                    }
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
            'payment_method' => ['required', Rule::in(['bankwire', 'card', 'cash', 'credit'])],
            'products' => 'required|array|min:1',
            // 'products.*' => 'exists:products,id',
            'qtys' => 'required|array',
            // 'qtys.*' => 'numeric'
        ];

        // if (isset($this->products) && is_array($this->products)) {
        //     foreach ($this->products as $keyProduct => $product) {
        //         $rules['products.'. $keyProduct . '.exists'] = ['exists:products,id'];
        //         // $rules['qtys.'. $keyProduct . '.numeric'] = ['numeric'];
        //     }
        // }

        return $rules;


    }

    /**
     * 
     */
    public function withValidator($validator)
    {
        /**
         * Para realizar una venta, el usuario debe tener una caja abierta
         */
        if (!$this->box_id) {
            $validator->after(function ($validator) {
                $validator->errors()->add('box', 'El usuario no tiene una caja abierta en este momento.');
            });
        }

        /** 
         * Si en el formulario de la venta, se habilita pautar una visita, debe seleccionar la fecha
         */
        if (!empty($this->enable_new_visit)) {
            if (!$this->visit_date) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('visit_date', 'Debe seleccionar la fecha de la visita.');
                });
            }
        }

        /***
         * Se debe validar, si la venta es a credito, que el monto no supere el saldo disponible en credito
         */
        if ( $this->payment_method == "credit")
        {
            $validator->after(function ($validator) {
                $customer = Customer::find($this->customer_id);
                $balance = $customer->getBalance();
                $total_sale_with_discount = $this->getTotal()['total'];
                $max_credit = $customer->max_credit ; 
                $available_credit = $max_credit + $balance >= 0 ? $max_credit + $balance : 0;


                if (!$this->canBuyOnCredit($balance, $total_sale_with_discount , $max_credit)){
                    $validator->errors()->add('payment_method', 'El usuario no cuenta con credito disponible para hacer la venta.
                     Su compra debe ser menor o igual a $'. number_format($available_credit, 2, '.', ',') );
                }

            });
        }

        if (!$validator->fails()) {
            $totals = $this->getTotal();

            $this->merge([
                'date'                  => now(),
                'payed_bankwire'        => isset($this->payment_method) && $this->payment_method == 'bankwire' ? 1 : 0,
                'payed_card'            => isset($this->payment_method) && $this->payment_method == 'card' ? 1 : 0, 
                'payed_cash'            => isset($this->payment_method) && $this->payment_method == 'cash' ? 1 : 0,
                'payed_credit'          => isset($this->payment_method) && $this->payment_method == 'credit' ? 1 : 0,
                'subtotal'              => $totals['subtotal'],
                'discount'              => $totals['discount'],
                'total'                 => $totals['total'],
                'total_to_collection'   => $totals['total_to_collection'],
            ]);
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
        $box = $user->boxes()->where('closed', 0)->first(); // Se busca la caja abierta del usuario
        $visit_date = $this->isMethod('POST') && !empty($this->visit_date) ? 
                    Carbon::createFromFormat('d-m-Y', $this->visit_date) : null; // Se puede pautar una visita al realizar una venta

        $this->merge([
            'box_id'            => $box ? $box->id : null,
            'stock_type'        => $user->getColumnStock(), // La cantidades de productos se descontaran de este stock
            'user_id'           => $user->id,
            'visit_date'        => $visit_date ? $visit_date->format('Y-m-d') : null
        ]);
    }

    /**
     * Se calculan los totales (descuento incluido)
     * @return Array
     */
    public function getTotal()
    {
        $discount = isset($this->discount) && is_numeric($this->discount) ? $this->discount : 0;
        $subtotal = 0;

        foreach ($this->products as $keyProduct => $stores) {
            if ($product = $this->productRepository->find($keyProduct)) {
                if (isset($this->qtys[$keyProduct])) {
                    foreach ($this->qtys[$keyProduct] as $keyStore => $qty) {
                        if ($qty <= 0) {
                            continue;
                        }

                        $real_price =  $product->regular_price; // Precio regular por defecto
                        if(auth()->user()->can('prices-per-method-payment') ) {
                            if ($this->payment_method == "card" || $this->payment_method == "credit") {
                                $real_price = $this->payment_method == "card" ?  $product->regular_price_card_credit : $product->regular_price_credit;
                            }
                        }
                        $subtotal += ($real_price * $qty);
                    }
                }
            }
        }
        $total_to_pay = $subtotal - $discount; // El total con descuento
        $total_to_collection = $this->getTotalToCollection($total_to_pay); // El total que se le cobrara al cliente es por default el total
        
        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total_to_pay,
            'total_to_collection' => $total_to_collection
        ];
    }


    /**
     * Calcula el total a cobrar al cliente, teniendo en cuenta si el metodo de pago es credito.
     * Si el metodo de pago es credito y el cliente tiene un balance positivo, se le restara el balance al total a cobrar.
     * @param int $total_to_pay El total a cobrar sin tener en cuenta el balance del cliente.
     * @return int El total a cobrar al cliente.
     */
    private function getTotalToCollection($total_to_pay)
    {
        if ($this->payment_method != "credit") {
            return $total_to_pay; 
        }
        // si es credito, el total a cobrar sera el total mas el balance positivo
        $balance = Customer::find($this->customer_id)->getBalance();
        if ( $balance <= 0 ) {
            return $total_to_pay;
        }
        
        $total_to_collection = max(0,  $total_to_pay - $balance);
        return $total_to_collection;
    }

     /**
     * Se valida si el usuario puede hacer una compra a credito
     * @return bool
     */
    public function canBuyOnCredit($balance, $total_sale_with_discount , $max_credit)
    {
        $result = $max_credit + $balance - $total_sale_with_discount ;
        return $result >= 0 ? true : false;
    }


}
