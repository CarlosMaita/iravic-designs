<?php

namespace App\Http\Requests\admin;

use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\VisitRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VisitRequest extends FormRequest
{
    public $max_customers_per_zone;

    public $customerRepository;

    public $scheduleRepository;

    public $visitRepository;

    public function __construct(CustomerRepository $customerRepository, VisitRepository $visitRepository)
    {
        $this->max_customers_per_zone = 25;
        $this->customerRepository = $customerRepository;
        $this->visitRepository = $visitRepository;
    }

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
            'date'              => $date->format('Y-m-d')
        ]);
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $customer = $this->customerRepository->find($this->customer_id);
            $customersCount = $this->visitRepository->getCountCustomersFromZone($this->date, $customer->id, $customer->zone_id);
            $hasCustomerVisit = $this->visitRepository->hasCustomerVisitForDate($this->date, $customer->id);
            
            if ($hasCustomerVisit) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('customer_visit', 'El cliente ya tiene una visita pautada para el día seleccionado.');
                });
            }

            if ($customersCount >= $this->max_customers_per_zone) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('zone', 'La zona ya posee 25 clientes a visitar para el día seleccionado.');
                });
            }
        }
    }
}
