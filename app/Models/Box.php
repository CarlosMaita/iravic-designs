<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $table = 'boxes';

    public $fillable = [
        'user_id',
        'cash_initial',
        'closed',
        'date',
        'date_start',
        'date_end'
    ];

    public $appends = [
        'total_bankwire',
        'total_card',
        'total_cash',
        'total_credit',
        'total_payed'
    ];

    # Relationships
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    # Accessors
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y');
        }

        return null;
    }

    public function getDateStartAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y h:m:s');
        }

        return null;
    }

    public function getDateEndAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y h:m:s');
        }

        return null;
    }

    # Appends
    public function getTotalBankwireAttribute()
    {
        $total = $this->getTotalByPaymentMethod('bankwire');
        return '$ ' . number_format($total, 2, '.', ',');
    }

    public function getTotalCardAttribute()
    {
        $total = $this->getTotalByPaymentMethod('card');
        return '$ ' . number_format($total, 2, '.', ',');
    }

    public function getTotalCashAttribute()
    {
        $total = $this->getTotalByPaymentMethod('cash');
        return '$ ' . number_format($total, 2, '.', ',');
    }
    
    public function getTotalCreditAttribute()
    {
        $total = $this->getTotalByPaymentMethod('credit');
        return '$ ' . number_format($total, 2, '.', ',');
    }

    public function getTotalPayedAttribute()
    {
        $total = $this->getTotalPayed();
        return '$ ' . number_format($total, 2, '.', ',');
    }

    # Methods
    public function getTotalPayed()
    {
        return $this->getTotalByPaymentMethod();
    }

    public function getTotalByPaymentMethod($payment_method = null)
    {
        $orders = $this->orders();
        
        if ($payment_method) {
            $ordes = $orders->where('payed_' . $payment_method, 1);
        }

        return $orders->sum('total');
    }
}
