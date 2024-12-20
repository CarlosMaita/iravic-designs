<?php

namespace App\Models;

use App\Credit;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Integer;

class Collection extends Model
{


    protected $fillable = [
        'date',
        'amount', 
        'is_completed'
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
