<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class VisitResponsableRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'user_responsable_id.required' => 'El campo responsable es obligatorio.',
            'user_responsable_id.exists' => 'El responsable seleccionado no se encuentra en nuestra BD.'
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
            'user_responsable_id' => 'required|exists:users,id'
        ];
    }
}
