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
        'address',
        'address_picture',
        'contact_name',
        'contact_telephone',
        'contact_dni',
        'dni',
        'dni_picture',
        'latitude',
        'longitude',
        'max_credit',
        'name',
        'email',
        'receipt_picture',
        'card_front',
        'card_back',
        'qualification',
        'telephone',
        'cellphone',
        'solvency_date',
        'collection_frequency',
        'collection_day',
        'password',
        'username',
        'shipping_agency',
        'shipping_agency_address',
    ];

    protected $appends = [
        'date_next_visit',
        'max_credit_str',
        'url_address',
        'url_dni',
        'url_receipt',
        'url_card_front',
        'url_card_back',
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

    const DISK_ADDRESS = 'customers_address';

    const DISK_DNI = 'customers_dni';

    const DISK_RECEIPT = 'customers_receipt';

    const CARD = 'cards';

    # Boot
    protected static function boot()
    {
        parent::boot();

        # Cuando se elimina un cliente, se eliminan sus imagenes
        Customer::deleting(function ($model) {
            ImageService::delete($model::DISK_DNI, $model->dni_picture);
            ImageService::delete($model::DISK_RECEIPT, $model->receipt_picture);
            ImageService::delete($model::DISK_ADDRESS, $model->address_picture);
        });
    }

    # Relationships
    # Sales and refunds relationships removed

    # Attributes

    /**
     * Retorna en formato moneda, el credito maximo otorgado al cliente
     */
    public function getMaxCreditStrAttribute()
    {
        if ($this->max_credit) {
            return FormatHelper::formatCurrency($this->max_credit);
        }
        return FormatHelper::formatCurrency(0);
    }

     /**
     * Retorna en formato moneda, el credito disponible del cliente
     */
    public function getAvailableCreditStrAttribute()
    {
        // Sales functionality removed - always return available credit as max credit
        $available_credit = $this->max_credit > 0 ? $this->max_credit : 0;
        return FormatHelper::formatCurrency($available_credit);
    }

    /**
     * Retorna la fecha de la proxima visita - DEPRECATED (scheduling module removed)
     */
    public function getDateNextVisitAttribute()
    {
        return null;
    }



    /**
     * Retorna url de imagen de direccion
     */
    public function getUrlAddressAttribute()
    {
        if ($this->address_picture && Storage::disk(self::DISK_ADDRESS)->exists($this->address_picture)) {
            return Storage::disk(self::DISK_ADDRESS)->url($this->address_picture);
        }

        return url("/img/no_image.jpg");
    }

    /**
     * Retorna url de imagen de DNI
     */
    public function getUrlDniAttribute()
    {
        if ($this->dni_picture && Storage::disk(self::DISK_DNI)->exists($this->dni_picture)) {
            return Storage::disk(self::DISK_DNI)->url($this->dni_picture);
        }

        return url("/img/no_image.jpg");
    }

    /**
     * Retorna URL del recibo
     */
    public function getUrlReceiptAttribute()
    {
        if ($this->receipt_picture && Storage::disk(self::DISK_RECEIPT)->exists($this->receipt_picture)) {
            return Storage::disk(self::DISK_RECEIPT)->url($this->receipt_picture);
        }

        return url("/img/no_image.jpg");
    }

    /**
     * Retorna URL del recibo
     */
    public function getUrlCardFrontAttribute()
    {
        if ($this->card_front && Storage::disk(self::CARD)->exists($this->card_front)) {
            return Storage::disk(self::CARD)->url($this->card_front);
        }

        return url("/img/no_image.jpg");
    }

    /**
     * Retorna URL del recibo
     */
    public function getUrlCardBackAttribute()
    {
        if ($this->card_back && Storage::disk(self::CARD)->exists($this->card_back)) {
            return Storage::disk(self::CARD)->url($this->card_back);
        }

        return url("/img/no_image.jpg");
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
     * Procesa imagenes, borra si tenia una anterior (e.g DNI o Recibo)
     */
    public function updateImage($disk, $old_image, $new_file, $delete)
    {
        $url = null;

        if ($delete || $new_file) {
            ImageService::delete($disk, $old_image);
        }

        if ($new_file) {
            $url = ImageService::save($disk, $new_file);
        } else {
            $url = $old_image;
        }

        return $url;
    }

   

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
