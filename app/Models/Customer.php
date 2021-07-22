<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    # Relationships
    public function zone()
    {
        return $this->belongsTo('App\Models\Zone');
    }
}
