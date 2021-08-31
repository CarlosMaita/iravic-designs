<?php

namespace App\Models;

use Carbon\Carbon;
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
        'qty',
        'stock'
    ];

    public $appends = [
        'action',
        'date',
        'stock_column'
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

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    # Appends
    public function getActionAttribute()
    {
        return $this->order_product ? 'Venta' : 'Actualización producto';
    }

    public function getDateAttribute()
    {
        if ($this->created_at) {
            return Carbon::parse($this->created_at)->format('d-m-Y h:i:s');
        }

        return '';
    }

    public function getStockColumnAttribute()
    {
        $stock = '';

        switch ($this->stock) {
            case 'stock_depot':
                $stock = 'Depósito';
                break;

            case 'stock_local':
                $stock = 'Local';
                break;

            case 'stock_truck':
                $stock = 'Camión';
                break;
            
            default:
                break;
        }

        return $stock;
    }

    # Scopes
    public function scopeWhereProduct($query, $product_id)
    {
        return $query->where('product_id', $product_id);
    }

    public function scopeWhereStock($query, $stock_column)
    {
        return $query->where('stock', $stock_column);
    }
}
