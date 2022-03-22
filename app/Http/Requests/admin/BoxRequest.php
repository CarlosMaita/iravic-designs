<?php

namespace App\Http\Requests\admin;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BoxRequest extends FormRequest
{
    public function messages()
    {
        return [
            'cash_initial.required' => 'El campo cantidad inicial de efectivo es obligatoria.',
            'cash_initial.numeric' => 'La cantidad inicial de efectivo debe ser valor numérico.',
            'user_id.required' => 'El sistema no ha podido obtener el ID del creador de la caja.'
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
        if (!$this->header('close-box')) {
            $rules =  [
                'cash_initial' => 'required|numeric',
            ];

            if ($this->isMethod('POST')) {
                $rules['user_id'] = 'required|exists:users,id';
            }

            return $rules;
        }

        return [];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Se manda un header para mandar a cerrar la caja
        if ($this->isMethod('POST') && !$this->header('close-box')) {
            $this->merge([
                'date' => now()->format('Y-m-d'),
                'date_start' => now(),
                'user_id' => Auth::user()->id
            ]);
        }
    }
}
