<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebtOrderProduct extends Model
{
    protected $table = 'debts_order_products';

    protected $fillable = [
        'debt_id',
        'order_product_id',
        'refund_product_id',
        'type',
        'product_name',
        'product_price',
        'qty',
        'total'
    ];
}
