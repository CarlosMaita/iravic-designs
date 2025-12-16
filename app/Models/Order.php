<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;

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
        'archived',
        'exchange_rate',
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
        'archived' => 'boolean',
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

    public function scopeNotArchived($query)
    {
        return $query->where('archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('archived', true);
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

    /**
     * Archive the order
     */
    public function archive()
    {
        $this->archived = true;
        $this->save();
        return true;
    }

    /**
     * Unarchive the order
     */
    public function unarchive()
    {
        $this->archived = false;
        $this->save();
        return true;
    }

    /**
     * Check if order can be edited (not archived)
     */
    public function canBeEdited()
    {
        return !$this->archived;
    }

    /**
     * Get the exchange rate to use for this order
     * - Use stored rate if order is paid/shipped/completed
     * - Use current rate if order is not yet paid
     *
     * @return float
     */
    public function getEffectiveExchangeRate(): float
    {
        if ($this->isPaid() && $this->exchange_rate) {
            return $this->exchange_rate;
        }
        
        return app(\App\Services\ExchangeRateService::class)->getCurrentRate();
    }

    /**
     * Check if order is paid or beyond
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return in_array($this->status, [self::STATUS_PAID, self::STATUS_SHIPPED, self::STATUS_COMPLETED]);
    }

    /**
     * Get the total in VES using the effective exchange rate
     *
     * @return float
     */
    public function getTotalInVES(): float
    {
        return $this->total * $this->getEffectiveExchangeRate();
    }

    /**
     * Get both USD and VES amounts for display
     *
     * @return array
     */
    public function getPricesBothCurrencies(): array
    {
        $vesAmount = $this->getTotalInVES();
        
        return [
            'usd' => [
                'amount' => $this->total,
                'formatted' => '$' . number_format($this->total, 2),
                'currency' => 'USD'
            ],
            'ves' => [
                'amount' => $vesAmount,
                'formatted' => 'Bs. ' . number_format($vesAmount, 2, ',', '.'),
                'currency' => 'VES'
            ],
            'exchange_rate' => $this->getEffectiveExchangeRate(),
            'rate_source' => $this->isPaid() && $this->exchange_rate ? 'stored' : 'current'
        ];
    }
}