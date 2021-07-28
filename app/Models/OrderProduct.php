<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'orders_products';
    protected $guarded = [];
    public $fillable = [
        'order_id',
        'product_id',
        'color_id',
        'size_id',
        'product_name',
        'qty',
        'total'
    ];

    # Relationships
    public function color()
    {
        return $this->belongsTo('App\Models\Color');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
