<?php

namespace App\Repositories\Eloquent;

use App\Constants\FrequencyCollectionConstants;
use App\Models\Credit;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Visit;
use App\Repositories\CreditRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class CreditRepository extends BaseRepository implements CreditRepositoryInterface
{

    /**
     * CreditRepository constructor.
     *
     * @param Credit $model
     */
    public function __construct(Credit $model)
    {
        parent::__construct($model);
    }


    /**
     * create a new collection
     * 
     * @param array $attributes
     * @return Credit
     * 
     * */
    public function create(array $attributes) : Credit
    {
        return $this->model->create($attributes);
    }


    /**
     *  get all credits
     *  
     *  
     * 
     * */

    public function all() : SupportCollection
    {
        return $this->model->all();

    }

    public function allWithCustomer() : SupportCollection 
    {
        return $this->model->with('order.customer')->get();
    }
    
    public function createCollectionsAndVisits($attributes){
        $user_id = $attributes['user_id'];
        $customer_id = $attributes['customer_id'];
        $credit_id = $attributes['credit_id'];

        $startDate = $attributes['start_date'];
        $quotasNumber = $attributes['amount_quotas'];
        $quota = $attributes['quota'];
        
        $customer = Customer::find($customer_id);
            $collection_day = $customer->collection_day;
            $collection_frequency = $customer->collection_frequency;

        $date = self::getDateSync($startDate, $customer_id);
        
        DB::beginTransaction();
        try {
            for ($i = 0; $i < $quotasNumber; $i++) {
                // fecha de hoy es menor que la fecha de la cuota - no crear visita
               if ($date->lt(Carbon::now())) {
                   // Próxima Fecha 
                   $date = self::setNextDate($date, $collection_day, $collection_frequency, $customer_id); 
                   $i--;
                   continue;
               }
   
                /**
                * Crear Cobro
                * */ 
               Collection::create(
                    array(
                       'date' => $date->format('Y-m-d'), 
                       'amount' => $quota,
                       'is_completed' => 0,
                       'number' => $i + 1,
                       'credit_id' => $credit_id
                   )
               );
   
               /**
                * Crear Agenda si no existe
                * */ 
               $schedule =  Schedule::firstOrCreate(
                   array(
                       'date' => $date->format('Y-m-d')
                   )
               ) ;
               
                /**
                * Si tiene Visita actualizarla, sino creala
                * */
               $visit = Visit::where('customer_id', $customer_id)
                   ->where('date', $date->format('Y-m-d'))
                   ->where('is_collection', true)
                   ->first();
   
               if ($visit) {
                   $visit->comment = 'COBRO DE CREDITO';
                   $visit->is_collection = true;
                   $visit->suggested_collection += $quota; // add the new quota to the suggested collection 
                   $visit->save();
               } else {
                   Visit::create(
                       array(
                           'customer_id' => $customer_id,
                           'schedule_id' => $schedule->id,
                           'user_id' => $user_id,
                           'comment' => 'COBRO DE CREDITO',
                           'date' => $date->format('Y-m-d'), 
                           'is_collection' => true,
                           'suggested_collection' => $quota 
                       )
                   );
               }
   
               // Próxima Fecha
               $date = self::setNextDate($date, $collection_day, $collection_frequency, $customer_id); 
           }

           DB::commit();

        }catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    private static function setNextDate($date, $collection_day, $collection_frequency, $customer_id): Carbon
    {
        // Hay fecha programada para el cobro? 
        $futureDate = self::getDateFuture($date,  $collection_frequency, $customer_id);
        if (is_null($futureDate))
        {
            $numberDay = self::collectionDayToNumber($collection_day);
            $numberWeek = self::getWeekWithCollectionFrequency($collection_frequency);
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

        return $futureDate;;

       
    }

    private static function getDateFuture($date, $collection_frequency, $customer_id)
    {
        $visit = Visit::where('customer_id', $customer_id)
            ->where('date', '>', $date->format('Y-m-d'))
            ->where('is_collection', true)
            ->orderBy('date', 'asc')
            ->first();
        
        if ($visit) {
            // Hay que limitar el rango de búsqueda de visitas futuras se plantea un condicional que limite la búsqueda
            // Ej. : semanal (Max 14 días), quincenal (Max 28 días), mensual (Max 56 días) 
            Switch ($collection_frequency) {
                case FrequencyCollectionConstants::CADA_SEMANA:
                    if (Carbon::parse($date)->diffInDays($visit->date) > 14) {
                        return null;
                    }
                    break;
                case FrequencyCollectionConstants::CADA_DOS_SEMANAS:
                    if (Carbon::parse($date)->diffInDays($visit->date) > 28) {
                        return null;
                    }
                    break;
                case FrequencyCollectionConstants::CADA_MES_PRIMERA_SEMANA:
                case FrequencyCollectionConstants::CADA_MES_SEGUNDA_SEMANA:
                case FrequencyCollectionConstants::CADA_MES_TERCERA_SEMANA:
                case FrequencyCollectionConstants::CADA_MES_CUARTA_SEMANA:
                    if (Carbon::parse($date)->diffInDays($visit->date) > 56) {
                        return null;
                    }
                    break;
            }
               
            return Carbon::parse($visit->date);
        }

        return null;
    }

    /**
     * Determines the week number corresponding to the given collection frequency.
     *
     * @param string $collection_frequency The frequency of collection, which should be one of the predefined constants
     *                                     representing specific weeks of the month.
     * @return int The week number (1 to 4) corresponding to the collection frequency.
     */
    private static function getWeekWithCollectionFrequency($collection_frequency): int
    {
        switch ($collection_frequency) {
            case FrequencyCollectionConstants::CADA_MES_PRIMERA_SEMANA:
                return 1;
                break;  
            case FrequencyCollectionConstants::CADA_MES_SEGUNDA_SEMANA:
                return 2;
                break;
            case FrequencyCollectionConstants::CADA_MES_TERCERA_SEMANA:
                return 3;
                break;
            case FrequencyCollectionConstants::CADA_MES_CUARTA_SEMANA:
                return 4;
                break;
            default:
                return 0;
        }   
    }

    private static function getDateSync($startDate, $customer_id)  : Carbon
    {
        /**
         *  Obtener la fecha de sicnronizacion con la fecha de inicio de cuota de crédito del cliente en los cobros 
         */
        $syncVisit = Visit::where('customer_id', $customer_id)
            ->where('date', '>=', Carbon::parse($startDate))
            ->where('is_collection', true)
            ->orderBy('date', 'asc')
            ->first();

        if ($syncVisit) {
            return Carbon::parse($syncVisit->date);
        }

        /**
         * Si no tiene fecha de sincronizacion, tomar la fecha de inicio de cuota de crédito del cliente
         * en base a el dia de cobro configurado
         */
        $customer = Customer::find($customer_id);
        $collection_day = $customer->collection_day;
        $collection_frequency = $customer->collection_frequency;

        return self::getFirstDate($startDate, $collection_day, $collection_frequency);
    }


    private static function getFirstDate($startDate, $collection_day, $collection_frequency) : Carbon
    {
        $date = Carbon::parse($startDate);
        $numberDay = self::collectionDayToNumber($collection_day);
        $date->next($numberDay);
        
        if( $collection_frequency == FrequencyCollectionConstants::CADA_DOS_SEMANAS ){
            return $date->next($numberDay);
        }elseif( in_array( $collection_frequency, [
            FrequencyCollectionConstants::CADA_MES_PRIMERA_SEMANA,
            FrequencyCollectionConstants::CADA_MES_SEGUNDA_SEMANA,
            FrequencyCollectionConstants::CADA_MES_TERCERA_SEMANA,
            FrequencyCollectionConstants::CADA_MES_CUARTA_SEMANA ])
            )
        {
            $numberWeek = self::getWeekWithCollectionFrequency($collection_frequency);
            do{
                $date->addMonth();
                $date->startOfMonth();
                $date->next($numberDay);
                $date->addWeeks($numberWeek - 1);
            }
            while( $date < Carbon::parse($startDate));

            return $date;
        }

        return $date; 
    }


/**
 * Convert a day of the week from Spanish to its corresponding Carbon constant.
 *
 * @param string $collection_day The day of the week in Spanish (e.g., 'Lunes', 'Martes').
 * @return int The corresponding Carbon constant for the day of the week.
 */

    private static function collectionDayToNumber($collection_day) 
    {
        switch ($collection_day) {
            case 'Lunes':
                return Carbon::MONDAY;
                break;
            case 'Martes':
                return Carbon::TUESDAY;
                break;
            case 'Miercoles':
                return Carbon::WEDNESDAY;
                break;
            case 'Jueves':
                return Carbon::THURSDAY;
                break;
            case 'Viernes':
                return Carbon::FRIDAY;
                break;
            case 'Sabado':
                return Carbon::SATURDAY;
                break;
            case 'Domingo':
                return Carbon::SUNDAY;
                break;
        }
    }

    private static function periodToDays( String $quotas_period) : int
    {
        switch ($quotas_period) {
            case FrequencyCollectionConstants::CADA_SEMANA:
                return 7;
                break;  
            case FrequencyCollectionConstants::CADA_DOS_SEMANAS:
                return 14;
                break;
            default:
                return 7;
        }
    }
}