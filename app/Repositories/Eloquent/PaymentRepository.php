<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use Illuminate\Support\Collection;
use App\Repositories\PaymentRepositoryInterface;
use Carbon\Carbon;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{

    /**
     * PaymentRepository constructor.
     *
     * @param Payment $model
     */
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de todos los pagos. Puede filtrar por cliente o por caja de un cliente
     * 
     * @return Collection
     */
    public function all($params = null): Collection
    {
        $payments = $this->model->with(['box', 'customer', 'user']);

        if (isset($params['customer'])) {
            $payments->where('customer_id', $params['customer']);
        }

        if (isset($params['box'])) {
            $payments->where('box_id', $params['box']);
        }
        
        return $payments->orderBy('date', 'DESC')->get();
    }

    /**
     * Calculates the total collections for a specified number of months.
     *
     * @param int $months The number of months to calculate the total collections for.
     * @return mixed The sum of the 'amount' column for payments within the specified range.
     */
    public function getTotalCollections_perMonths($months){
        $start  = Carbon::now()->subMonth($months)->startOfMonth();
        $end  = Carbon::now()->endOfMonth();
        return $this->model->whereBetween('date', [$start, $end])->sum('amount');
    }

    /**
     * Calculates the total amount of collections made between two given dates.
     *
     * @param string $date_initial The initial date in the format 'd/m/Y'.
     * @param string $date_final The final date in the format 'd/m/Y'.
     * @return float The total amount of collections made between the two dates.
     */
    public function getTotalCollections_OnDates($date_initial, $date_final){
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfDay();
        return $this->model->whereBetween('date', [$start, $end])->sum('amount');
    }

    /**
     * Calculates the total collections between two specific months.
     *
     * @param string $date_initial The initial date for the collections calculation in the format 'd/m/Y'.
     * @param string $date_final The final date for the collections calculation in the format 'd/m/Y'.
     * @return float The total amount of collections made between the specified months.
     */
    public function getTotalCollections_OnMonths($date_initial, $date_final){
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfMonth();
        return $this->model->whereBetween('date', [$start, $end])->sum('amount');
    }

    public function getTotalCollectedGraph_perDay($months = 0)
    {
        $start = Carbon::now()->subMonths($months)->startOfMonth();
        $end = Carbon::now()->endOfDay();

        $dailyCollections = $this->model->selectRaw("DATE(date) as collection_date, SUM(amount) as daily_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy('collection_date')
            ->orderBy('collection_date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->collection_date)->format('d-m') => (float)$item->daily_total];
            })
            ->toArray();

        $allDatesWithZero = [];
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            $allDatesWithZero[$currentDate->format('d-m')] = 0;
            $currentDate->addDay();
        }

        $dailyTotalsComplete = array_merge($allDatesWithZero, $dailyCollections);

        return $dailyTotalsComplete;
    }

    public function getTotalCollectedGraph_perMonths($months){
        $start = Carbon::now()->subMonths($months)->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $monthlyCollections = $this->model->selectRaw("YEAR(date) as collection_year, MONTH(date) as collection_month, SUM(amount) as monthly_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy(['collection_year', 'collection_month'])
            ->orderBy('collection_year', 'asc')
            ->orderBy('collection_month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->collection_year . '-' . $item->collection_month)->format('M-y') => (float)$item->monthly_total];
            })
            ->toArray();

        $allMonthsWithZero = [];
        $currentMonth = $start->copy();
        for($i = 0; $i < $months; $i++){
            $allMonthsWithZero[$currentMonth->format('M-y')] = 0;
            $currentMonth->addMonth();
        }

        $monthlyTotalsComplete = array_merge($allMonthsWithZero, $monthlyCollections);

        return $monthlyTotalsComplete;
    }

    public function getTotalCollectedGraph_OnDates($start, $end)
    {
        $start = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();

        $dailyCollections = $this->model->selectRaw("DATE(date) as collection_date, SUM(amount) as daily_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy('collection_date')
            ->orderBy('collection_date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->collection_date)->format('d-m') => (float)$item->daily_total];
            })
            ->toArray();

        $allDatesWithZero = [];
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            $allDatesWithZero[$currentDate->format('d-m')] = 0;
            $currentDate->addDay();
        }

        $dailyTotalsComplete = array_merge($allDatesWithZero, $dailyCollections);

        return $dailyTotalsComplete;
    }

    public function getTotalCollectedGraph_OnMonths($start, $end)
    {
        $start = Carbon::createFromFormat('d/m/Y', $start)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $end)->endOfMonth();

        $monthlyCollections = $this->model->selectRaw("YEAR(date) as collection_year, MONTH(date) as collection_month, SUM(amount) as monthly_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy(['collection_year', 'collection_month'])
            ->orderBy('collection_year', 'asc')
            ->orderBy('collection_month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->collection_year . '-' . $item->collection_month)->format('M-y') => (float)$item->monthly_total];
            })
            ->toArray();

        $allMonthsWithZero = [];
        $currentMonth = $start->copy();
        while ($currentMonth->lte($end)) {
            $allMonthsWithZero[$currentMonth->format('M-y')] = 0;
            $currentMonth->addMonth();
        }

        $monthlyTotalsComplete = array_merge($allMonthsWithZero, $monthlyCollections);

        return $monthlyTotalsComplete;
    }
}