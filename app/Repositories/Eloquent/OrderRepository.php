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

    public function getOrdersGraph_perDays($months = 0)
    {
        // 1. Definir el rango de fechas
        // Desde el inicio del mes N meses atrás hasta el final del día de HOY.
        $start = Carbon::now()->subMonths($months)->startOfMonth();
        $end   = Carbon::now()->endOfDay(); // Hasta el final del día actual para incluir todas las ventas de hoy
        // 2. Obtener las ventas TOTALES POR DÍA desde la base de datos
        // Es crucial ordenar por fecha para calcular el acumulado correctamente.
        $dailySales = $this->model->selectRaw("DATE(date) as sale_date, SUM(total) as daily_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'asc') // ¡Importante ordenar por fecha!
            ->get()
            // Mapear a un formato [ 'd-m' => total_diario ]
            ->mapWithKeys(function ($item) {
                 // Asegurarse que $item->sale_date es interpretado correctamente como fecha
                return [Carbon::parse($item->sale_date)->format('d-m') => (float)$item->daily_total];
            })
            ->toArray();

        // 3. Crear un array base con TODAS las fechas del rango y valor 0
        $allDatesWithZero = [];
        $currentDate = $start->copy(); // Copiar para no modificar $start
        while ($currentDate->lte($end)) {
            $allDatesWithZero[$currentDate->format('d-m')] = 0;
            $currentDate->addDay();
        }

        // 4. Combinar los ceros con las ventas diarias reales
        // Esto asegura que los días sin ventas tengan un valor de 0 antes de acumular.
        // array_merge sobreescribe valores de $allDatesWithZero si la clave existe en $dailySales.
        $dailyTotalsComplete = array_merge($allDatesWithZero, $dailySales);

        return $dailyTotalsComplete;
    }

    public function getOrdersGraph_OnDates($start, $end){
        $start = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();

        $dailySales = $this->model->selectRaw("DATE(date) as sale_date, SUM(total) as daily_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->sale_date)->format('d-m') => (float)$item->daily_total];
            })
            ->toArray();

        $allDatesWithZero = [];
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            $allDatesWithZero[$currentDate->format('d-m')] = 0;
            $currentDate->addDay();
        }

        $dailyTotalsComplete = array_merge($allDatesWithZero, $dailySales);

        return $dailyTotalsComplete;
    }

    public function getPaidGraph_perDay($months = 0)
    {
        $start = Carbon::now()->subMonths($months)->startOfMonth();
        $end   = Carbon::now()->endOfDay();

        $dailySales = $this->model->selectRaw("DATE(date) as sale_date, SUM(total) as daily_total")
            ->where(function ($query) {
                $query->where('payed_cash', 1)
                    ->orWhere('payed_card', 1)
                    ->orWhere('payed_bankwire', 1);
            })
            ->whereBetween('date', [$start, $end])
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->sale_date)->format('d-m') => (float)$item->daily_total];
            })
            ->toArray();

        $allDatesWithZero = [];
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            $allDatesWithZero[$currentDate->format('d-m')] = 0;
            $currentDate->addDay();
        }

        $dailyTotalsComplete = array_merge($allDatesWithZero, $dailySales);

        return $dailyTotalsComplete;
    }


    public function getTotalPaymentMethodGraph_perDay($months = 0, $method, $method2 = null)
    {
        $start = Carbon::now()->subMonths($months)->startOfMonth();
        $end = Carbon::now()->endOfDay();

        $dailySales = $this->model->selectRaw("DATE(date) as sale_date, SUM(total) as daily_total")
            ->whereBetween('date', [$start, $end])
            ->where(function ($query) use ($method, $method2) {
                $query->where('payed_' . $method, 1);
                if ($method2) {
                    $query->orWhere('payed_' . $method2, 1);
                }
            })
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->sale_date)->format('d-m') => (float)$item->daily_total];
            })
            ->toArray();

        $allDatesWithZero = [];
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            $allDatesWithZero[$currentDate->format('d-m')] = 0;
            $currentDate->addDay();
        }

        $dailyTotalsComplete = array_merge($allDatesWithZero, $dailySales);

        return $dailyTotalsComplete;

    }

    public function getOrdersGraph_perMonths($months = 0)
    {
        $start = Carbon::now()->subMonths($months)->startOfMonth();
        $end   = Carbon::now()->endOfMonth();

       $monthlySales = $this->model->selectRaw("YEAR(date) as sale_year, MONTH(date) as sale_month, SUM(total) as monthly_total")
           ->whereBetween('date', [$start, $end])
           ->groupBy('sale_year', 'sale_month')
           ->orderBy('sale_year', 'asc')
           ->orderBy('sale_month', 'asc')
           ->get()
           ->mapWithKeys(function ($item) {
               return [Carbon::parse($item->sale_year . '-' . $item->sale_month)->format('M-y') => (float)$item->monthly_total];
           })
           ->toArray();

        $allMonthsWithZero = [];
        $currentMonth = $start->copy();
        for($i = 0; $i < $months; $i++){
            $allMonthsWithZero[$currentMonth->format('M-y')] = 0;
            $currentMonth->addMonth();
        }

        $monthlyTotalsComplete = array_merge($allMonthsWithZero, $monthlySales);

        return $monthlyTotalsComplete;
    }


    public function getTotalPaymentMethodGraph_perMonths($months, $method, $method2 = null){ 
        $start = Carbon::now()->subMonths($months)->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $monthlySales = $this->model->selectRaw("YEAR(date) as sale_year, MONTH(date) as sale_month, SUM(total) as monthly_total")
            ->whereBetween('date', [$start, $end])
            ->where( function ($query) use ($method, $method2) {
                $query->where('payed_' . $method, 1);
                if ($method2) {
                    $query->orWhere('payed_' . $method2, 1);
                }
            })
            ->groupBy('sale_year', 'sale_month')
            ->orderBy('sale_year', 'asc')
            ->orderBy('sale_month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->sale_year . '-' . $item->sale_month)->format('M-y') => (float)$item->monthly_total];
            })
            ->toArray();

        $allMonthsWithZero = [];
        $currentMonth = $start->copy();
        for ($i = 0; $i < $months; $i++) {
            $allMonthsWithZero[$currentMonth->format('M-y')] = 0;
            $currentMonth->addMonth();
        }

        $monthlyTotalsComplete = array_merge($allMonthsWithZero, $monthlySales);

        $cumulativeResults = [];
        $cumulativeSum = 0;

        foreach ($monthlyTotalsComplete as $monthKey => $monthlyValue) {
            $cumulativeSum += $monthlyValue;
            $cumulativeResults[$monthKey] = $cumulativeSum;
        }

        return $cumulativeResults;
    }

    public function getPaidGraph_perMonths($months){
        $start = Carbon::now()->subMonths($months)->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $monthlySales = $this->model->selectRaw("YEAR(date) as sale_year, MONTH(date) as sale_month, SUM(total) as monthly_total")
            ->whereBetween('date', [$start, $end])
            ->where(function ($query) {
                $query->where('payed_cash', 1)
                    ->orWhere('payed_card', 1)
                    ->orWhere('payed_bankwire', 1);
            })
            ->groupBy('sale_year', 'sale_month')
            ->orderBy('sale_year', 'asc')
            ->orderBy('sale_month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->sale_year . '-' . $item->sale_month)->format('M-y') => (float)$item->monthly_total];
            })
            ->toArray();

        $allMonthsWithZero = [];
        $currentMonth = $start->copy();
        for($i = 0; $i < $months; $i++){
            $allMonthsWithZero[$currentMonth->format('M-y')] = 0;
            $currentMonth->addMonth();
        }

        $monthlyTotalsComplete = array_merge($allMonthsWithZero, $monthlySales);

        return $monthlyTotalsComplete;

    }

    public function getTotalPaymentMethodGraph_OnDates($start, $end, $method, $method2 = null){ 
        $start = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();

        $dailySales = $this->model->selectRaw("DATE(date) as sale_date, SUM(total) as daily_total")
            ->whereBetween('date', [$start, $end])
            ->where( function ($query) use ($method, $method2) {
                $query->where('payed_' . $method, 1);
                if ($method2) {
                    $query->orWhere('payed_' . $method2, 1);
                }
            })
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->sale_date)->format('d-m') => (float)$item->daily_total];
            })
            ->toArray();

        $allDatesWithZero = [];
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            $allDatesWithZero[$currentDate->format('d-m')] = 0;
            $currentDate->addDay();
        }

        $dailyTotalsComplete = array_merge($allDatesWithZero, $dailySales);

        return $dailyTotalsComplete;

    }

    public function getPaidGraph_OnDates($start, $end) {
        $start = Carbon::createFromFormat('d/m/Y', $start)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $end)->endOfDay();

        $dailySales = $this->model->selectRaw("DATE(date) as sale_date, SUM(total) as daily_total")
            ->whereBetween('date', [$start, $end])
            ->where(function ($query) {
                $query->where('payed_cash', 1)
                    ->orWhere('payed_card', 1)
                    ->orWhere('payed_bankwire', 1);
            })
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->sale_date)->format('d-m') => (float)$item->daily_total];
            })
            ->toArray();

        $allDatesWithZero = [];
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            $allDatesWithZero[$currentDate->format('d-m')] = 0;
            $currentDate->addDay();
        }

        $dailyTotalsComplete = array_merge($allDatesWithZero, $dailySales);

        return $dailyTotalsComplete;
    }

    public function getOrdersGraph_OnMonths($start, $end)
    {
        $start = Carbon::createFromFormat('d/m/Y', $start)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $end)->endOfMonth();

        $monthlyOrders = $this->model->selectRaw("YEAR(date) as order_year, MONTH(date) as order_month, SUM(total) as monthly_total")
            ->whereBetween('date', [$start, $end])
            ->groupBy('order_year', 'order_month')
            ->orderBy('order_year', 'asc')
            ->orderBy('order_month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->order_year . '-' . $item->order_month)->format('M-y') => (float)$item->monthly_total];
            })
            ->toArray();

        $allMonthsWithZero = [];
        $currentMonth = $start->copy();
        while ($currentMonth->lte($end)) {
            $allMonthsWithZero[$currentMonth->format('M-y')] = 0;
            $currentMonth->addMonth();
        }

        $monthlyTotalsComplete = array_merge($allMonthsWithZero, $monthlyOrders);

        return $monthlyTotalsComplete;
    }

    public function getTotalPaymentMethodGraph_OnMonths($start, $end, $method, $method2 = null)
    {
        $start = Carbon::createFromFormat('d/m/Y', $start)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $end)->endOfMonth();

        $monthlySales = $this->model->selectRaw("YEAR(date) as sale_year, MONTH(date) as sale_month, SUM(total) as monthly_total")
            ->whereBetween('date', [$start, $end])
            ->where(function ($query) use ($method, $method2) {
                $query->where('payed_' . $method, 1);
                if ($method2) {
                    $query->orWhere('payed_' . $method2, 1);
                }
            })
            ->groupBy('sale_year', 'sale_month')
            ->orderBy('sale_year', 'asc')
            ->orderBy('sale_month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->sale_year . '-' . $item->sale_month)->format('M-y') => (float)$item->monthly_total];
            })
            ->toArray();

        $allMonthsWithZero = [];
        $currentMonth = $start->copy();
        while ($currentMonth->lte($end)) {
            $allMonthsWithZero[$currentMonth->format('M-y')] = 0;
            $currentMonth->addMonth();
        }

        $monthlyTotalsComplete = array_merge($allMonthsWithZero, $monthlySales);

        return $monthlyTotalsComplete;

    }

    public function getPaidGraph_OnMonths($start, $end)
    {
        $start = Carbon::createFromFormat('d/m/Y', $start)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $end)->endOfMonth();

        $monthlySales = $this->model->selectRaw("YEAR(date) as sale_year, MONTH(date) as sale_month, SUM(total) as monthly_total")
            ->whereBetween('date', [$start, $end])
            ->where(function ($query) {
                $query->where('payed_cash', 1)
                    ->orWhere('payed_card', 1)
                    ->orWhere('payed_bankwire', 1);
            })
            ->groupBy('sale_year', 'sale_month')
            ->orderBy('sale_year', 'asc')
            ->orderBy('sale_month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->sale_year . '-' . $item->sale_month)->format('M-y') => (float)$item->monthly_total];
            })
            ->toArray();

        $allMonthsWithZero = [];
        $currentMonth = $start->copy();
        while ($currentMonth->lte($end)) {
            $allMonthsWithZero[$currentMonth->format('M-y')] = 0;
            $currentMonth->addMonth();
        }

        $monthlyTotalsComplete = array_merge($allMonthsWithZero, $monthlySales);

        return $monthlyTotalsComplete;
    }


}