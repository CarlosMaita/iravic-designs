<?php

namespace App\Repositories\Eloquent;

use App\Constants\DaysConstants;
use App\Constants\FrequencyCollectionConstants;
use App\Models\Customer;
use App\Models\Visit;
use Illuminate\Support\Collection;
use App\Repositories\CustomerRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{

    /**
     * CustomerRepository constructor.
     *
     * @param Customer $model
     */
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de clientes
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with('zone')->orderBy('name')->get();
    }

    /**
     * Retorna listado de clientes
     * 
     * @return Collection
     */
    public function allQuery()
    {
        return $this->model->with('zone')->orderBy('name');
    }


    /**
     * Retrieve all records with only 'id' and 'name' fields from the model.
     *
     * @return Collection
     */
    public function allOnlyName(): Collection{
        return DB::table($this->model->getTable())
            ->select(['id', 'name'])
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();
    }

 
    /**
     * Retorna listado de clientes que tienen deudas y necesitan ser avisados/visitados.
     * Cada modelo de cliente tiene un metodo "needsToNotifyDebt" para validar si necesita entrar en este listado
     * 
     * @return Collection
     */
    public function debtorsToNotify(): Collection
    {
        $customers = $this->model->with('zone')
                            ->whereHas('debts')
                            ->orWhereHas('orders', function($q) {
                                $q->where('payed_credit', 1);
                            })
                            ->get();

        return $customers->filter(function ($customer) {
            return $customer->needsToNotifyDebt();
        })->values();
    }

    /**
     * Retorna listado de clientes que se les ha postergado la visita y necesitan ser reagendados. 
     * Cada modelo de cliente tiene un metodo "needsToNotifyDebt" para validar si necesita entrar en este listado
     * 
     * @return Collection
     */
    public function pendingToScheduleToNotify(): Collection
    {
        $customers = $this->model->where('is_pending_to_schedule', 1 )
                            ->with('zone')
                            ->get();

        return $customers;
    }

    public function updateVisits( $collection_frequency, $collection_day, $customer_id ){

        $Visits  = Visit::where('customer_id', $customer_id)
                        ->whereDate('date', '>=', now())
                        ->get();   

        $nextDate = self::setNextDateVisit( Carbon::parse(now()) , $collection_frequency, $collection_day);
        $Visits->each(function ($visit) use ($nextDate, $collection_frequency, $collection_day) {
            $visit->update(['date' => $nextDate ]);  
            $nextDate = self::setNextDateVisit( $nextDate , $collection_frequency , $collection_day);
        });
    }

    public static function setNextDateVisit( $date, $collection_frequency, $collection_day ): Carbon
    {
        $numberDay  = DaysConstants::collectionDayToNumber($collection_day);
        $numberWeek = FrequencyCollectionConstants::getWeekWithCollectionFrequency($collection_frequency);
        /* No hay fecha programada para el cobro, Crea una nueva fecha de cobro */
        // cada mes
        switch ($collection_frequency) {
            case FrequencyCollectionConstants::CADA_MES_PRIMERA_SEMANA:
            case FrequencyCollectionConstants::CADA_MES_SEGUNDA_SEMANA:
            case FrequencyCollectionConstants::CADA_MES_TERCERA_SEMANA:
            case FrequencyCollectionConstants::CADA_MES_CUARTA_SEMANA:
                $date->addMonth();
                $date->startOfMonth();
                $date->next( $numberDay );
                $date->addWeeks($numberWeek - 1);
                break;
            case FrequencyCollectionConstants::CADA_DOS_SEMANAS:
                $date->next( $numberDay );
                $date->next( $numberDay );
                break;
            case FrequencyCollectionConstants::CADA_SEMANA:
                $date->next( $numberDay );
                break;
            default:
                break;
        }
                
       return $date;
    }
}