<?php

namespace App\Http\Requests\admin\Catalog;

use App\Models\Product;
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
            'code.unique' => 'Ya existe un producto con el código ingresado.',
            // 'color_id.required' => 'El campo color es obligatorio.',
            // 'color_id.exists' => 'El color seleccionado no existe en nuestra BD.',
            'colors.required' => 'Debe seleccionar un color para cada combinación.',
            'colors.required' => 'Debe seleccionar un color para cada combinación ya existente.',
            'combinations.required' => 'Debe agregar al menos 1 combinación.',
            'gender.required' => 'El campo género es obligatorio.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El campo nombre debe tener un mínimo de :min caracteres.',
            'name.max' => 'El campo nombre debe tener un máximo de :max caracteres.',
            'price.required' => 'El campo Precio es obligatorio.',
            'price.min' => 'El campo Precio no debe ser menor a :min.',
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

        if (!isset($this->is_regular) || (isset($this->is_regular) && $this->is_regular == 0)) {
            if (isset($this->combinations_group)) {
                foreach (array_keys($this->combinations_group) as $i) {
                    $combination_num = ($i + 1);

                    if (isset($this->product_combinations[$i])) {
                        foreach ($this->product_combinations[$i] as $key_product_combination => $j) {
                            $size_num = ($key_product_combination + 1);

                            $messages['stocks_depot_existing.' . $i . '.' . $j . '.min'] = 'El Stock Depósito de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' no puede ser menor a :min';
                            $messages['stocks_depot_existing.' . $i . '.' . $j . '.integer'] = 'El Stock Depósito de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' debe ser un numéro entero.';
                            $messages['stocks_local_existing.' . $i . '.' . $j . '.min'] = 'El Stock Local de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' no puede ser menor a :min';
                            $messages['stocks_local_existing.' . $i . '.' . $j . '.integer'] = 'El Stock Local de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' debe ser un numéro entero.';
                            $messages['stocks_truck_existing.' . $i . '.' . $j . '.min'] = 'El Stock Camión de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' no puede ser menor a :min';
                            $messages['stocks_truck_existing.' . $i . '.' . $j . '.integer'] = 'El Stock Camión de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' debe ser un numéro entero.';

                            if (isset($this->prices_existing[$i][$j]) && $this->prices_existing[$i][$j]) {
                                $messages['prices_existing.' . $i . '.' . $j . '.min'] = 'El precio de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' no puede ser menor a :min';
                                $messages['prices_existing.' . $i . '.' . $j . '.numeric'] = 'El precio de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' debe ser un valor numérico.';
                            }
        
                            if (isset($this->sizes_existing[$i][$j]) && $this->sizes_existing[$i][$j]) {
                                $messages['sizes_existing.' . $i . '.' . $j . '.exists'] = 'La talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' no se encuentra disponible en la BD.';
                            }
                        }
                    }

                    if (isset($this->combinations[$i])) {
                        $total_existing = isset($this->product_combinations[$i]) && is_array($this->product_combinations[$i]) 
                                                        ? count($this->product_combinations[$i]) 
                                                        : 0;

                        foreach (array_keys($this->combinations[$i]) as $j) {
                            $size_num = ($total_existing + $j + 1);

                            $messages['stocks_depot.' . $i . '.' . ($j + $total_existing) . '.min'] = 'El Stock Depósito ' . $size_num . ' de la combinación ' . ($combination_num) . ' no puede ser menor a :min';
                            $messages['stocks_depot.' . $i . '.' . ($j + $total_existing) . '.integer'] = 'El Stock Depósito ' . $size_num . ' de la combinación ' . ($combination_num) . ' debe ser un numéro entero.';
                            $messages['stocks_local.' . $i . '.' . ($j + $total_existing) . '.min'] = 'El Stock Local de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' no puede ser menor a :min';
                            $messages['stocks_local.' . $i . '.' . ($j + $total_existing) . '.integer'] = 'El Stock Local de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' debe ser un numéro entero.';
                            
                            $messages['stocks_truck.' . $i . '.' . ($j + $total_existing) . '.min'] = 'El Stock Camión de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' no puede ser menor a :min';
                            $messages['stocks_truck.' . $i . '.' . ($j + $total_existing) . '.integer'] = 'El Stock Camión de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' debe ser un numéro entero.';

                            if (isset($this->prices[$i][$j]) && $this->prices[$i][$j]) {
                                $messages['prices.' . $i . '.' . ($j + $total_existing) . '.min'] = 'El precio de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' no puede ser menor a :min';
                                $messages['prices.' . $i . '.' . ($j + $total_existing) . '.numeric'] = 'El precio de la talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' debe ser un valor numérico.';
                            }
        
                            if (isset($this->sizes[$i][$j]) && $this->sizes[$i][$j]) {
                                $messages['sizes.' . $i . '.' . ($j + $total_existing) . '.exists'] = 'La talla ' . $size_num . ' de la combinación ' . ($combination_num) . ' no se encuentra disponible en la BD.';
                            }
                        }
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
            'name' => 'required|min:3|max:155',
            'gender' => 'required',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0'
        ];

        if ($this->isMethod('POST')) {
            $rules['code'] = 'required|min:1|max:100|unique:products,code,NULL,id,deleted_at,NULL';
        } else {
            $rules['code'] = 'required||min:1|max:100|unique:products,code,' . $this->route('producto')->id;
        }
        
        if (!isset($this->is_regular) || (isset($this->is_regular) && $this->is_regular == 0)) {
            if ($this->isMethod('POST')) {
                $rules['combinations'] = 'required';
            } else if (!isset($this->combinations)) {
                $rules['product_combinations'] = 'required';
            }

            if (isset($this->combinations_group)) {
                foreach (array_keys($this->combinations_group) as $i) {
                    if (isset($this->product_combinations[$i])) {
                        foreach ($this->product_combinations[$i] as $j) {
                            $rules['stocks_depot_existing.' . $i . '.' . $j] = 'integer|min:0';
                            $rules['stocks_local_existing.' . $i . '.' . $j] = 'integer|min:0';
                            $rules['stocks_truck_existing.' . $i . '.' . $j] = 'integer|min:0';

                            if (isset($this->prices_existing[$i][$j]) && $this->prices_existing[$i][$j]) {
                                $rules['prices_existing.' . $i . '.' . $j] = 'min:0|numeric';
                            }
        
                            if (isset($this->sizes_existing[$i][$j]) && $this->sizes_existing[$i][$j]) {
                                $rules['sizes_existing.' . $i . '.' . $j] = 'exists:sizes,id';
                            }
                        }
                    }

                    if (isset($this->combinations[$i])) {
                        $total_existing = isset($this->product_combinations[$i]) && is_array($this->product_combinations[$i]) 
                                                        ? count($this->product_combinations[$i]) 
                                                        : 0;
                        foreach (array_keys($this->combinations[$i]) as $j) {
                            $rules['stocks_depot.' . $i . '.' . ($j + $total_existing)] = 'integer|min:0';
                            $rules['stocks_local.' . $i . '.' . ($j + $total_existing)] = 'integer|min:0';
                            $rules['stocks_truck.' . $i . '.' . ($j + $total_existing)] = 'integer|min:0';

                            if (isset($this->prices[$i][($j + $total_existing)]) && $this->prices[$i][($j + $total_existing)]) {
                                $rules['prices.' . $i . '.' . ($j + $total_existing)] = 'min:0|numeric';
                            }
        
                            if (isset($this->sizes[$i][($j + $total_existing)]) && $this->sizes[$i][($j + $total_existing)]) {
                                $rules['sizes.' . $i . '.' . ($j + $total_existing)] = 'exists:sizes,id';
                            }
                        }
                    }
                }
            }
        } else {
            $rules['stock_depot'] = 'integer|min:0|nullable';
            $rules['stock_local'] = 'integer|min:0|nullable';
            $rules['stock_truck'] = 'integer|min:0|nullable';
        }

        return $rules;
    }

    /**
     * 
     */
    public function withValidator($validator)
    {
        if (!$this->is_regular && isset($this->combinations_group)) {
            foreach(array_keys($this->combinations_group) as $key) {
                $combination_num = ($key + 1);

                if (empty($this->combinations_group_colors[$key])) {
                    $validator->after(function ($validator) use($combination_num) {
                        $validator->errors()->add('color_' . $combination_num, 'Debe seleccionar un color para la combinación ' . ($combination_num) . '.');
                    });
                } else {
                    if (isset($this->product_combinations[$key])) {
                        foreach ($this->product_combinations[$key] as $key_product_combination => $id_product_combination) {
                            $size_num = ($key_product_combination + 1);

                            if (empty($this->sizes_existing[$key][$id_product_combination])) {
                                $validator->after(function ($validator) use($size_num, $combination_num) {
                                    $validator->errors()->add('sizes_' . $size_num, 'Debe seleccionar la talla ' .  ($size_num) .  ' para la combinación ' . ($combination_num));
                                });
                            }
                        }
                    }
    
                    if (isset($this->combinations[$key])) {
                        $total_existing = isset($this->product_combinations[$key]) && is_array($this->product_combinations[$key]) 
                                                        ? count($this->product_combinations[$key]) 
                                                        : 0;
                        foreach (array_keys($this->combinations[$key]) as $key_new_combination) {
                            $size_num = ($total_existing + $key_new_combination + 1);

                            // if (empty($this->sizes[$key][$key_new_combination])) {
                            if (empty($this->sizes[$key][($key_new_combination + $total_existing)])) {
                                $validator->after(function ($validator) use($size_num, $combination_num) {
                                    $validator->errors()->add('sizes_' . $size_num, 'Debe seleccionar la talla ' .  ($size_num) .  ' para la combinación ' . ($combination_num));
                                });
                            }
                        }
                    }
                }

                if (!empty($this->combinations_group_code[$key])) {
                    if ($this->isMethod('POST')) {
                        $product_with_code = Product::where('code', $this->combinations_group_code[$key])->count();
                    } else {
                        $product_id_route = $this->route('producto')->id;
                        $product_with_code = Product::where('code', $this->combinations_group_code[$key])
                                                    ->where(function($q) use($product_id_route) {
                                                        $q->where('id', '<>', $product_id_route)
                                                            ->orWhere('product_id', '<>', $product_id_route);
                                                    })
                                                    ->count();
                    }

                    if ($product_with_code) {
                        $validator->after(function ($validator) use($combination_num) {
                            $validator->errors()->add('code_' . $combination_num, 'El código de la combinación ' . ($combination_num) . ' ya esta registrado para otro producto.');
                        });
                    }
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
        if (isset($this->is_regular)) {
            $stock_depot = !empty($this->stock_depot) ? $this->stock_depot : 0;
            $stock_local = !empty($this->stock_local) ? $this->stock_local : 0;
            $stock_truck = !empty($this->stock_truck) ? $this->stock_truck : 0;

            $this->merge([
                'stock_depot' => $stock_depot,
                'stock_local' => $stock_local,
                'stock_truck' => $stock_truck
            ]);
        } else {
            if (isset($this->combinations_group)) {
                $stocks_depot = array();
                $stocks_local = array();
                $stocks_truck = array();
                $stocks_depot_existing = array();
                $stocks_local_existing = array();
                $stocks_truck_existing = array();

                foreach (array_keys($this->combinations_group) as $i) {
                    if (isset($this->product_combinations[$i])) {
                        foreach ($this->product_combinations[$i] as $j) {
                            $stocks_depot_existing[$i][$j] = isset($this->stocks_depot_existing[$i][$j]) ? $this->stocks_depot_existing[$i][$j] : 0;
                            $stocks_local_existing[$i][$j] = isset($this->stocks_local_existing[$i][$j]) ? $this->stocks_local_existing[$i][$j] : 0;
                            $stocks_truck_existing[$i][$j] = isset($this->stocks_truck_existing[$i][$j]) ? $this->stocks_truck_existing[$i][$j] : 0;
                        }
                    }
                    
                    if (isset($this->combinations[$i])) {
                        $total_existing = isset($this->product_combinations[$i]) && is_array($this->product_combinations[$i]) 
                                                        ? count($this->product_combinations[$i]) 
                                                        : 0;
                        foreach (array_keys($this->combinations[$i]) as $j) {
                            $stocks_depot[$i][($j + $total_existing)] = isset($this->stocks_depot[$i][($j + $total_existing)]) ? $this->stocks_depot[$i][($j + $total_existing)] : 0;
                            $stocks_local[$i][($j + $total_existing)] = isset($this->stocks_local[$i][($j + $total_existing)]) ? $this->stocks_local[$i][($j + $total_existing)] : 0;
                            $stocks_truck[$i][($j + $total_existing)] = isset($this->stocks_truck[$i][($j + $total_existing)]) ? $this->stocks_truck[$i][($j + $total_existing)] : 0;
                        }
                    }
                }

                $this->merge([
                    'stocks_depot' => $stocks_depot,
                    'stocks_local' => $stocks_local,
                    'stocks_truck' => $stocks_truck,
                    'stocks_depot_existing' => $stocks_depot_existing,
                    'stocks_local_existing' => $stocks_local_existing,
                    'stocks_truck_existing' => $stocks_truck_existing
                ]);
            }
        }

        $this->merge([
            'is_regular'        => isset($this->is_regular) ? 1 : 0
        ]);
    }
}
