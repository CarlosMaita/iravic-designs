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

    # Scopes
    public function scopeWhereHasVisitasByResponsable($query, $user_id)
    {
        return $query->whereHas('visits', function ($q) use ($user_id) {
            $q->where('user_responsable_id', $user_id);
        });
    }
}
