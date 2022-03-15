<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operations';
    
    protected $guarded = [];
    
    public $fillable = [
        'customer_id',
        'debt_id',
        'order_id',
        'payment_id',
        'refund_id',
        'balance',
        'created_at',
        'updated_at'
    ];

    public $appends = [
        'amount',
        'comment',
        'date',
        'type'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function debt()
    {
        return $this->belongsTo('App\Models\Debt');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function payment()
    {
        return $this->belongsTo('App\Models\Payment');
    }

    public function refund()
    {
        return $this->belongsTo('App\Models\Refund');
    }
    
    # Appends
    public function getAmountAttribute()
    {
        if ($this->debt_id) {
            return number_format($this->debt->amount, 2, ',', '.');
        } else if ($this->order_id) {
            return number_format($this->order->total_real, 2, ',', '.');
        } else if ($this->payment_id) {
            return number_format($this->payment->amount, 2, ',', '.');
        } else if ($this->refund_id) {
            return number_format($this->refund->total, 2, ',', '.');
        }

        return null;
    }

    public function getBalanceAttribute($value)
    {
        if ($value) {
            return number_format($value, 2, ',', '.');
        }

        return null;
    }

    public function getCommentAttribute()
    {
        if ($this->debt_id) {
            return $this->debt->comment;
        } else if ($this->payment_id) {
            return $this->payment->comment;
        } else if ($this->order && $this->order->refund) {
            return "A partir de devolución: #" . $this->order->refund_id;
        }

        return null;
    }

    public function getDateAttribute()
    {
        if ($this->created_at) {
            return Carbon::parse($this->created_at)->format('d-m-Y');
        }

        return null;
    }

    public function getTypeAttribute()
    {
        if ($this->debt_id) {
            return 'Deuda';
        } else if ($this->order_id) {
            return "Venta (" . $this->order->payment_method . ")";
        } else if ($this->payment_id) {
            return 'Pago';
        } else if ($this->refund_id) {
            return 'Devolución';
        }

        return null;
    }
}
