<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class UserDestroyRequest extends FormRequest
{
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
        return [];
    }

    /**
     * Add validation messages in case of error 
     */
    public function withValidator($validator)
    {
        $user = $this->route('usuario');

        // Note: Superadmin role check disabled - roles system removed in #116
        // if (!empty($user) && $user->roles()->pluck('name')->contains('superadmin')) {
        //     $validator->after(function ($validator) {
        //         $validator->errors()->add('superadmin', 'No puede eliminar usuarios con el perfil Superadmin.');
        //     });
        // }
    }
}
