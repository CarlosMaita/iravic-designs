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
        'total_payed',
        'total_refunded',
        'total_spent',
        'total_final_sales'
    ];

    # Relationships
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function refunds()
    {
        return $this->hasMany('App\Models\Refund');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
    }

    public function spendings()
    {
        return $this->hasMany('App\Models\Spending');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    # Scopes
    public function scopeWhereUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    # Accessors
    /**
     * Modifica la fecha en formato d-m-Y
     */
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y');
        }

        return null;
    }

    /**
     * Modifica la fecha de inicio en fomato d-m-Y h:i:s
     */
    public function getDateStartAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }

        return null;
    }

    /**
     * Modifica la fecha de fin en fomato d-m-Y h:i:s
     */
    public function getDateEndAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }

        return null;
    }

    # Appends
    /**
     * Retorna en formato moneda, calculo de diferencia entre vendido y pagado por metodo de pago Transferencia Bancaria
     */
    public function getTotalBankwireAttribute()
    {
        $total = $this->getTotalByPaymentMethod('bankwire');
        return '$ ' . number_format($total, 2, '.', ',');
    }

    /**
     * Retorna en formato moneda, calculo de diferencia entre vendido y pagado por metodo de pago Tarjeta
     */
    public function getTotalCardAttribute()
    {
        $total = $this->getTotalByPaymentMethod('card');
        return '$ ' . number_format($total, 2, '.', ',');
    }

    /**
     * Retorna en formato moneda, calculo de diferencia entre vendido y pagado por metodo de pago Efectivo
     */
    public function getTotalCashAttribute()
    {
        $total = $this->getTotalByPaymentMethod('cash');
        return '$ ' . number_format($total, 2, '.', ',');
    }

    /**
     * Retorna en formato moneda, el total de efectivo luego de descontar gastos 
     * Se toma en cuenta el efectivo inicial
     */
    public function getTotalCashInBoxAttribute()
    {
        $total = $this->getTotalByPaymentMethod('cash');
        $total += $this->getOriginal('cash_initial');
        $total -= $this->getTotalSpent();
        return '$ ' . number_format($total, 2, '.', ','); 
    }
    
    /**
     * Retorn en formato moneda, 
     */
    public function getTotalCreditAttribute()
    {
        $total = $this->getTotalOrdersByPaymentMethod('credit');
        return '$ ' . number_format($total, 2, '.', ',');
    }

    /**
     * Retorna en formato moneda, total pagado
     */
    public function getTotalPayedAttribute()
    {
        $total = $this->getTotalPayed();
        return '$ ' . number_format($total, 2, '.', ',');
    }
    
    /**
     * Retorna en formato moneda, total devuelto
     */
    public function getTotalRefundedAttribute()
    {
        $total = $this->getTotalRefunded();
        return '$ ' . number_format($total, 2, '.', ',');
    }

    /**
     * Retorna en formato moneda, total gastado
     */
    public function getTotalSpentAttribute()
    {
        $total = $this->getTotalSpent();
        return '$ ' . number_format($total, 2, '.', ',');
    }

    /**
     * Retorna en formato moneda, calculo de total pagado - total devuelto
     */
    public function getTotalFinalSalesAttribute()
    {
        $total_payed = $this->getTotalPayed();
        $total_refunded = $this->getTotalRefunded();
        $total = $total_payed - $total_refunded;
        return '$ ' . number_format($total, 2, '.', ',');
    }

    # Methods
    /**
     * Retorna en formato numerico, calculo de diferencia entre vendido y pagado sin tomar en cuenta ningun metodo de pago
     */
    public function getTotalPayed()
    {
        return $this->getTotalByPaymentMethod();
    }

    /**
     * Retorna en formato numerico, calculo de diferencia entre vendido y pagado por metodo de pago (Opcional) o en general
     */
    public function getTotalByPaymentMethod($payment_method = null)
    {
        $total_orders = $this->getTotalOrdersByPaymentMethod($payment_method);
        $total_payments = $payment_method != 'credit' ? $this->getTotalPaymentsByPaymentMethod($payment_method) : 0;
        return ($total_orders + $total_payments);
    }

    /**
     * Retorna en formato numerico, total vendido, por metodo de pago (Opcional) o en general
     */
    public function getTotalOrdersByPaymentMethod($payment_method = null)
    {
        $orders = $this->orders();
        
        if ($payment_method) {
            $orders = $orders->where('payed_' . $payment_method, 1);
        }

        return $orders->sum('total');
    }

    /**
     * Retorna en formato numerico, total pagado por metodo especifico
     */
    public function getTotalPaymentsByPaymentMethod($payment_method = null)
    {
        $payments = $this->payments();
        
        if ($payment_method) {
            $payments = $payments->where('payed_' . $payment_method, 1);
        }

        return $payments->sum('amount');
    }

    /**
     * Retorna en formato numerico, total devuelto
     */
    public function getTotalRefunded()
    {
        return $this->refunds()->sum('total');
    }

    /**
     * Retorna en formato numerico, total gastado
     */
    public function getTotalSpent()
    {
        return $this->spendings()->sum('amount');
    }

    /**
     * Retorna 
     * true, si la caja esta cerrada
     * fasle, si esta abierta
     */
    public function isClosed() : bool
    {
        return  $this->closed == 1 ? true : false;
    }
}
