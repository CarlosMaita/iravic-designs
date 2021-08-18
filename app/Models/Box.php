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
        'total_cash_in_box',
        'total_credit',
        'total_payed'
    ];

    # Relationships
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
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

    # Scopes
    public function scopeWhereUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
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

    public function getTotalCashInBoxAttribute()
    {
        $total = $this->getTotalByPaymentMethod('cash');
        $total += $this->getOriginal('cash_initial');
        return '$ ' . number_format($total, 2, '.', ','); 
    }
    
    public function getTotalCreditAttribute()
    {
        $total = $this->getTotalOrdersByPaymentMethod('credit');
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
        $total_orders = $this->getTotalOrdersByPaymentMethod($payment_method);
        $total_payments = $payment_method != 'credit' ? $this->getTotalPaymentsByPaymentMethod($payment_method) : 0;
        return ($total_orders + $total_payments);
    }

    public function getTotalOrdersByPaymentMethod($payment_method = null)
    {
        $orders = $this->orders();
        
        if ($payment_method) {
            $orders = $orders->where('payed_' . $payment_method, 1);
        }

        return $orders->sum('total');
    }

    public function getTotalPaymentsByPaymentMethod($payment_method = null)
    {
        $payments = $this->payments();
        
        if ($payment_method) {
            $payments = $payments->where('payed_' . $payment_method, 1);
        }

        return $payments->sum('amount');
    }
}
