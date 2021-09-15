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
        'refund_id',
        'user_id',
        'date',
        'discount',
        'subtotal',
        'total',
        'payed_bankwire',
        'payed_card',
        'payed_cash',
        'payed_credit'
    ];

    public $appends = [
        'payment_method'
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
            return Carbon::parse($value)->format('d-m-Y h:i:s');
        }

        return null;
    }

    public function getDiscountAttribute($value)
    {
        return $this->getAmountFormated($value);
    }

    public function getSubtotalAttribute($value)
    {
        return $this->getAmountFormated($value);
    }

    public function getTotalAttribute($value)
    {
        return $this->getAmountFormated($value);
    }

    # Appends
    public function getPaymentMethodAttribute()
    {
        if ($this->payed_bankwire) {
            return 'Transferencia';
        }

        if ($this->payed_card) {
            return 'Tarjeta';
        }

        if ($this->payed_cash) {
            return 'Efectivo';
        }

        if ($this->payed_credit) {
            return 'Crédito';
        }

        return '';
    }

    # Methods
    public function getAmountFormated($value)
    {
        if ($value) {
            return '$ ' . number_format($value, 2, '.', ',');
        }

        return '$ 0,00';
    }
}
