<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MyProfileRequest extends FormRequest
{
    public function messages()
    {
        return [
            'current_password.required' => 'La contraseña actual es obligatoria.'
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
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . Auth::user()->id,
            'current_password' => 'required',
        ];

        if (!empty($this->password)) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }

    /**
     * Add validation messages in case of error 
     */
    public function withValidator($validator)
    {
        if (!empty($this->current_password)) {
            if (!Hash::check($this->current_password, Auth::user()->password)) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('current_password', 'La contraseña actual que ha ingresado no coincide.');
                });
            }
        }

        if (!$validator->fails()) {
            if (!empty($this->password)) {
                $this->merge(['password' => Hash::make($this->password)]);
            } else {
                $this->merge(['password' => Auth::user()->password]);
            }
        }
    }
}
