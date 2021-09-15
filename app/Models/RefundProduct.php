<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundProduct extends Model
{
    protected $table = 'refunds_products';

    public $fillable = [
        'color_id',
        'refund_id',
        'order_product_id',
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

        RefundProduct::saved(function($refund_product) {
            $qty = $refund_product->qty;
            $refund_product->product->addStockUser($refund_product->id, $qty, 'Devolución (# ' . $refund_product->refund_id . ') - Pedido #' . $refund_product->order_product->order_id . ' ');
        });
    }

    # Relationships
    public function color()
    {
        return $this->belongsTo('App\Models\Color');
    }

    public function order_product()
    {
        return $this->belongsTo('App\Models\OrderProduct');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product')->withTrashed();
    }

    public function refund()
    {
        return $this->belongsTo('App\Models\Refund');
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
