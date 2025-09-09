<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'date',
        'total',
        'subtotal',
        'discount',
        'status',
        'shipping_name',
        'shipping_dni',
        'shipping_phone',
        'shipping_agency',
        'shipping_address',
        'shipping_tracking_number',
        'payed_bankwire',
        'payed_card',
        'payed_cash',
        'payed_credit',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // Order status constants
    const STATUS_CREATED = 'creada';
    const STATUS_PAID = 'pagada';
    const STATUS_SHIPPED = 'enviada';
    const STATUS_COMPLETED = 'completada';
    const STATUS_CANCELLED = 'cancelada';

    // Shipping agencies
    const SHIPPING_AGENCIES = ['MRW', 'ZOOM', 'Domesa'];

    public static function getStatuses()
    {
        return [
            self::STATUS_CREATED => 'Creada',
            self::STATUS_PAID => 'Pagada',
            self::STATUS_SHIPPED => 'Enviada',
            self::STATUS_COMPLETED => 'Completada',
            self::STATUS_CANCELLED => 'Cancelada',
        ];
    }

    public static function getShippingAgencies()
    {
        return self::SHIPPING_AGENCIES;
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', Payment::STATUS_VERIFIED)->get()->sum('equivalent_usd_amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total - $this->total_paid;
    }

    public function getIsFullyPaidAttribute()
    {
        return $this->remaining_balance <= 0;
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    // Methods
    public function canBePaid()
    {
        return $this->status === self::STATUS_CREATED;
    }

    public function canBeShipped()
    {
        return $this->status === self::STATUS_PAID;
    }

    public function canBeCompleted()
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    public function canBeCancelled()
    {
        return $this->status === self::STATUS_CREATED;
    }

    public function cancel()
    {
        if ($this->canBeCancelled()) {
            $this->status = self::STATUS_CANCELLED;
            $this->save();
            return true;
        }
        return false;
    }

    public function updateStatus($newStatus)
    {
        if (in_array($newStatus, array_keys(self::getStatuses()))) {
            $this->status = $newStatus;
            $this->save();
            return true;
        }
        return false;
    }
}