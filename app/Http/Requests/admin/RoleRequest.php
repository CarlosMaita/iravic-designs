<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.unique' => 'El campo nombre ya se encuentra ocupado por otro rol.'
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
        $rules = array();

        if ($this->isMethod('POST')) {
            $rules['name'] = 'required|unique:roles,name';
        } else {
            $rules['name'] = 'required|unique:roles,name,' . $this->route('role')->id;
        }

        return $rules;
    }

    /**
     * Add validation messages in case of error 
     */
    public function withValidator($validator)
    {
        $this->merge(['is_employee' => isset($this->is_employee) ? 1 : 0]);
    }
}
