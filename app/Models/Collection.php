<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{


    protected $fillable = [
        'date',
        'amount', 
        'number',
        'is_completed',
        'credit_id'
     ]; 

     public $appends = ['amount_formatted'];

    public function credit(){
        return $this->belongsTo(Credit::class);
    }

    
    # Retorna en formato moneda el total de la venta
    public function getAmountFormattedAttribute($value)
    {
        return $this->getAmountFormatted($value);
    }

    # Retorna monto en formato moneda
    public function getAmountFormatted($value)
    {
        return $value ? '$ ' . number_format($value, 2, '.', ',') : '$ 0,00';
    }

   

}
