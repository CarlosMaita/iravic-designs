<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStockTransfer extends Model
{
    public $table = 'products_stock_transfer';

    public $fillable = [
        'product_id',
        'user_creator_id',
        'user_responsable_id',
        'qty',
        'stock'
    ];

    public $appends = [
        'date',
        'stock_column'
    ];

    # Relationships
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'user_creator_id', 'id');
    }

    public function responsable()
    {
        return $this->belongsTo('App\User', 'user_responsable_id', 'id');
    }
}
