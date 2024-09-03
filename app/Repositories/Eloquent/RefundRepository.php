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
}