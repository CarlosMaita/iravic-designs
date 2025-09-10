<?php

namespace App\Models;

use App\Helpers\FormatHelper;
use App\Services\Images\ImageService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

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
    ];

    protected $appends = [
        'whatsapp_number',
    ];

      /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    # Boot
    protected static function boot()
    {
        parent::boot();

        # Clean up functionality removed since related images no longer exist
    }

    # Relationships
    # Sales and refunds relationships removed

    # Attributes

    /**
     * Retorna la fecha de la proxima visita - DEPRECATED (scheduling module removed)
     */
    public function getDateNextVisitAttribute()
    {
        return null;
    }



    /**
     * Retorna Link de whatsapp con mensaje a cliente sobre visita para cobrar hoy
     */
    public function getWhatsappNumberAttribute()
    {
        if ($this->cellphone) {
            $cellphone = str_replace('+', '', $this->cellphone);
            $cellphone = str_replace(' ', '', $this->cellphone);
            
            if ($cellphone[0] == 0) {
                $cellphone = substr($cellphone, 1);
            }

            return $cellphone;
            return `<a class="whatsapp-link" href="https://wa.me/{{ $this->cellphone }}?text=Hola buenos días, te escribimos desde {{ config('app.name') }}. Tenemos agendado para pasar a cobrar hoy. Te queda bien?" target='_blank'><i class="fab fa-whatsapp"></i></a>`;
        }

        return null;
    }
     /**
     * Retorna si es es solvente
     */
    public function getIsSolventAttribute(){
        return $this->getBalance() >= 0 ? true :false;
    }

    # Methods

    /**
     * Retorna balance del cliente
     */
    public function getBalance()
    {
        // Sales functionality removed - always return 0 balance
        return 0;
    }

    /**
     * Sales functionality removed - return default values
     */
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

    /**
     * Retorna Boolean si necesita ser visitado por deuda.
     * Se valida que los dias para avisar en caso de deuda, junto a la fecha del ultimo pago.
     */
    public function needsToNotifyDebt()
    {
        // Sales functionality removed - always return false
        return false;
    }

    /**
     * Sales functionality removed - return current date
     */
    public function getLastDateForDebtNotification()
    {
        return now();
    }
    
    /**
     * Sales functionality removed - return default values
     */
    public function existsOrders(): bool
    {
        return false;
    }

    public function haveDebtsCustomer(): bool 
    {
        return false;
    }

    public function isPendingToSchedule() : bool 
    {
        return false; // Always false since scheduling module is removed
    } 

    public function  setPendingToSchedule ($value) : void
    {
        // No-op since scheduling module is removed
    }

    public function getPlanningCollection(){
        // Sales functionality removed - return default values
        return array(
            'check' => true,
            'rest' => 0,
            'rest_formatted' => FormatHelper::formatCurrency(0),
            'customer_name' => $this->name,
        );
    }

    private function getSuggestedCollectionTotal(){
        return 0; // No visits to calculate from since scheduling module is removed
    }

    // Shipping validation methods
    public function hasCompleteShippingInfo()
    {
        return !empty($this->name) && 
               !empty($this->dni) && 
               !empty($this->cellphone) && 
               !empty($this->shipping_agency) && 
               !empty($this->shipping_agency_address);
    }

    public function getMissingShippingFields()
    {
        $missing = [];
        if (empty($this->name)) $missing[] = 'Nombre';
        if (empty($this->dni)) $missing[] = 'Cédula';
        if (empty($this->cellphone)) $missing[] = 'Teléfono';
        if (empty($this->shipping_agency)) $missing[] = 'Agencia de envío';
        if (empty($this->shipping_agency_address)) $missing[] = 'Dirección de la agencia';
        return $missing;
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
}
