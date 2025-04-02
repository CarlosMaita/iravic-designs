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

        # Cada vez que se devuelve un producto, se agrega la cantidad de vuelta al tipo de Stock
        RefundProduct::saved(function($refund_product) {
            $quantity = $refund_product->qty;
            $refund_product->product
                ->rollbackStockUser(
                    $refund_product->order_product->store_id,
                    $quantity,
                    'Devolución (# ' . $refund_product->refund_id . ') - venta #' . $refund_product->order_product->order_id . ' ', 
                    $refund_product->id, 
                );
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
    /**
     * Modifica en formato moneda, el costo total del producto
     */
    public function getTotalAttribute($value)
    {
        return '$ ' . number_format($value, 2, '.', ',');
    }
}
