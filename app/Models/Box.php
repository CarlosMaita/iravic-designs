<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $table = 'boxes';

    public $fillable = [
        'user_id',
        'cash_initial',
        'closed',
        'date',
        'date_start',
        'date_end'
    ];

    public $appends = [
        'total_bankwire',
        'total_card',
        'total_cash',
        'total_credit',
        'total_payed'
    ];

    # Relationships
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    # Accessors
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y');
        }

        return null;
    }

    public function getDateStartAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y h:m:s');
        }

        return null;
    }

    public function getDateEndAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y h:m:s');
        }

        return null;
    }

    # Appends
    public function getTotalBankwireAttribute()
    {
        return 0;
    }

    public function getTotalCardAttribute()
    {
        return 0;
    }

    public function getTotalCashAttribute()
    {
        return 0;
    }
    
    public function getTotalCreditAttribute()
    {
        return 0;
    }

    public function getTotalPayedAttribute()
    {
        $total = $this->getTotalPayed();
        return number_format($total, 2 , ',', '.');
    }

    # Methods
    public function getTotalPayed()
    {
        return 0;
    }
}
