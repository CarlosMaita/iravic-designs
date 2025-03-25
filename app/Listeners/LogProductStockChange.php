<?php

namespace App\Listeners;

use App\Models\ProductStockHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogProductStockChange
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        ProductStockHistory::create([
            'product_id' => $event->productId,
            'store_id' => $event->storeId,
            'old_stock' => $event->oldStock,
            'new_stock' => $event->newStock,
            'qty' => $event->qty,
            'action' => $event->action,
            'user_id' => $event->userId,
            'product_stock_transfer_id' => $event->productStockTransferId
        ]);
    }
}
