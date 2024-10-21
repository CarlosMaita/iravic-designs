<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{


    protected $fillable = [
        'order_id',
        'quota', 
        'amount_quotas', 
        'frequency',
        'start_date',
        'total', 
        'paid', 
        'balance', 
        'status', 
        'is_overdue'
     ];


    # Retorna en formato moneda el total de la venta
    public function getQuotaAttribute($value)
    {
        return $this->getAmountFormated($value);
    }

    # Retorna en formato moneda el total de la venta
    public function getBalanceAttribute($value)
    {
        return $this->getAmountFormated($value);
    }
    

    # Retorna monto de la venta en formato moneda
    public function getAmountFormated($value)
    {
        if ($value) {
            return '$ ' . number_format($value, 2, '.', ',');
        }

        return '$ 0,00';
    }

    // link with order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
