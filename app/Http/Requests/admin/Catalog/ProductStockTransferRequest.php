<?php

namespace App\Http\Requests\admin\Catalog;

use App\Models\Store;
use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductStockTransferRequest extends FormRequest
{
    public $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get the validation messages that apply to the request rules.
     */
    public function messages()
    {
        return [
            'product_id.required' => 'El producto es obligatorio.',
            'product_id.exists' => 'El producto no existe en nuestra BD.',
            'qty.required' => 'La cantidad a transferir es obligatoria.',
            'qty.numeric' => 'La cantidad debe ser un valor numérico.',
            'qty.min' => 'La cantidad mínima a transferir es de :min unidad.',
            'stock_origin.required' => 'El deposito de origen es obligatorio.',
            'stock_destination.required' => 'El deposito de destino es obligatorio.',
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
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|numeric|min:1',
            'stock_origin' => 'required',
            'stock_destination' => 'required',
        ];
    }


    /**
     * 
     */
    public function withValidator($validator)
    {
        if ($this->qty && !empty($this->product_id) && $product = $this->productRepository->find($this->product_id)) {

            // get stock origin
            $store_id = $this->stock_origin;
            $stock = $product->stores()->find($store_id)->pivot->stock;

            if ($stock > 0) {
                $pending_stock = $product->stocks_transfers()->where('is_accepted', 0)->where('stock_origin',  $store_id)->sum('qty');
                $available_stock = $stock - $pending_stock;

                if ($this->qty > $available_stock) {
                    $validator->after(function ($validator) use ($available_stock, $pending_stock) {
                        $validator->errors()->add('stock', 'Solamente puede solicitar transferir ' . $available_stock . ' unidad porque aún tiene ' . $pending_stock . ' en stock pendiente por transferir/aceptar.');
                    });
                }
            } else {
                $validator->after(function ($validator) {
                    $validator->errors()->add('stock', 'No hay stock disponible para transferir.');
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
        $this->merge([
            'user_creator_id' => Auth::user()->id
        ]);
    }
}
