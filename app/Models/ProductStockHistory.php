<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductStockHistory extends Model
{
    public $table = 'products_stock_history';
    
    public $fillable = [
        'order_product_id',
        'product_id',
        'product_stock_transfer_id',
        'refund_product_id',
        'user_id',
        'action',
        'new_stock',
        'old_stock',
        'qty',
        'store_id'
    ];

    public $appends = [
        'date',
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
    public function getDateAttribute()
    {
        if ($this->created_at) {
            return Carbon::parse($this->created_at)->format('d-m-Y h:i');
        }

        return '';
    }

    # Scopes
    public function scopeWhereProduct($query, $product_id)
    {
        return $query->where('product_id', $product_id);
    }
}
