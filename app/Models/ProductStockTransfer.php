<?php

namespace App\Models;

use App\Events\ProductStockChanged;
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

                $transferQuantity = $product_stock_transfer->qty;

                $originStore = Store::find($product_stock_transfer->stock_origin);
                $destinationStore = Store::find($product_stock_transfer->stock_destination);

                $oldOriginStock = $product->stores()
                    ->where('store_id', $originStore->id)
                    ->first()->pivot->stock;
                $oldDestinationStock = $product->stores()
                    ->where('store_id', $destinationStore->id)
                    ->first()->pivot->stock;

                $newOriginStock = $oldOriginStock - $transferQuantity;
                $newDestinationStock = $oldDestinationStock + $transferQuantity;

                $product->stores()->updateExistingPivot( $originStore->id, ['stock' => $newOriginStock]);
                $product->stores()->updateExistingPivot($destinationStore->id, ['stock' => $newDestinationStock]);

                // Evento de Transferencia Origen
               event(new ProductStockChanged(
                       $product->id,
                       $originStore->id,
                       $oldOriginStock,
                       $newOriginStock,
                       $transferQuantity,
                       'Transferencia hacia ' . $destinationStore->name,
                       auth()->id(), 
                       $product_stock_transfer->id)
                   );

                // Evento de Transferencia destino
                event(new ProductStockChanged(
                        $product->id,
                        $destinationStore->id,
                        $oldDestinationStock,
                        $newDestinationStock,
                        $transferQuantity,
                        'Transferencia desde ' . $originStore->name,
                        auth()->id(), 
                        $product_stock_transfer->id)
                    );



                // $product->addStockHistoryRecord(
                //     $user->id,
                //     'Transferencia hacia ' . $destinationStore->name,
                //     $newOriginStock,
                //     $oldOriginStock,
                //     $transferQuantity,
                //     $originStore->name,
                //     null,
                //     $product_stock_transfer->id
                // );

                // $product->addStockHistoryRecord(
                //     $user->id,
                //     'Transferencia desde ' . $originStore->name,
                //     $newDestinationStock,
                //     $oldDestinationStock,
                //     $transferQuantity,
                //     $destinationStore->name,
                //     null,
                //     $product_stock_transfer->id
                // );
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
    /**
     * Retorna la fecha en formato d-m-Y h:i:s
     */
    public function getDateAttribute()
    {
        if ($this->created_at) {
            return Carbon::parse($this->created_at)->format('d-m-Y H:i:s');
        }

        return '';
    }

    /**
     * Retorna nombre del stock origen de la transferencia
     */
    public function getStockNameOriginAttribute()
    {
        $store = Store::find($this->stock_origin);
        return $store->name;
    }

    /**
     * Retorna nombre del stock destino de la transferencia
     */
    public function getStockNameDestinationAttribute()
    {
        $store = Store::find($this->stock_destination);
        return $store->name;
    }

    # Scopes
    public function scopeWhereUserStock($query, $user_id, $stock)
    {
        return $query->where(function($q) use ($user_id, $stock){
            $q->where(function($q) use ($user_id) {
                $q->where('user_creator_id', $user_id)
                    ->orWhere('user_responsable_id', $user_id);
            })->orWhere(function($q) use ($stock) {
                $q->where('is_accepted', 0)
                    ->where('stock_destination', $stock);
            });
        });
    }
}
