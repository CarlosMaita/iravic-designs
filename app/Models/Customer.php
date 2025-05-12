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
        'zone_id',
        'address',
        'address_picture',
        'contact_name',
        'contact_telephone',
        'contact_dni',
        'days_to_notify_debt',
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
        'is_pending_to_schedule',
        'telephone',
        'cellphone',
        'solvency_date',
        'collection_frequency',
        'collection_day',
        'password',
    ];

    protected $appends = [
        'balance',
        'balance_numeric',
        'date_next_visit',
        'max_credit_str',
        'available_credit_str',
        'total_buyed',
        'total_credit',
        'total_debt',
        'total_payments',
        'total_refund_credit',
        'url_address',
        'url_dni',
        'url_receipt',
        'url_card_front',
        'url_card_back',
        'whatsapp_number',
        'is_solvent',
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
    public function debts()
    {
        return $this->hasMany('App\Models\Debt');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
    }

    public function refunds()
    {
        return $this->hasMany('App\Models\Refund');
    }

    public function visits()
    {
        return $this->hasMany('App\Models\Visit');
    }

    public function zone()
    {
        return $this->belongsTo('App\Models\Zone');
    }
    
    # Attributes
    /**
     * Retorna en formato moneda, el balance del cliente
     */
    public function getBalanceAttribute()
    {
        return FormatHelper::formatCurrency($this->getBalance());
    }

    /**
     * Retorna en formato numerico, el balance del cliente
     */
    public function getBalanceNumericAttribute()
    {
        return $this->getBalance();
    }

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
        $used_credit = $this->getBalance() <= 0 ?  $this->getBalance() : 0 ;
        $available_credit = $this->max_credit + $used_credit > 0 ? ($this->max_credit + $used_credit) : 0;
        return FormatHelper::formatCurrency($available_credit);
    }

    /**
     * Retorna la fecha de la proxima visita (Si tiene)
     */
    public function getDateNextVisitAttribute()
    {
        $now = now();
        $date = null;

        if ($next = $this->visits()->whereDate('date', '>=', $now)->orderBy('date', 'ASC')->first()) {
            $date = $next->date;
        }

        return $date;
    }

    /**
     * Retorna en formato moneda el total comprardo
     */
    public function getTotalBuyedAttribute()
    {
        return FormatHelper::formatCurrency($this->getTotalBuyed());
    }

    /**
     * Retorna en formato moneda, total comprado a credito
     */
    public function getTotalCreditAttribute()
    {
        return FormatHelper::formatCurrency($this->getTotalCredit());
    }

    /**
     * Retorna en formato moneda, total comprado a debito
     */
    public function getTotalDebtAttribute()
    {
        $total = $this->getTotalDebt();
        return FormatHelper::formatCurrency($total, $total > 0 ? '$ -' : '$ ');
    }

    /**
     * Retorna en formato moneda, total de pagos
     */
    public function getTotalPaymentsAttribute()
    {
        return FormatHelper::formatCurrency($this->getTotalPayments());
    }

    /**
     * Retorna en formato moneda, el total devoluciones a credito
     */
    public function getTotalRefundCreditAttribute()
    {
        return FormatHelper::formatCurrency($this->getTotalRefundCredit());
    }

    /**
     * Retorna url de imagen de direccion
     */
    public function getUrlAddressAttribute()
    {
        if (Storage::disk(self::DISK_ADDRESS)->exists($this->address_picture)) {
            return Storage::disk(self::DISK_ADDRESS)->url($this->address_picture);
        }

        return url("/img/no_image.jpg");
    }

    /**
     * Retorna url de imagen de DNI
     */
    public function getUrlDniAttribute()
    {
        if (Storage::disk(self::DISK_DNI)->exists($this->dni_picture)) {
            return Storage::disk(self::DISK_DNI)->url($this->dni_picture);
        }

        return url("/img/no_image.jpg");
    }

    /**
     * Retorna URL del recibo
     */
    public function getUrlReceiptAttribute()
    {
        if (Storage::disk(self::DISK_RECEIPT)->exists($this->receipt_picture)) {
            return Storage::disk(self::DISK_RECEIPT)->url($this->receipt_picture);
        }

        return url("/img/no_image.jpg");
    }

    /**
     * Retorna URL del recibo
     */
    public function getUrlCardFrontAttribute()
    {
        if (Storage::disk(self::CARD)->exists($this->card_front)) {
            return Storage::disk(self::CARD)->url($this->card_front);
        }

        return url("/img/no_image.jpg");
    }

    /**
     * Retorna URL del recibo
     */
    public function getUrlCardBackAttribute()
    {
        if (Storage::disk(self::CARD)->exists($this->card_back)) {
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
        #pagos totales
        $payments = $this->payments()->sum('amount');
        $total_orders_to_cash = $this->orders()->where('payed_credit', 0)->sum('total');
        $total_payments = $payments + $total_orders_to_cash; //pagos + ordenes pagas en Efectivo, targeta y transferencia
        
        #ordenes totales
        $total_orders = $this->orders()->sum('total');

        #total de devoluciones
        $total_refunds = $this->refunds()->sum('total');

        #total de deudas 
        $total_debts = $this->debts()->sum('amount');
        
        $balance = ($total_payments - $total_debts - $total_orders + $total_refunds);
        return $balance;
    }

    /**
     * Retorna en formato numerico, el total devuelto con compra pagada a debito
     */
    public function getTotalDebitRefundedBalance()
    {
        $refunded = $this->refunds()->has('order')->sum('total_refund_debit');
        $ordered = $this->orders()->whereHas('refund', function($q) {
            $q->where('total_refund_debit', '>', 0);
        })
        ->where('payed_credit', 0)
        ->sum('total');
        
        #cuando se llevan mas del monto devuelto pagando con debito. el saldo queda negativo, cuando deberia quedar en 0
        return $refunded - $ordered >= 0 ? ($refunded - $ordered) : 0 ;
    }

    /**
     * Retorna en formato numerico, total comprado
     */
    public function getTotalBuyed()
    {
        return $this->orders()->sum('total');
    }

    /**
     * Retorna en formato numerico, total comprado a credito
     */
    public function getTotalCredit()
    {
        return $this->orders()->where('payed_credit', 1)->sum('total');
    }

    /**
     * Retorna en formato numerico, total pagado
     */
    public function getTotalPayments()
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Retorna en formato numerico, total devuelto comprado a credito
     */
    public function getTotalRefundCredit()
    {
        $total = 0;

        foreach ($this->refunds as $refund) {
            foreach ($refund->products as $refund_product) {
                if ($refund_product->order_product->order->payed_credit) {
                    $total += ($refund_product->product_price * $refund_product->qty);
                }
            }
        }
        
        return $total;
    }

    /**
     * Retorn en formato numerico, total de deuda
     */
    public function getTotalDebt()
    {
        return $this->debts()->sum('amount');
    }

    /**
     * Retorna Boolean si necesita ser visitado por deuda.
     * Se valida que los dias para avisar en caso de deuda, junto a la fecha del ultimo pago.
     */
    public function needsToNotifyDebt()
    {
        if ($this->getBalance() < 0) {
            $now = now();
            $days_to_notify = is_int($this->days_to_notify_debt) ? $this->days_to_notify_debt : 0;
            $date_last_payment = $this->getLastDateForDebtNotification();
            if ($date_last_payment->diffInDays($now, false) >= $days_to_notify) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retorna fecha del ultimo pago/deuda/orden registrada despues de la fecha de solvencia
     */
    public function getLastDateForDebtNotification()
    {
        $solvencyDate = Carbon::parse($this->solvency_date)->format('Y-m-d H:i:s');
        $latestPayment = $this->payments()->where('date', '>', $solvencyDate)->latest()->first();
        if ($latestPayment) {
            return Carbon::parse($latestPayment->date);
        }
        #segundo considera la fecha de la ultima deuda despues de la fecha de solvencia
        if ($debt = $this->debts()->where('date', '>', $solvencyDate)->oldest()->first()) {
            return Carbon::parse($debt->date);
        }
        #tercero considera la fecha de la ultima orden a credito despues de la fecha de solvencia
        if($order = $this->orders()->where('payed_credit', '1')->where('date', '>', $solvencyDate)->oldest()->first()){
            return Carbon::parse($order->date);
        }
        #by default
        return now();
    }
    
    /**
     * Retorna si existen ordenes vinculadas al cliente
     */
    public function existsOrders() : bool
    {
        return count($this->orders) > 0 ? true : false;
    }

    /**
     * Retorna si existen Visitas vinculadas al cliente
     */
    public function existsVisits() : bool
    {
        return count($this->visits) > 0? true : false;
    }

    public function haveDebtsCustomer() : bool 
    {
        return $this->getBalance() < 0 ? true : false;
    }

    public function isPendingToSchedule() : bool 
    {
        return $this->is_pending_to_schedule == 1 ? true : false ;
    } 

    public function  setPendingToSchedule ($value) : void
    {
        $this->is_pending_to_schedule = $value;
        $this->save();
    }

    public function getPlanningCollection(){
        $balance = $this->getBalance();
        $suggestedCollectionTotal = $this->getSuggestedCollectionTotal();
        $rest = round($suggestedCollectionTotal + $balance) ;
        return array(
            'check' => (isset($rest) && $rest == 0 ) || $balance >= 0 ,
            'rest' => $rest,
            'rest_formatted' => FormatHelper::formatCurrency($rest ?? 0),
            'customer_name' => $this->name,
        );
    }

    private function getSuggestedCollectionTotal(){
        return  $this->visits()
            ->where('is_collection', true)
            ->whereDate('date', '>=', now())
            ->sum('suggested_collection');

    }
}
