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
            // 'color_id.required' => 'El campo color es obligatorio.',
            // 'color_id.exists' => 'El color seleccionado no existe en nuestra BD.',
            'colors.required' => 'Debe seleccionar un color para cada combinación.',
            'colors.required' => 'Debe seleccionar un color para cada combinación ya existente.',
            'combinations.required' => 'Debe agregar al menos 1 combinación.',
            'gender.required' => 'El campo género es obligatorio.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El campo nombre debe tener un mínimo de :min caracteres.',
            'name.max' => 'El campo nombre debe tener un máximo de :max caracteres.',
            // 'size_id.required' => 'El campo talla es obligatorio.',
            // 'size_id.exists' => 'La talla seleccionada no existe en nuestra BD.',
            'sizes.required' => 'Debe seleccionar una talla para cada combinación.',
            'sizes_existing.required' => 'Debe seleccionar una talla para cada combinación ya existente.',
            'stock_depot.required' => 'El campo Stock Depósito es obligatorio.',
            'stock_depot.min' => 'El Stock Depósito no puede ser menor a :min.',
            'stock_local.required' => 'El campo Stock Local es obligatorio.',
            'stock_local.min' => 'El Stock Local no puede ser menor a :min.',
            'stock_truck.required' => 'El campo Stock Camioneta es obligatorio.',
            'stock_truck.min' => 'El Stock Camioneta no puede ser menor a :min.'
        ];

        /**
         * Existing combinations when editing product
         */
        if (isset($this->product_combinations)) {
            foreach ($this->product_combinations as $key => $i) {
                $combination = ($key + 1);
                $messages['colors_existing.' . $i . 'exists'] = 'El color seleccionado para la combinación ' . ($combination) . ' no se encuentra disponible en la BD.';
                $messages['sizes_existing.' . $i . 'exists'] = 'La talla seleccionada para la combinación ' . ($combination) . ' no se encuentra disponible en la BD.';
                $messages['stocks_depot_existing.' . $i . '.integer'] = 'Ingrese Stock Depósito válido para la combinación ' . ($combination);
                $messages['stocks_depot_existing.' . $i . '.min'] = 'El Stock Depósito para la combinación ' . ($combination) . ' no puede ser menor a :min';
                $messages['stocks_local_existing.' . $i . '.integer'] = 'Ingrese Stock Local válido para la combinación ' . ($combination);
                $messages['stocks_local_existing.' . $i . '.min'] = 'El Stock Local para la combinación ' . ($combination) . ' no puede ser menor a :min';
                $messages['stocks_local_existing.' . $i . '.integer'] = 'Ingrese Stock Camioneta válido para la combinación ' . ($combination);
                $messages['stocks_local_existing.' . $i . '.min'] = 'El Stock Camioneta para la combinación ' . ($combination) . ' no puede ser menor a :min';
                $messages['prices_existing.' . $i . '.min'] = 'El precio para la combinación ' . ($combination) . ' no puede ser menor a :min';
                $messages['prices_existing.' . $i . '.numeric'] = 'Ingrese un Precio válido para la combinación ' . ($combination);
            }
        }

        /**
         * New ones
         */
        if (isset($this->combinations)) {
            $total_existing_combinations = isset($this->product_combinations) && is_array($this->product_combinations) ? count($this->product_combinations) : 0;
            foreach ($this->combinations as $key => $i) {
                $combination = ($total_existing_combinations + $key + 1);
                $messages['colors.' . $i . 'exists'] = 'El color seleccionado para la combinación ' . ($combination) . ' no se encuentra disponible en la BD.';
                $messages['sizes.' . $i . 'exists'] = 'La talla seleccionada para la combinación ' . ($combination) . ' no se encuentra disponible en la BD.';
                $messages['stocks_depot.' . $i . '.integer'] = 'Ingrese Stock Depósito válido para la combinación ' . ($combination);
                $messages['stocks_depot.' . $i . '.min'] = 'El Stock Depósito para la combinación ' . ($combination) . ' no puede ser menor a :min';
                $messages['stocks_local.' . $i . '.integer'] = 'Ingrese Stock Local válido para la combinación ' . ($combination);
                $messages['stocks_local.' . $i . '.min'] = 'El Stock Local para la combinación ' . ($combination) . ' no puede ser menor a :min';
                $messages['stocks_truck.' . $i . '.integer'] = 'Ingrese Stock Camioneta válido para la combinación ' . ($combination);
                $messages['stocks_truck.' . $i . '.min'] = 'El Stock Camioneta para la combinación ' . ($combination) . ' no puede ser menor a :min';
                $messages['prices.' . $i . '.min'] = 'El precio para la combinación ' . ($combination) . ' no puede ser menor a :min';
                $messages['prices.' . $i . '.numeric'] = 'Ingrese un Precio válido para la combinación ' . ($combination);
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
            'name' => 'required|min:3|max:155',
            'code' => 'required|min:1|max:100',
            'gender' => 'required',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric'
        ];

        if (!isset($this->is_regular) || (isset($this->is_regular) && $this->is_regular == 0)) {
            if ($this->isMethod('POST')) {
                $rules['combinations'] = 'required';
            } else if (!isset($this->combinations)) {
                $rules['product_combinations'] = 'required';
            }

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

                if (!empty($this->prices)) {
                    foreach ($this->combinations as $combination) {
                        if (!empty($this->prices[$combination])) {
                            $rules['prices.' . $combination] = 'min:0|numeric';
                        }
                    }
                }
            }

            if (isset($this->product_combinations) && is_array($this->product_combinations) && count($this->product_combinations)) {
                $rules['colors_existing'] = 'required';
                $rules['colors_existing.*'] = 'exists:colors,id';
                $rules['sizes_existing'] = 'required';
                $rules['sizes_existing.*'] = 'exists:sizes,id';
                $rules['stocks_depot_existing'] = 'required';
                $rules['stocks_depot_existing.*'] = 'integer|min:0';
                $rules['stocks_local_existing'] = 'required';
                $rules['stocks_local_existing.*'] = 'integer|min:0';
                $rules['stocks_truck_existing'] = 'required';
                $rules['stocks_truck_existing.*'] = 'integer|min:0';

                if (!empty($this->prices_existing)) {
                    foreach ($this->product_combinations as $product_combination_id) {
                        if (!empty($this->prices_existing[$product_combination_id])) {
                            $rules['prices_existing.' . $product_combination_id] = 'min:0|numeric';
                        }
                    }
                }
            }
        } else {
            // $rules['color_id'] = 'required|exists:colors,id';
            // $rules['size_id'] = 'required|exists:sizes,id';
            $rules['stock_depot'] = 'required|integer|min:0';
            $rules['stock_local'] = 'required|integer|min:0';
            $rules['stock_truck'] = 'required|integer|min:0';
        }

        return $rules;
    }

    /**
     * 
     */
    public function withValidator($validator)
    {
        /**
         * Existing combinations when editing product
         */
        if (isset($this->product_combinations)) {
            foreach ($this->product_combinations as $key => $i) {
                $combination = ($key + 1);

                if (!isset($this->colors_existing[$i])) {
                    $validator->after(function ($validator) use($i, $combination) {
                        $validator->errors()->add('color_existing_' . $i, 'Debe seleccionar un color para la combinación ' . ($combination) . ' .');
                    });
                }

                if (!isset($this->sizes_existing[$i])) {
                    $validator->after(function ($validator) use($i, $combination) {
                        $validator->errors()->add('size_existing' . $i, 'Debe seleccionar una talla para la combinación ' . ($combination) . ' .');
                    });
                }
            }
        }
        
        /**
         * New ones
         */
        if (isset($this->combinations) && is_array($this->combinations)) {
            $total_existing_combinations = isset($this->product_combinations) && is_array($this->product_combinations) ? count($this->product_combinations) : 0;
            foreach ($this->combinations as $key => $i) {
                $combination = ($total_existing_combinations + $key + 1);

                if (!isset($this->colors[$i])) {
                    $validator->after(function ($validator) use($i, $combination) {
                        $validator->errors()->add('color_' . $i, 'Debe seleccionar un color para la combinación ' . ($combination) . ' .');
                    });
                }

                if (!isset($this->sizes[$i])) {
                    $validator->after(function ($validator) use($i, $combination) {
                        $validator->errors()->add('size_' . $i, 'Debe seleccionar la talla para la combinación ' . ($combination) . ' .');
                    });
                }
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
            'is_price_generic'  => isset($this->is_price_generic) ? 1 : 0,
            'is_regular'        => isset($this->is_regular) ? 1 : 0
        ]);
    }
}
