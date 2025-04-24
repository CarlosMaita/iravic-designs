<?php

namespace App\Repositories\Eloquent;

use App\Models\Refund;
use Illuminate\Support\Collection;
use App\Repositories\RefundRepositoryInterface;
use Carbon\Carbon;

class RefundRepository extends BaseRepository implements RefundRepositoryInterface
{

    /**
     * RefundRepository constructor.
     *
     * @param Refund $model
     */
    public function __construct(Refund $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de devoluciones
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with(['customer', 'user'])->orderBy('date', 'DESC')->get();
    }

    public function allQuery()
    {
        return $this->model->with(['customer', 'user'])->orderBy('date', 'DESC');
    }

    /**
     * Calculates the total refunds for a given number of months.
     *
     * @param int $months The number of months to calculate the total refunds for.
     * @return float The total refunds for the given number of months.
     */
    public function getTotalRefunds_perMonths($months)
    {
        $start  = Carbon::now()->subMonth($months)->startOfMonth();
        $end  = Carbon::now()->endOfMonth();
        return  $this->model->whereBetween('date', [$start, $end])->sum('total');
    }

    /**
     * Calculates the total refund amount for a given date range.
     *
     * @param string $date_initial The start date of the range in the format 'd/m/Y'.
     * @param string $date_final The end date of the range in the format 'd/m/Y'.
     * @return float The total refund amount.
     */
    public function getTotalRefunds_OnDates($date_initial, $date_final)
    {
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfDay();
        return  $this->model->whereBetween('date', [$start, $end])->sum('total');
    }   

    /**
     * Calculates the total refund amount for a given month range.
     *
     * @param string $date_initial The start date of the range in the format 'd/m/Y'.
     * @param string $date_final The end date of the range in the format 'd/m/Y'.
     * @return float The total refund amount for the given month range.
     */
    public function getTotalRefunds_OnMonths($date_initial, $date_final){
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfMonth();
        return  $this->model->whereBetween('date', [$start, $end])->sum('total');
    }

    
    /**
     * Calculates the total refund amount for each day over a given number of months
     * and returns it in a format suitable for a graph.
     *
     * @param int $months The number of months to calculate the daily refunds for.
     * @return array The daily refund amounts for the given number of months.
     */
    public function getRefundsGraph_perDays($months=0){
        $start = Carbon::now()->subMonths($months)->startOfMonth();
        $end = Carbon::now()->endOfDay();

        $dailyRefunds = $this->model->selectRaw("DATE(date) as refund_date, SUM(total) as daily_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy('refund_date')
            ->orderBy('refund_date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->refund_date)->format('d-m') => (float)$item->daily_total];
            })
            ->toArray();

        $allDatesWithZero = [];
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            $allDatesWithZero[$currentDate->format('d-m')] = 0;
            $currentDate->addDay();
        }

        $dailyTotalsComplete = array_merge($allDatesWithZero, $dailyRefunds);

        return $dailyTotalsComplete;
    }

    public function getRefundsGraph_OnDates($start, $end){
        $start = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();

        $dailyRefunds = $this->model->selectRaw("DATE(date) as refund_date, SUM(total) as daily_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy('refund_date')
            ->orderBy('refund_date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->refund_date)->format('d-m') => (float)$item->daily_total];
            })
            ->toArray();

        $allDatesWithZero = [];
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            $allDatesWithZero[$currentDate->format('d-m')] = 0;
            $currentDate->addDay();
        }

        $dailyTotalsComplete = array_merge($allDatesWithZero, $dailyRefunds);

        return $dailyTotalsComplete;

    }


    public function getRefundsGraph_perMonths($months=0){
        $start = Carbon::now()->subMonths($months)->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $monthlyRefunds = $this->model->selectRaw("YEAR(date) as refund_year, MONTH(date) as refund_month, SUM(total) as monthly_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy('refund_year', 'refund_month')
            ->orderBy('refund_year', 'asc')
            ->orderBy('refund_month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->refund_year . '-' . $item->refund_month)->format('M-y') => (float)$item->monthly_total];
            })
            ->toArray();

        $allMonthsWithZero = [];
        $currentMonth = $start->copy();
        for($i = 0; $i < $months; $i++){
            $allMonthsWithZero[$currentMonth->format('M-y')] = 0;
            $currentMonth->addMonth();
        }

        $monthlyTotalsComplete = array_merge($allMonthsWithZero, $monthlyRefunds);

        return $monthlyTotalsComplete;
    }

    public function getRefundsGraph_OnMonths($start, $end){
        $start = Carbon::createFromFormat('d/m/Y', $start)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $end)->endOfMonth();

        $monthlyRefunds = $this->model->selectRaw("YEAR(date) as refund_year, MONTH(date) as refund_month, SUM(total) as monthly_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy('refund_year', 'refund_month')
            ->orderBy('refund_year', 'asc')
            ->orderBy('refund_month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->refund_year . '-' . $item->refund_month)->format('M-y') => (float)$item->monthly_total];
            })
            ->toArray();

        $allMonthsWithZero = [];
        $currentMonth = $start->copy();
        while ($currentMonth->lte($end)) {
            $allMonthsWithZero[$currentMonth->format('M-y')] = 0;
            $currentMonth->addMonth();
        }

        $monthlyTotalsComplete = array_merge($allMonthsWithZero, $monthlyRefunds);

        return $monthlyTotalsComplete;

    }
    
}