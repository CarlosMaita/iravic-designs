<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use SoftDeletes;

    protected $table = 'zones';

    protected $guarded = [];

    public $fillable = [
        'name',
        'position',
        'address_destination',
        'latitude_destination',
        'longitude_destination'
    ];

    # Relationships
    public function customers()
    {
        return $this->hasMany('App\Models\Customer');
    }
}
