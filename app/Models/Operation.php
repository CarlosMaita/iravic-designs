<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operations';
    
    protected $guarded = [];
    
    public $fillable = [
        'customer_id',
        'debt_id',
        'order_id',
        'payment_id',
        'refund_id',
        'date'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function debt()
    {
        return $this->belongsTo('App\Models\Debt');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function payment()
    {
        return $this->belongsTo('App\Models\Payment');
    }

    public function refund()
    {
        return $this->belongsTo('App\Models\Refund');
    }
}
