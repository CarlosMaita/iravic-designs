<?php

namespace App\Models;

use App\Helpers\FormatHelper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'dni',
        'cellphone',
        'qualification',
        'username',
        'password',
        'shipping_agency',
        'shipping_agency_address',
        'google_verified',
        'google_id',
    ];

    protected $appends = [
        'whatsapp_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'google_verified' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'customer_favorites')->withTimestamps();
    }

    public function favorites()
    {
        return $this->hasMany(\App\Models\CustomerFavorite::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Attributes
    public function getWhatsappNumberAttribute()
    {
        if ($this->cellphone) {
            $cellphone = preg_replace('/\D/', '', $this->cellphone);
            if (isset($cellphone[0]) && $cellphone[0] == '0') {
                $cellphone = substr($cellphone, 1);
            }
            return $cellphone;
        }
        return null;
    }

    public function getIsSolventAttribute()
    {
        return $this->getBalance() >= 0;
    }

    // Methods
    public function getBalance()
    {
        return 0;
    }

    public function getTotalDebitRefundedBalance()
    {
        return 0;
    }

    public function getTotalBuyed()
    {
        return 0;
    }

    public function getTotalCredit()
    {
        return 0;
    }

    public function getTotalPayments()
    {
        return 0;
    }

    public function getTotalRefundCredit()
    {
        return 0;
    }

    public function getTotalDebt()
    {
        return 0;
    }

    public function needsToNotifyDebt()
    {
        return false;
    }

    public function getLastDateForDebtNotification()
    {
        return now();
    }

    public function existsOrders(): bool
    {
        return false;
    }

    public function haveDebtsCustomer(): bool
    {
        return false;
    }

    public function isPendingToSchedule(): bool
    {
        return false;
    }

    public function setPendingToSchedule($value): void
    {
        // No-op
    }

    public function getPlanningCollection()
    {
        return [
            'check' => true,
            'rest' => 0,
            'rest_formatted' => FormatHelper::formatCurrency(0),
            'customer_name' => $this->name,
        ];
    }

    private function getSuggestedCollectionTotal()
    {
        return 0;
    }

    // Shipping validation methods
    public function hasCompleteShippingInfo()
    {
        return !empty($this->name)
            && !empty($this->dni)
            && !empty($this->cellphone)
            && !empty($this->shipping_agency)
            && !empty($this->shipping_agency_address);
    }

    public function getMissingShippingFields()
    {
        $fields = [
            'name' => 'Nombre',
            'dni' => 'Cédula',
            'cellphone' => 'Teléfono',
            'shipping_agency' => 'Agencia de envío',
            'shipping_agency_address' => 'Dirección de la agencia',
        ];
        $missing = [];
        foreach ($fields as $key => $label) {
            if (empty($this->$key)) {
                $missing[] = $label;
            }
        }
        return $missing;
    }
}
