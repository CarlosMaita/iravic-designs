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
}