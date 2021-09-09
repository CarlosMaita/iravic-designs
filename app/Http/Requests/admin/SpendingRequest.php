<?php

namespace App\Http\Requests\admin;

use App\Repositories\Eloquent\BoxRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SpendingRequest extends FormRequest
{
    public $boxRepository;

    public function __construct(BoxRepository $boxRepository)
    {
        $this->boxRepository = $boxRepository;
    }

    public function messages()
    {
        return [
            'amount.required' => 'El campo monto es obligatorio.',
            'amount.min' => 'El monto debe ser mayor a :min',
            'amount.numeric' => 'El monto debe ser un valor numérico.'
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
            'amount' => 'required|numeric|min:0.01'
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
        $box = $this->boxRepository->getOpenByUserId($user->id);

        $this->merge([
            'box_id'            => $box ? $box->id : null,
            'date'              => now(),
            'user_id'           => $user->id
        ]);
    }
}
