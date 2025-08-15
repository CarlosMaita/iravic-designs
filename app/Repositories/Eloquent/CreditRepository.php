<?php

namespace App\Repositories\Eloquent;

use App\Constants\DaysConstants;
use App\Constants\FrequencyCollectionConstants;
use App\Models\Credit;
use App\Models\Collection;
use App\Models\Customer;
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
    
    public function getAllWithRelations($columns = array('*'))
    {
        return $this->model->with('order', 'order.customer')->get();
    }

    /**
     * All debts grouped by customers
     * @param $columns
     * @return [type]
     */
    public function allGroupedByCustomer($columns = array('*'))
    {
        $credits = $this->getAllWithRelations($columns);

        $grouped = SupportCollection::make($credits)->groupBy('order.customer.id');

        return $grouped;
    }

    public function queryAll () 
    {
        return $this->model->with('order.customer');
    } 

    public function orderByCustomer ($query) 
    {
        return $query->with('order.customer');
    }

    public function queryByCustomer ($customer_id) 
    {
        return $this->model->whereHas('order', function ($query) use ($customer_id) {
            $query->where('customer_id', $customer_id);
        });
    }

    public function queryByCredit ($credit_id) 
    {
        return $this->model->where('id', $credit_id);
    }

    public function queryWithCustomer () 
    {
        return $this->model->with('order.customer');
    }
    
    public function createCollectionsAndVisits($attributes){
        $user_id = $attributes['user_id'];
        $customer_id = $attributes['customer_id'];
        $credit_id = $attributes['credit_id'];

        $startDate = $attributes['start_date'];
        $quotasNumber = $attributes['amount_quotas'];
        $quota = $attributes['quota'];
        
        $customer = Customer::find($customer_id);

        // Create collections without visits (scheduling module removed)
        try {
            DB::beginTransaction();
            
            $date = Carbon::parse($startDate);
            
            for ($i = 1; $i <= $quotasNumber; $i++) {
                Collection::create([
                    'credit_id' => $credit_id,
                    'order' => $i,
                    'amount' => $quota,
                    'amount_to_collect' => $quota,
                    'date_to_pay' => $date->format('Y-m-d'),
                    'date_to_collect' => $date->format('Y-m-d'),
                    'customer_id' => $customer_id,
                    'is_paid' => false,
                ]);
                
                $date = $this->getNextDate($date, $customer->collection_frequency, $customer->collection_day);
            }
            
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function getNextDate($date, $collection_frequency, $collection_day): Carbon
    {
        $numberDay = DaysConstants::collectionDayToNumber($collection_day);
        $numberWeek = FrequencyCollectionConstants::getWeekWithCollectionFrequency($collection_frequency);
        
        switch ($collection_frequency) {
            case FrequencyCollectionConstants::CADA_MES_PRIMERA_SEMANA:
            case FrequencyCollectionConstants::CADA_MES_SEGUNDA_SEMANA:
            case FrequencyCollectionConstants::CADA_MES_TERCERA_SEMANA:
            case FrequencyCollectionConstants::CADA_MES_CUARTA_SEMANA:
                $date->addMonth();
                $date->startOfMonth();
                $date->next($numberDay);
                $date->addWeeks($numberWeek - 1);
                break;
            case FrequencyCollectionConstants::CADA_DOS_SEMANAS:
                $date->next($numberDay);
                $date->next($numberDay);
                break;
            case FrequencyCollectionConstants::CADA_SEMANA:
                $date->next($numberDay);
                break;
            default:
                $date->addWeek();
                break;
        }
        
        return $date;
    }
}