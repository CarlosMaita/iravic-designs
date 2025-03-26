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
    /**
     * Retorna el total de la operacion, validando si fue devolucion, deuda, pago o venta
     */
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

    # Retorna en formato moneda el balance con el que quedo el cliente
    public function getBalanceAttribute($value)
    {
        if ($value) {
            return number_format($value, 2, ',', '.');
        }

        return null;
    }

    /**
     * Retorna comentario de la operacion, validando si fue devolucion, deuda, pago o venta
     * Si es venta o devolucion, se listan los productos
     */
    public function getCommentAttribute()
    {
        if ($this->debt_id) {
            return $this->debt->comment;
        } else if ($this->payment_id) {
            return $this->payment->comment;
        } else if ($this->order_id) {
            $comment = array();
            $products = $this->order->products;
            foreach ($products as $product) {
                $product_name = $product->product->name;

                if ($product->qty > 1) {
                    $product_name .= " ($product->qty)";
                }

                array_push($comment, $product_name);
            }
            
            return $comment;
        } else if ($this->refund_id) {
            $comment = array();
            $products = $this->refund->products;
            foreach ($products as $product) {
                $product_name = $product->product->name;

                if ($product->qty > 1) {
                    $product_name .= " ($product->qty)";
                }

                array_push($comment, $product_name);
            }

            return $comment;
        }

        return null;
    }

    /**
     * Retorna la fecha en formato d-m-Y
     */
    public function getDateAttribute()
    {
        if ($this->created_at) {
            return Carbon::parse($this->created_at)->format('d-m-Y');
        }

        return null;
    }

    /**
     * Retorna tipo de operacion
     */
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

    /**
     * Retorna la url de recurso asociado a la operacion. Solo considera ventas o devoluciones.
     */
    public function getResourceRoute()
    {
        if ($this->order_id) {
            return route('ventas.show', $this->order_id);
        } else if ($this->refund_id) {
            return route('devoluciones.show', $this->refund_id);
        }

        return null;
    }

    /**
     * Retorna modelo del recurso asociado, para ser usado en el listado de operaciones, validando sus permisos por policy
     */
    public function getResourceForPolicy()
    {
        if ($this->order_id) {
            return $this->order;
        } else if ($this->refund_id) {
            return $this->refund;
        }

        return null;
    }
}
