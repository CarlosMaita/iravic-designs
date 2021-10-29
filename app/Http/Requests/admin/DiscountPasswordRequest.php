<?php

namespace App\Http\Requests\admin;

use App\Models\Config;
use Illuminate\Foundation\Http\FormRequest;

class DiscountPasswordRequest extends FormRequest
{
    public function messages()
    {
        return  [
            'discount_password.required' => 'La contraseña para descuentos es obligatoria.'
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
            'discount_password' => 'required'
        ];
    }

    /**
     * 
     */
    public function withValidator($validator)
    {
        if (!empty($this->discount_password)) {
            $discount_password = Config::getConfig('discount_password');

            if ($discount_password->value && $discount_password->value != $this->discount_password) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('discount_password', 'La contraseña para descuentos es incorrecta.');
                });
            } else if (!$discount_password->value) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('discount_password', 'Los descuentos no estan habilitados.');
                });
            }
        }
    }
}
