<?php

namespace App\Models;

use App\Services\Images\ImageService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Spending extends Model
{
    public $table = 'spendings';

    public $fillable = [
        'box_id',
        'user_id',
        'amount',
        'comment',
        'date',
        'picture'
    ];

    public $appends = [
        'amount_str',
        'url_picture'
    ];

    const DISK = 'spendings';

    # Relationships
    public function box()
    {
        return $this->belongsTo('App\Models\Box');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    # Accessors
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y h:i:s');
        }

        return null;
    }

    # Appends
    public function getAmountStrAttribute()
    {
        return '$ ' . number_format($this->amount, 2, '.', ',');
    }

    public function getUrlPictureAttribute()
    {
        if (Storage::disk(self::DISK)->exists($this->picture)) {
            return Storage::disk(self::DISK)->url($this->picture);
        }

        return url("/img/no_image.jpg");
    }
}
