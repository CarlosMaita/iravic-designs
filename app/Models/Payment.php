<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $table = 'payments';

    public $fillable = [
        'customer_id',
        'refund_id',
        'user_id',
        'amount',
        'comment',
        'date',
        'payed_bankwire',
        'payed_card',
        'payed_cash'
    ];

    public $appends = [
        'amount_str',
        'payment_method',
        'payment_selected'
    ];

    # Relationships
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
    
    public function operation()
    {
        return $this->hasOne('App\Models\Operation');
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

        # Cada vez que se crea una pago, se crea un registro de Operacion con el saldo resultante del cliente
        self::created(function ($model) {
            Operation::create([
                'customer_id' => $model->customer_id,
                'payment_id' => $model->id,
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

    # Appends
    # Retorna en formato moneda el monto del pago
    public function getAmountStrAttribute()
    {
        return '$ ' . number_format($this->amount, 2, '.', ',');
    }

    # Retorna nombre en espanol del metodo de pago
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

    # Retorna display name de metodo de pago
    public function getPaymentSelectedAttribute()
    {
        if ($this->payed_bankwire) {
            return 'bankwire';
        }

        if ($this->payed_card) {
            return 'card';
        }

        if ($this->payed_cash) {
            return 'cash';
        }

        return null;
    }
}
