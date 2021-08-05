<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStockHistory extends Model
{
    public $table = 'products_stock_history';
    public $fillable = [
        'product_id',
        'order_product_id',
        'user_id',
        'new_stock',
        'old_stock',
        'order_product_qty',
        'stock'
    ];

    # Relationships
    public function order_product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

}
