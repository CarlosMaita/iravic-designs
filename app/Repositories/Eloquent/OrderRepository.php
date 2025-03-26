<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use Illuminate\Support\Collection;
use App\Repositories\OrderRepositoryInterface;
use Carbon\Carbon;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{

    /**
     * OrderRepository constructor.
     *
     * @param Order $model
     */
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de todos las ventas de todos los clientes
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with(['customer', 'user'])->orderBy('date', 'DESC')->get();
    }

    public function query(){
        return $this->model->with(['customer', 'user'])->orderBy('date', 'DESC');
    }
    
    /**
     * Calculates the total sales for a given number of months.
     *
     * @param int $months The number of months to calculate the total sales for.
     * @return float The total sales for the specified number of months.
     */
    public function getTotalSales_perMonths($months)
    {
        $start  = Carbon::now()->subMonth($months)->startOfMonth();
        $end  = Carbon::now()->endOfMonth();
        return  $this->model->whereBetween('date', [$start, $end])->sum('total');    
    }

    /**
     * Calculates the total sales between two specific dates.
     *
     * @param string $date_initial The initial date for the sales calculation.
     * @param string $date_final The final date for the sales calculation.
     * @return float The total sales amount between the specified dates.
     */
    public function getTotalSales_OnDates($date_initial, $date_final){
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfDay();
        return  $this->model->whereBetween('date', [$start, $end])->sum('total');
    }

    /**
     * Calculates the total sales between two specific months.
     *
     * @param string $date_initial The initial date for the sales calculation in the format 'd/m/Y'.
     * @param string $date_final The final date for the sales calculation in the format 'd/m/Y'.
     * @return float The total sales amount between the specified months.
     */
    public function getTotalSales_OnMonths( $date_initial, $date_final){
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfMonth();
        return  $this->model->whereBetween('date', [$start, $end])->sum('total');
    }

    /**
     * Calculates the total payment for a specific payment method over a given number of months.
     *
     * @param int $months The number of months to calculate the total payment for.
     * @param string $method The specific payment method to calculate the total payment for. Default is 'any'.
     * @return float The total payment amount for the specified payment method and number of months.
     */
    public function getTotalPaymentMethod_perMonths($months, $method = 'any')
    {
        $start  = Carbon::now()->subMonth($months)->startOfMonth();
        $end  = Carbon::now()->endOfMonth();
        switch ($method) {
            case 'credit':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_credit', 1)->sum('total');
                break;
            case 'cash':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_cash', 1)->sum('total');
                break;
            case 'card':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_card', 1)->sum('total');
                break;
            case 'transfer':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_bankwire', 1)->sum('total');
                break;
            default:
                return  $this->model->whereBetween('date', [$start, $end])->sum('total');
                break;
        }
        return  $this->model->whereBetween('date', [$start, $end])->where->sum('total');    
    }


    
    /**
     * Calculates the total payment for a specific payment method over a given date range.
     *
     * @param string $date_initial The initial date for the payment calculation in the format 'd/m/Y'.
     * @param string $date_final The final date for the payment calculation in the format 'd/m/Y'.
     * @param string $method The specific payment method to calculate the total payment for. Default is 'any'.
     * @return float The total payment amount for the specified payment method and date range.
     */
    public function getTotalPaymentMethod_OnDates( $date_initial, $date_final, $method = 'any'){
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfDay();
        switch ($method) {
            case 'credit':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_credit', 1)->sum('total');
                break;
            case 'cash':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_cash', 1)->sum('total');
                break;
            case 'card':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_card', 1)->sum('total');
                break;
            case 'transfer':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_bankwire', 1)->sum('total');
                break;
            default:
                return  $this->model->whereBetween('date', [$start, $end])->sum('total');
                break;
        }   
    }

    /**
     * Calculates the total payment for a specific payment method over a given month range.
     *
     * @param string $date_initial The initial date for the payment calculation in the format 'd/m/Y'.
     * @param string $date_final The final date for the payment calculation in the format 'd/m/Y'.
     * @param string $method The specific payment method to calculate the total payment for. Default is 'any'.
     * @return float The total payment amount for the specified payment method and month range.
     */
    public function getTotalPaymentMethod_OnMonths( $date_initial, $date_final, $method = 'any'){
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfMonth();
        switch ($method) {
            case 'credit':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_credit', 1)->sum('total');
                break;
            case 'cash':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_cash', 1)->sum('total');
                break;
            case 'card':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_card', 1)->sum('total');
                break;
            case 'transfer':
                return  $this->model->whereBetween('date', [$start, $end])->where('payed_bankwire', 1)->sum('total');
                break;
            default:
                return  $this->model->whereBetween('date', [$start, $end])->sum('total');    
                break;
        }

    }

    
    public function getTotalPaid_perMonths($months){
        $start  = Carbon::now()->subMonth($months)->startOfMonth();
        $end  = Carbon::now()->endOfMonth(); 
        
        return  $this->model->whereBetween('date', [$start, $end])
            ->where(function ($query) {
                $query->where('payed_cash', 1)
                    ->orWhere('payed_card', 1)
                    ->orWhere('payed_bankwire', 1);
                })
            ->sum('total');
    }

    public function getTotalPaid_OnMonths( $date_initial, $date_final){
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfMonth();
        return  $this->model->whereBetween('date', [$start, $end])
            ->where(function ($query) {
                $query->where('payed_cash', 1)
                    ->orWhere('payed_card', 1)
                    ->orWhere('payed_bankwire', 1);
                })
            ->sum('total');
    }   

    public function getTotalPaid_OnDates($date_initial, $date_final){
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfDay();
        return  $this->model->whereBetween('date', [$start, $end])
            ->where(function ($query) {
                $query->where('payed_cash', 1)
                    ->orWhere('payed_card', 1)
                    ->orWhere('payed_bankwire', 1);
                })
            ->sum('total');
    }
}