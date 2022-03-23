<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    public $table = 'debts';

    public $fillable = [
        'box_id',
        'customer_id',
        'order_product_id',
        'user_id',
        'amount',
        'comment',
        'date'
    ];

    public $appends = [
        'amount_str'
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

    public function operation()
    {
        return $this->hasOne('App\Models\Operation');
    }

    public function order_product()
    {
        return $this->belongsTo('App\Models\OrderProduct');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    # Boot
    protected static function boot()
    {
        parent::boot();

        # Cada vez que se crea una deuda, se crea un registro de Operacion con el saldo resultante del cliente
        self::created(function ($model) {
            Operation::create([
                'customer_id' => $model->customer_id,
                'debt_id' => $model->id,
                'balance' => $model->customer->getBalance()
            ]);
        });
    }

    # Accessors
    /**
     * Modifica la fecha de la deuda
     */
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y h:i:s');
        }

        return null;
    }

    # Appends
    /**
     * Retorna en formato moneda el monto de la deuda
     */
    public function getAmountStrAttribute()
    {
        return '$ ' . number_format($this->amount, 2, '.', ',');
    }
}
