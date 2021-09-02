<?php

namespace App\Models;

use App\Services\Catalog\StockService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductStockTransfer extends Model
{
    public $table = 'products_stock_transfer';

    public $fillable = [
        'product_id',
        'user_creator_id',
        'user_responsable_id',
        'is_accepted',
        'qty',
        'stock_origin',
        'stock_destination'
    ];

    public $appends = [
        'date',
        'stock_name_origin',
        'stock_name_destination'
    ];

    # Boot
    public static function boot()
    {
        parent::boot();

        static::saved(function ($product_stock_transfer) {
            if ($product_stock_transfer->isDirty('is_accepted') && $product_stock_transfer->is_accepted) {
                $user = Auth::user();
                $product = Product::withoutEvents(function () use ($product_stock_transfer) {
                    $product = Product::find($product_stock_transfer->product_id);
                    return $product;
                });

                $qty = $product_stock_transfer->qty;
                $stock_name_origin = $product_stock_transfer->stock_origin;
                $stock_name_destination = $product_stock_transfer->stock_destination;
                $old_stock_origin = $product->$stock_name_origin;
                $old_stock_destination = $product->$stock_name_destination;
                $new_stock_origin = ($old_stock_origin - $qty);
                $new_stock_destination = ($old_stock_destination + $qty);

                $product->$stock_name_origin = $new_stock_origin;
                $product->$stock_name_destination = $new_stock_destination;
                $product->save();

                $product->addStockHistoryRecord($user->id, 'Transferencia hacia ' . $new_stock_destination, $new_stock_origin, $old_stock_origin, $qty, $stock_name_origin);
                $product->addStockHistoryRecord($user->id, 'Transferencia desde ' . $stock_name_origin, $new_stock_destination, $old_stock_destination, $qty, $stock_name_destination);
            }
        });
    }

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

    # Appends
    public function getDateAttribute()
    {
        if ($this->created_at) {
            return Carbon::parse($this->created_at)->format('d-m-Y h:i:s');
        }

        return '';
    }

    public function getStockNameOriginAttribute()
    {
        return StockService::getStockName($this->stock_origin);
    }

    public function getStockNameDestinationAttribute()
    {
        return StockService::getStockName($this->stock_destination);
    }
}
