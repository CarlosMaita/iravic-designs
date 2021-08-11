<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public $table = 'schedules';

    public $fillable = [
        'date',
        'completed'
    ];

    # Relationships
    public function visits()
    {
        return $this->hasMany('App\Models\Visit');
    }

    # Accessors
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y');
        }

        return null;
    }
}
