<?php

namespace App\Models;

use App\Services\Images\ImageService;
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
        'telephone'
    ];

    public $appends = [
        'url_dni'
    ];

    private $disk_dni = 'customers_dni';

    private $disk_receipt = 'customers_receipt';

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
    public function zone()
    {
        return $this->belongsTo('App\Models\Zone');
    }
    
    # Attributes
    public function getUrlDniAttribute()
    {
        if (Storage::disk($this->disk_dni)->exists($this->dni_picture)) {
            return Storage::disk($this->disk_dni)->url($this->dni_picture);
        }

        return url("/img/no_image.jpg");
    }

    public function getUrlReceiptAttribute()
    {
        if (Storage::disk($this->disk_receipt)->exists($this->receipt_picture)) {
            return Storage::disk($this->disk_receipt)->url($this->receipt_picture);
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
}
