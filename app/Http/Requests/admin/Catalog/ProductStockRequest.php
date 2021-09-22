<?php

namespace App\Http\Requests\admin\Catalog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStockRequest extends FormRequest
{
    /**
     * Get the validation messages that apply to the request rules.
     */
    public function messages()
    {
        return [
            'product_id.required' => 'El producto es obligatorio.',
            'product_id.exists' => 'El producto no existe en nuestra BD.',
            'stock.required' => 'La cantidad es obligatoria.',
            'stock.integer' => 'La cantidad debe ser un valor entero.',
            'stock.min' => 'La cantidad mínima es de :min.',
            'stock_column.required' => 'El tipo de stock es obligatorio.',
            'stock_column.in' => 'Solo se puede modificar stocks de Depósito, Local y Camión.',
            'stock_name.required' => 'El nombre de stock es obligatorio.'
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
            'stock_column'  => ['required', Rule::in(['stock_depot', 'stock_local', 'stock_truck'])],
            'stock' => 'required|integer|min:0',
            'stock_name' => 'required'
        ];
    }
}
