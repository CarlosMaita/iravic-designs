<?php

namespace App\Http\Requests\admin;

use App\Repositories\Eloquent\ProductRepository;
use Carbon\Carbon;
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

        if (isset($this->needs_customer_debt)) {
            $rules['customer_id_new_credit'] = 'required|exists:customers,id';
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
            'stock_type'        => $user->getColumnStock(), // La cantidades de productos se descontaran de este stock
            'user_id'           => $user->id,
            'visit_date'        => $visit_date ? $visit_date->format('Y-m-d') : null
        ]);
    }
}
