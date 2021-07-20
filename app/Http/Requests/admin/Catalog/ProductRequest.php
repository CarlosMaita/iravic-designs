<?php

namespace App\Http\Requests\admin\Catalog;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function messages()
    {
        $messages = [
            'brand_id.required' => 'El campo marca es obligatorio.',
            'brand_id.exists' => 'La marca seleccionada no existe en nuestra BD.',
            'category_id.required' => 'El campo categoría es obligatorio.',
            'category_id.exists' => 'La categoría seleccionada no existe en nuestra BD.',
            'code.required' => 'El campo código es obligatorio.',
            'code.min' => 'El campo código debe tener un mínimo  de :min caracteres.',
            'code.max' => 'El campo código debe tener un máximo de :max caracteres.',
            'color_id.required' => 'El campo color es obligatorio.',
            'color_id.exists' => 'El color seleccionado no existe en nuestra BD.',
            'colors.required' => 'Debe seleccionar un color para cada combinación.',
            'combinations.required' => 'Debe agregar al menos 1 combinación.',
            'gender.required' => 'El campo género es obligatorio.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El campo nombre debe tener un mínimo de :min caracteres.',
            'name.max' => 'El campo nombre debe tener un máximo de :max caracteres.',
            'size_id.required' => 'El campo talla es obligatorio.',
            'size_id.exists' => 'La talla seleccionada no existe en nuestra BD.',
            'sizes.required' => 'Debe seleccionar una talla para cada combinación.',
            'stock_depot.required' => 'El campo Stock Depósito es obligatorio.',
            'stock_depot.min' => 'El Stock Depósito no puede ser menor a :min.',
            // 'stocks_depot.required' => 'Debe ingresar el stock Depósito para cada combinación.',
            'stock_local.required' => 'El campo Stock Local es obligatorio.',
            'stock_local.min' => 'El Stock Local no puede ser menor a :min.',
            // 'stocks_local.required' => 'Debe ingresar el stock Local para cada combinación.',
            'stock_truck.required' => 'El campo Stock Camioneta es obligatorio.',
            'stock_truck.min' => 'El Stock Camioneta no puede ser menor a :min.',
            // 'stocks_depot.required' => 'Debe ingresar el stock Camioneta para cada combinación.'
        ];

        
        foreach ($this->combinations as $combination) {
            $messages['colors.' . $combination . 'exists'] = 'El color seleccionado para la combinación ' . ($combination + 1) . ' no se encuentra disponible en la BD.';
            $messages['sizes.' . $combination . 'exists'] = 'La talla seleccionada para la combinación ' . ($combination + 1) . ' no se encuentra disponible en la BD.';
            $messages['stocks_depot.' . $combination . '.integer'] = 'Ingrese Stock Depósito válido para la combinación ' . ($combination + 1);
            $messages['stocks_depot.' . $combination . '.min'] = 'El Stock Depósito para la combinación ' . ($combination + 1) . ' no puede ser menor a :min';
            $messages['stocks_local.' . $combination . '.integer'] = 'Ingrese Stock Local válido para la combinación ' . ($combination + 1);
            $messages['stocks_local.' . $combination . '.min'] = 'El Stock Local para la combinación ' . ($combination + 1) . ' no puede ser menor a :min';
            $messages['stocks_truck.' . $combination . '.integer'] = 'Ingrese Stock Camioneta válido para la combinación ' . ($combination + 1);
            $messages['stocks_truck.' . $combination . '.min'] = 'El Stock Camioneta para la combinación ' . ($combination + 1) . ' no puede ser menor a :min';
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
            'name' => 'required|min:3|max:155',
            'code' => 'required|min:1|max:100',
            'gender' => 'required',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id'
        ];

        if (!isset($this->is_regular)) {
            $rules['combinations'] = 'required';

            if (isset($this->combinations) && is_array($this->combinations) && count($this->combinations)) {
                $rules['colors'] = 'required';
                $rules['colors.*'] = 'exists:colors,id';
                $rules['sizes'] = 'required';
                $rules['sizes.*'] = 'exists:sizes,id';
                $rules['stocks_depot'] = 'required';
                $rules['stocks_depot.*'] = 'integer|min:0';
                $rules['stocks_local'] = 'required';
                $rules['stocks_local.*'] = 'integer|min:0';
                $rules['stocks_truck'] = 'required';
                $rules['stocks_truck.*'] = 'integer|min:0';
            }
        } else {
            $rules['color_id'] = 'required|exists:colors,id';
            $rules['size_id'] = 'required|exists:sizes,id';
            $rules['stock_depot'] = 'required|integer|min:0';
            $rules['stock_local'] = 'required|integer|min:0';
            $rules['stock_truck'] = 'required|integer|min:0';
        }

        return $rules;
    }
}
