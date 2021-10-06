<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    # Methods
    public function getZones()
    {
        $zones = collect();

        foreach ($this->visits as $visit) {
            if (!$zones->contains('id', $visit->customer->zone_id)) {
                $zone = $visit->customer->zone;
                $zones->push($zone);
            }
        }

        return $zones->sortBy('position')->values()->all();
    }

    public function getVisitsByCustomersZone($zone_id)
    {
        return $this->visits()->whereHas('customer', function($q) use ($zone_id) {
            $q->where('zone_id', $zone_id);
        })->orderBy('position')->get();
    }
}
