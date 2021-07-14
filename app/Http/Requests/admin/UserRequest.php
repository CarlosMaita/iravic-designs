<?php

namespace App\Http\Requests\admin;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRequest extends FormRequest
{
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña debe tener un min :min de caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'role_id.required' => 'El campo rol es obligatorio.',
            'role_id.exists' => 'El Rol seleccionado no existe en la Base de Datos.'
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
            'role_id' => 'required|exists:roles,id'
        ];

        if ($this->isMethod('POST')) {
            $rules['email'] = 'required|email|unique:users,email,NULL,id,deleted_at,NULL';
            $rules['password'] = 'required|min:6|confirmed';
        } else {
            $rules['email'] = 'required|email|unique:users,email,' . $this->route('usuario')->id;

            if (!empty($this->password)) {
                $rules['password'] = 'required|min:6|confirmed';
            }
        }

        return $rules;
    }

    /**
     * Add validation messages in case of error 
     */
    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $this->merge(['role' => Role::find($this->role_id)]);

            if ($this->isMethod('POST')) {
                $this->merge(['password' => Hash::make($this->password)]);
                $this->merge(['deleted_at' => null]);
            } else if (!empty($this->password) && $this->isMethod('PUT')) {
                $this->merge(['password' => $this->password]);
            } else if ($this->isMethod('PUT')) {
                $this->merge(['password' => Auth::user()->password]);
            }
        }
    }
}
