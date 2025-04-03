<?php

namespace App\Http\Requests\admin;

use App\Models\Customer;
use App\Repositories\Eloquent\OrderProductRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Services\Orders\OrderService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RefundRequest extends FormRequest
{
    public $productRepository;
    public $orderProductRepository; 

    public function __construct(ProductRepository $productRepository, OrderProductRepository $orderProductRepository)
    {
        $this->productRepository = $productRepository;
        $this->orderProductRepository = $orderProductRepository;
    }

    public function messages()
    {
        $messages = [
            'customer_id.required'              => 'El cliente es obligatorio.',
            'customer_id.exists'                => 'El cliente seleccionado no existe en nuestra BD.',
            'payment_method.required'           => 'El método de pago es obligatorio.',
            'payment_method.in'                 => 'El método de pago seleccionado no es válido.',
            'products.required'                 => 'Debe seleccionar por lo menos 1 producto a llevar.',
            'products.array'                    => 'Los productos a llevarse deben ser enviados en array.',
            'products.min'                      => 'Debe seleccionar por lo menos 1 producto para llevarse.',
            'products_refund.required'          => 'Debe seleccionar por lo menos 1 producto a llevar.',
            'products_refund.array'             => 'Los productos a llevarse deben ser enviados en array.',
            'products_refund.min'               => 'Debe seleccionar por lo menos 1 producto para llevarse.',
            'qtys.required'                     => 'Las cantidades de los productos a llevarse es requerida.',
            'qtys.array'                        => 'Las cantidades de los productos a llevarse debe ser enviado en array.',
            'qtys_refund.required'              => 'Las cantidades de los productos a devolver es requerida.',
            'qtys_refund.array'                 => 'Las cantidades de los productos deben ser enviadas en array.',
            'customer_id_new_credit.required'   => 'El cliente para compartir deuda es obligatorio.',
            'customer_id_new_credit.exists'     => 'El cliente para compartir deuda seleccionado no existe en nuestra BD.'
        ];

        if (isset($this->products) && is_array($this->products)) {
            foreach ($this->products as $keyProduct => $product) {
                $messages['products.'. $keyProduct . '.exists'] = 'El producto ' . ($keyProduct + 1) . ' a llevarse no se encuentra en la BD.';

                if (isset($this->qtys[$keyProduct]) && is_array($this->qtys[$keyProduct])) {
                    foreach ($this->qtys[$keyProduct] as $keyStore => $qty) {
                        $messages['qtys.' . $keyProduct . '.' . $keyStore . '.numeric'] = 'La cantidad para el producto ' . ($keyProduct + 1) . ', deposito ' . ($keyStore + 1) . ' a llevarse no es válida.';
                    }
                }
            }
        }

        if (isset($this->products_refund) && is_array($this->products_refund)) {
            foreach ($this->products_refund as $keyProductRefund => $productRefund) {
                $messages['products_refund.'. $keyProductRefund . '.exists'] = 'El producto ' . ($keyProductRefund + 1) . ' a devolver no se encuentra en la BD.';

                if (isset($this->qtys[$keyProductRefund]) && is_array($this->qtys[$keyProductRefund])) {
                    foreach ($this->qtys[$keyProductRefund] as $keyStore => $qty) {
                        $messages['qtys_refund.' . $keyProductRefund . '.' . $keyStore . '.numeric'] = 'La cantidad para el producto ' . ($keyProductRefund + 1) . ', deposito ' . ($keyStore + 1) . ' a devolver no es válida.';
                    }
                }
            }
        }

        if (isset($this->payment_method) && $this->payment_method == 'credit') {
            $messages['amount-quotas.required'] = 'El campo cantidad de cuotas es obligatorio.';
            $messages['amount-quotas.min'] = 'El campo cantidad de cuotas debe ser mayor a :min.';
            $messages['start-quotas.required'] = 'El campo Fecha de inicio de Pago es obligatorio.';
            $messages['quotas.required'] = 'required';
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
            // 'products_refund.*' => 'exists:orders_products,id',
            'qtys_refund' => 'required|array',
            // 'qtys_refund.*' => 'numeric'
        ];

        if (!empty($this->products)) {
            $rules['payment_method'] = ['required', Rule::in(['bankwire', 'card', 'cash', 'credit'])];
            $rules['products'] = 'required|array|min:1';
            // $rules['products.*'] = 'exists:products,id';
            $rules['qtys'] = 'required|array';
            // $rules['qtys.*'] = 'numeric';
        }

        if (isset($this->needs_customer_debt)) {
            $rules['customer_id_new_credit'] = 'required|exists:customers,id';
        }

        if (isset($this->payment_method) && $this->payment_method == 'credit') {
            $rules['amount-quotas'] = 'required|numeric|min:1';
            $rules['start-quotas'] = 'required|date';
            $rules['quotas'] = 'required';
        }

        return $rules;
    }

    /**
     * 
     */
    public function withValidator($validator)
    {
        /**
         * Para realizar una devolucion, el usuario debe tener una caja abierta
         */
        if (!$this->box_id) {
            $validator->after(function ($validator) {
                $validator->errors()->add('box', 'El usuario no tiene una caja abierta en este momento.');
            });
        }

        /** 
         * Si en el formulario de la devolucion, se habilita pautar una visita, debe seleccionar la fecha
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
            #identificar a quien se le asignara el credito 
            $validator->after(function ($validator) {
                $credit_customer_id = isset($this->customer_id_new_credit) ? $this->customer_id_new_credit : $this->customer_id;
                $customer_credit = Customer::find($credit_customer_id);
                $balance = $customer_credit->getBalance();
                $total_sale_with_discount = $this->getBuyTotal()['total'];
                $max_credit = $customer_credit->max_credit ; 
                $available_credit = $max_credit + $balance >= 0 ? $max_credit + $balance : 0;
                $refund_credit =  $this->getRefundTotal()['total_by_credit'];

                if (!$this->canRefundAndBuyOnCredit( $max_credit, $balance, $refund_credit,  $total_sale_with_discount)){
                    $validator->errors()->add('payment_method', 'El usuario no cuenta con credito disponible para hacer la venta.
                     Su compra debe ser menor o igual a $'. number_format(($available_credit + $refund_credit), 2, '.', ',') );
                }

            });
        }

        if (!$validator->fails()) {
            $this->merge([
                'date' => now()
            ]);

            if (!empty($this->products)) {
                $this->merge([
                    'payed_bankwire'    => isset($this->payment_method) && $this->payment_method == 'bankwire' ? 1 : 0,
                    'payed_card'        => isset($this->payment_method) && $this->payment_method == 'card' ? 1 : 0,
                    'payed_cash'        => isset($this->payment_method) && $this->payment_method == 'cash' ? 1 : 0,
                    'payed_credit'      => isset($this->payment_method) && $this->payment_method == 'credit' ? 1 : 0
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
        $box = $user->boxes()->where('closed', 0)->first(); // Se busca la caja abierta del usuario
        $visit_date = $this->isMethod('POST') && !empty($this->visit_date) ? 
                    Carbon::createFromFormat('d-m-Y', $this->visit_date) : null; // Se puede pautar una visita al realizar una venta

        $this->merge([
            'box_id'            => $box ? $box->id : null,
            'user_id'           => $user->id,
            'visit_date'        => $visit_date ? $visit_date->format('Y-m-d') : null
        ]);
    }

     /**
     * Se calculan los totales de la devolucion
     *      * @return Array
     */
    public function getRefundTotal()
    {
        $totals = OrderService::getTotalsToRefund( $this->orderProductRepository , $this->products_refund, $this->qtys_refund );
        return [
            'total' => $totals['total'],
            'total_by_credit' =>$totals['total_by_credit'],
            'total_by_debit' => $totals['total_by_debit']
        ];
    }


     /**
     * Se calculan los totales de la nueva venta(descuento incluido)
     * @return Array
     */
    public function getBuyTotal()
    {
        $discount = isset($this->discount) && is_numeric($this->discount) ? $this->discount : 0;
        $subtotal = 0;

        foreach ($this->products as $keyProduct => $stores) {
            if ($product = $this->productRepository->find($keyProduct)) {
                if (isset($this->qtys[$keyProduct])) {
                    foreach ($this->qtys[$keyProduct] as $keyStore => $qty) {
                        if ($qty <= 0) { continue; }

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

        return [
            'discount' => $discount,
            'subtotal' => $subtotal,
            'total' =>  $total_to_pay,
        ];
    }

     /**
     * Se valida si el usuario puede hacer una compra a credito
     * @return bool
     */
    public function canRefundAndBuyOnCredit($max_credit, $balance, $refund_credit,  $total_sale_with_discount )
    {
        $result = $max_credit + $balance + $refund_credit - $total_sale_with_discount ;
        return $result >= 0 ? true : false;
    }
}
