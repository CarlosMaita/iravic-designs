<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'orders_products';
    protected $guarded = [];
    public $fillable = [
        'color_id',
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'qty',
        'size_id',
        'stock_type',
        'total'
    ];

    # Boot
    public static function boot()
    {
        parent::boot();

        OrderProduct::saved(function($order_product) {
            $qty = $order_product->qty;
            $order_product->product->subtractStockUser($order_product->id, $qty);
        });
    }

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
        return $this->belongsTo('App\Models\Product')->withTrashed();
    }
    
    public function size()
    {
        return $this->belongsTo('App\Models\Size');
    }

    # Accessors
    public function getTotalAttribute($value)
    {
        return '$ ' . number_format($value, 2, '.', ',');
    }
}
