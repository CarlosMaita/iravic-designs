<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductStockChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $productId;
    public $storeId;
    public $oldStock;
    public $newStock;
    public $qty;
    public $action;
    public $userId;
    public $productStockTransferId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
   public function __construct($productId, $storeId, $oldStock, $newStock, $qty, $action, $userId = null, $productStockTransferId = null)
    {
        $this->productId = $productId;
        $this->storeId = $storeId;
        $this->oldStock = $oldStock;
        $this->newStock = $newStock;
        $this->qty = $qty;
        $this->action = $action;
        $this->userId = $userId;
        $this->productStockTransferId = $productStockTransferId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
