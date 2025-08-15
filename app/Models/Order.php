<?php

namespace App\Models;

use App\Helpers\FormatHelper;
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

    public function collection(){

        return $this->hasOne('App\Models\Collection');
    }

    # Boot
    protected static function boot()
    {
        parent::boot();

        # Cada vez que se crea una venta, se crea un registro de Operacion con el saldo resultante del cliente
        self::created(function ($model) {
            Operation::create([
                'customer_id' => $model->customer_id,
                'order_id' => $model->id,
                'balance' => $model->customer->getBalance()
            ]);
        });
    }

    # Accessors
    # Modifica la fecha en formato d-m-Y h:i:s
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y h:i:s');
        }

        return null;
    }

    # Retorna en formato moneda el descuento aplicado en la venta
    public function getDiscountAttribute($value)
    {
        return FormatHelper::formatCurrency($value);
    }

    # Retorna en formato moneda el subtotal de la venta
    public function getSubtotalAttribute($value)
    {
        return FormatHelper::formatCurrency($value);
    }

    # Retorna en formato moneda el total de la venta
    public function getTotalAttribute($value)
    {
        return FormatHelper::formatCurrency($value);
    }

    # Appends
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

        if ($this->payed_credit) {
            return 'Crédito';
        }

        return '';
    }

   
}
