<?php

namespace App\Models;

use App\Services\Images\ImageService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';
    
    protected $guarded = [];

    public $fillable = [
        'zone_id',
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
        'receipt_picture',
        'qualification',
        'telephone',
        'cellphone'
    ];

    public $appends = [
        'date_next_visit',
        'max_credit_str',
        'total_buyed',
        'total_credit',
        'total_debt',
        'total_payments',
        'url_address',
        'url_dni',
        'url_receipt'
    ];

    const DISK_ADDRESS = 'customers_address';

    const DISK_DNI = 'customers_dni';

    const DISK_RECEIPT = 'customers_receipt';

    # Boot
    protected static function boot()
    {
        parent::boot();
        Customer::deleting(function ($model) {
            ImageService::delete($model->disk_dni, $model->dni_picture);
            ImageService::delete($model->disk_receipt, $model->receipt_picture);
        });
    }

    # Relationships
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
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
    public function getMaxCreditStrAttribute()
    {
        if ($this->max_credit) {
            return '$ ' . number_format($this->max_credit, 2, '.', ',');
        }

        return '$ 0.00';
    }

    public function getDateNextVisitAttribute()
    {
        $now = now();
        $date = null;

        if ($next = $this->visits()->whereDate('date', '>=', $now)->orderBy('date', 'ASC')->first()) {
            $date = $next->date;
        }

        return $date;
    }

    public function getTotalBuyedAttribute()
    {
        $total = $this->getTotalBuyed();
        return '$ ' . number_format($total, 2, '.', ',');
    }

    public function getTotalCreditAttribute()
    {
        $total = $this->getTotalCredit();
        return '$ ' . number_format($total, 2, '.', ',');
    }

    public function getTotalDebtAttribute()
    {
        $total = $this->getTotalDebt();
        return '$ ' . number_format($total, 2, '.', ',');
    }

    public function getTotalPaymentsAttribute()
    {
        $total = $this->getTotalPayments();
        return '$ ' . number_format($total, 2, '.', ',');
    }

    public function getUrlAddressAttribute()
    {
        if (Storage::disk(self::DISK_ADDRESS)->exists($this->address_picture)) {
            return Storage::disk(self::DISK_ADDRESS)->url($this->address_picture);
        }

        return url("/img/no_image.jpg");
    }

    public function getUrlDniAttribute()
    {
        if (Storage::disk(self::DISK_DNI)->exists($this->dni_picture)) {
            return Storage::disk(self::DISK_DNI)->url($this->dni_picture);
        }

        return url("/img/no_image.jpg");
    }

    public function getUrlReceiptAttribute()
    {
        if (Storage::disk(self::DISK_RECEIPT)->exists($this->receipt_picture)) {
            return Storage::disk(self::DISK_RECEIPT)->url($this->receipt_picture);
        }

        return url("/img/no_image.jpg");
    }
    

    # Methods
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

    public function getTotalBuyed()
    {
        return $this->orders()->sum('total');
    }

    public function getTotalCredit()
    {
        return $this->orders()->where('payed_credit', 1)->sum('total');
    }

    public function getTotalPayments()
    {
        return $this->payments()->sum('amount');
    }

    public function getTotalDebt()
    {
        $total_credit = $this->orders()->where('payed_credit', 1)->sum('total');
        $total_payments = $this->payments()->sum('amount');

        return ($total_credit - $total_payments);
    }
}
