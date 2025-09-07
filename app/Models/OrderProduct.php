<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'orders_products';

    protected $fillable = [
        'order_id',
        'product_id',
        'color_id',
        'size_id',
        'store_id',
        'product_name',
        'product_price',
        'qty',
        'stock_type',
        'total',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}