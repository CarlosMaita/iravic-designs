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
    /**
     * Modifica la fecha de la agenda en formato d-m-Y
     */
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
    /**
     * Retorna numero (usado como boolean) para verificar si tiene visitas que aun no han sido ordenadas
     */
    public function hasVisitsWithoutPosition()
    {
        return $this->visits()->whereNull('position')->count();
    }

    /**
     * Retorna collect de zonas que tengan clientes en la agenda
     */
    public function getZones($sort_asc = 'asc')
    {
        $zones = collect();

        foreach ($this->visits as $visit) {
            if (!$zones->contains('id', $visit->customer->zone_id)) {
                $zone = $visit->customer->zone;
                $zones->push($zone);
            }
        }

        if ($sort_asc == 'asc') {
            return $zones->sortBy('position')->values()->all();
        } else {
            return $zones->sortByDesc('position')->values()->all();
        }
    }

    /**
     * Retorna listado de visitas por una zona especifica pasada por parametro
     */
    public function getVisitsByCustomersZone($zone_id)
    {
        return $this->visits()->whereHas('customer', function($q) use ($zone_id) {
            $q->where('zone_id', $zone_id);
        })
        ->orderBy('position')
        ->get();
    }
}
