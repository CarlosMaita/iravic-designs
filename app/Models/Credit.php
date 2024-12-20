<?php

namespace App;

use App\Models\Collection;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'quota', 
        'amount_quotas', 
        'start_date',
        'frequency',
        'total', 
        'status',
        'order_id',
        'customer_id',
     ]; 
     

     public $appends = ['total_formatted', 'quota_formatted'];

     public function order()
     {
         return $this->belongsTo(Order::class, 'order_id', 'id');
     }

     public function customer()
     {
            return $this->belongsTo(Customer::class, 'customer_id', 'id');
     }

     public function collections()
     {
         return $this->hasMany(Collection::class);
     }
 
     public function payments(){
         return $this->hasMany(Payment::class, 'collection_id', 'id');
     }
 
     # Retorna en formato moneda el total de la venta
     public function getQuotaFormattedAttribute($value)
     {
         return $this->getAmountFormatted($value);
     }
 
     
      # Retorna en formato moneda el total de la venta
      public function getTotalFormattedAttribute()
      {
          return $this->getAmountFormatted($this->total);
      }
     
 
     # Returns the sale amount in currency format
         public function formatCurrency($amount)
    {
        return $amount ? '$ ' . number_format($amount, 2, '.', ',') : '$ 0.00';
    }

}
