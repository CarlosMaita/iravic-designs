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
        'is_credit_shared',
        'total_refund_credit',
        'total_refund_debit',
        'discount',
        'subtotal',
        'total',
        'total_real',
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
        return $this->belongsTo('App\Models\Customer')->withTrashed();
    }

    public function operation()
    {
        return $this->hasOne('App\Models\Operation');
    }

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct');
    }

    public function refund()
    {
        return $this->belongsTo('App\Models\Refund');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    # Boot
    protected static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            Operation::create([
                'customer_id' => $model->customer_id,
                'order_id' => $model->id,
                'balance' => $model->customer->getBalance()
            ]);
        });
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
