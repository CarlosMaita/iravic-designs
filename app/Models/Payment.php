<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'user_id',
        'date',
        'amount',
        'status',
        'payment_method',
        'reference_number',
        'mobile_payment_date',
        'comment',
        'payed_bankwire',
        'payed_card',
        'payed_cash',
    ];

    protected $casts = [
        'date' => 'datetime',
        'mobile_payment_date' => 'datetime',
    ];

    // Payment status constants
    const STATUS_PENDING = 'pendiente';
    const STATUS_VERIFIED = 'verificado';
    const STATUS_REJECTED = 'rechazado';

    // Payment methods
    const METHOD_MOBILE = 'pago_movil';
    const METHOD_TRANSFER = 'transferencia';
    const METHOD_CASH = 'efectivo';
    const METHOD_CARD = 'tarjeta';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_VERIFIED => 'Verificado',
            self::STATUS_REJECTED => 'Rechazado',
        ];
    }

    public static function getPaymentMethods()
    {
        return [
            self::METHOD_MOBILE => 'Pago Móvil',
            self::METHOD_TRANSFER => 'Transferencia',
            self::METHOD_CASH => 'Efectivo',
            self::METHOD_CARD => 'Tarjeta',
        ];
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getPaymentMethodLabelAttribute()
    {
        return self::getPaymentMethods()[$this->payment_method] ?? $this->payment_method;
    }

    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    public function getReferenceDigitsAttribute()
    {
        return $this->reference_number ? substr($this->reference_number, 0, 6) : null;
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // Methods
    public function canBeVerified()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeRejected()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function verify()
    {
        if ($this->canBeVerified()) {
            $this->status = self::STATUS_VERIFIED;
            $this->save();

            // Check if order is fully paid and update status
            if ($this->order && $this->order->is_fully_paid && $this->order->status === Order::STATUS_CREATED) {
                $this->order->updateStatus(Order::STATUS_PAID);
            }

            return true;
        }
        return false;
    }

    public function reject()
    {
        if ($this->canBeRejected()) {
            $this->status = self::STATUS_REJECTED;
            $this->save();
            return true;
        }
        return false;
    }
}