<?php

namespace App\Http\Requests\admin;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VisitRequest extends FormRequest
{
    public function messages()
    {
        return [
            'date.required' => 'El campo fecha es obligatorio.'
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
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $user = Auth::user();
        $date = !empty($this->date) ? Carbon::createFromFormat('d-m-Y', $this->date) : null;

        $this->merge([
            'user_creator_id'   => $user->id,
            'date'              => $date
        ]);
    }
}
