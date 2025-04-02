<?php

namespace App\Models;

use App\Helpers\FormatHelper;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'orders_products';
    
    public $fillable = [
        'color_id',
        'order_id',
        'product_id',
        'store_id',
        'product_name',
        'product_price',
        'qty',
        'size_id',
        'stock_type',
        'total'
    ];

    public $appends = [
        'available_for_refund',
        'is_by_credit',
        'product_price_str',
    ];

    # Boot
    public static function boot()
    {
        parent::boot();

        # Cada ve que se compra un producto, se descuenta la cantidad del stock asociado al deposito seleccionado en la venta
        OrderProduct::saved(function($order_product) {
            $qty = $order_product->qty;
            $order_product->product
                ->subtractStock(
                    $order_product->store_id,
                    $qty,
                    'venta #' . $order_product->id,
                    $order_product->id
                );
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

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id', 'id');
    }

    # Accessors
    # Modifica el total del producto en formato moneda
    public function getTotalAttribute($value)
    {
        return FormatHelper::formatCurrency($value);
    }

    # Appends
    # Retorna la cantidad disponible para devolucion del producto comprado
    public function getAvailableForRefundAttribute()
    {
        $qty_refunded = $this->refunds_products()->sum('qty');
        return $this->qty - $qty_refunded;
    }

    # Retorna si el venta fue por credito
    public function getIsByCreditAttribute()
    {
        return $this->order->payed_credit;
    }

    # Retorna en formato moneda el precio del producto comprado
    public function getProductPriceStrAttribute()
    {
        return FormatHelper::formatCurrency($this->product_price);
    }
}
