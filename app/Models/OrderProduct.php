<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'orders_products';
    
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

    public $appends = [
        'available_for_refund',
        'product_price_str',
    ];

    # Boot
    public static function boot()
    {
        parent::boot();

        OrderProduct::saved(function($order_product) {
            $qty = $order_product->qty;
            $order_product->product->subtractStockUser($order_product->id, $qty, 'Pedido');
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
    
    public function refunds_products()
    {
        return $this->hasMany('App\Models\RefundProduct');
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

    # Appends
    public function getAvailableForRefundAttribute()
    {
        $qty_refunded = $this->refunds_products()->sum('qty');
        return $this->qty - $qty_refunded;
    }

    
    public function getProductPriceStrAttribute()
    {
        return '$ ' . number_format($this->product_price, 2, '.', ',');
    }
}
