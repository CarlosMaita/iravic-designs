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

}
