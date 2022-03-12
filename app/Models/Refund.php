<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refunds';
    
    public $fillable = [
        'box_id',
        'customer_id',
        'user_id',
        'date',
        'total',
        'total_refund_credit',
        'total_refund_debit'
    ];

    # Relationships
    public function box()
    {
        return $this->belongsTo('App\Models\Box');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer')->withTrashed();
    }

    public function order()
    {
        return $this->hasOne('App\Models\Order');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function products()
    {
        return $this->hasMany('App\Models\RefundProduct');
    }

    # Accessors
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y h:i:s');
        }

        return null;
    }
}
