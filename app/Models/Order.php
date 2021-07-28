<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $guarded = [];
    public $fillable = [
        'box_id',
        'customer_id',
        'user_id',
        'date',
        'total',
        'payed_bankwire',
        'payed_card',
        'payed_cash',
        'payed_credit'
    ];

    # Relationships
    public function box()
    {
        return $this->belongsTo('App\Models\Box');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct');
    }

    # Accessors
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y h:m:s');
        }

        return null;
    }

    public function getTotalAttribute($value)
    {
        if ($value) {
            return '$ ' . number_format($value, 2, '.', ',');
        }

        return '$ 0,00';
    }
}
