<?php

namespace App\Http\Requests\admin;

use App\Models\Box;
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

    public function withValidator($validator)
    {
       

         /**
         *  Para realizar una devolución, el usuario debe tener una caja abierta
         */
        if (!$this->box_id) {
            $validator->after(function ($validator) {
                $validator->errors()->add('box', 'El usuario no tiene una caja abierta en este momento.');
            });
        }
        #si existe la caja consultar su efectivo en caja
        $box = Box::find($this->box_id);
        if(($box->getTotalCashInBox() - $this->amount) < 0){
            #Para realizar una gasto la caja debe contar con efectivo
            $validator->after(function ($validator) {
                $validator->errors()->add('box', 'No se dispone de suficiente efectivo en caja para registrar este gasto.');
            });
        }
        
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $user = Auth::user();
        $box = $this->boxRepository->getOpenByUserId($user->id); // Se busca la caja abierta del usuario

        $this->merge([
            'box_id'            => $box ? $box->id : null,
            'date'              => now(),
            'user_id'           => $user->id
        ]);
    }
}
