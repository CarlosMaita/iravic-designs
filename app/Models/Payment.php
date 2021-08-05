<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $table = 'payments';

    public $fillable = [
        'box_id',
        'customer_id',
        'user_id',
        'amount',
        'date',
        'payed_bankwire',
        'payed_card',
        'payed_cash'
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

    # Accessors
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y h:m:s');
        }

        return null;
    }

    public function getAmountAttribute($value)
    {
        if ($value) {
            return '$ ' . number_format($value, 2, '.', ',');
        }

        return '$ 0,00';
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

        return '';
    }
}
