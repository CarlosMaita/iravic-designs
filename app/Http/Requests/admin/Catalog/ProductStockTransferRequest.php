<?php

namespace App\Http\Requests\admin\Catalog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductStockTransferRequest extends FormRequest
{
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
            'stock_origin.required' => 'El tipo de stock es obligatorio.',
            'stock_origin.in' => 'Solo se pueden transferir stocks de Local y Camión.',
            'stock_destination.required' => 'El tipo de stock es obligatorio.',
            'stock_destination.in' => 'Solo se pueden transferir stocks de Local y Camión.'
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
            'qty' => 'required|numeric',
            'stock_origin' => ['required', Rule::in(['stock_local', 'stock_truck'])],
            'stock_destination' => ['required', Rule::in(['stock_local', 'stock_truck'])],
        ];
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
