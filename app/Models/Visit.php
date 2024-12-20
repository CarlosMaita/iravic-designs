<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    public $table = 'visits';

    public $fillable = [
        'customer_id',
        'schedule_id',
        'user_creator_id',
        'user_responsable_id',
        'order_id',
        'comment',
        'date',
        'position',
        'is_completed',
        'completed_date',
        'is_collection', 
        'is_paid',
    ];

    # Boot
    protected static function boot()
    {
        parent::boot();

        # Si actualizan una visita (fecha) y esta cambia de agenda, se valida si la agenda anterior queda vacia para eliminarla
        Visit::updated(function ($model) {
            if ($model->schedule->id != $model->getOriginal('schedule_id')) {
                $schedule = Schedule::find($model->getOriginal('schedule_id'));

                if ($schedule->visits()->count()) {
                    $schedule->delete();
                }
            }
        });

        # Si eliminan una visita, se valida si la agenda queda vacia para eliminarla
        Visit::deleting(function ($model) {
            if (!$model->schedule->visits()->where('id', '<>', $model->id)->count()) {
                $model->schedule->delete();
            }
        });
    }

    # Relationships
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'user_creator_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function responsable()
    {
        return $this->belongsTo('App\User', 'user_responsable_id', 'id');
    }
    
    public function schedule()
    {
        return $this->belongsTo('App\Models\Schedule');
    }

    # Accessors

    # Modifica la fecha en formato d-m-Y
    public function getDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-Y');
        }

        return null;
    }

    # Scopes
    public function scopeJoinCustomers($query)
    {
        return $query->join('customers', 'customers.id', '=', 'visits.customer_id');
    }

    public function scopeJoinZones($query)
    {
        return $query->join('zones', 'zones.id', '=', 'customers.zone_id');
    }

    public function scopeWhereScheduleId($query, $schedule_id)
    {
        return $query->where('schedule_id', $schedule_id);
    }

    public function scopeWhereUserResponsableId($query, $user_responsable_id)
    {
        return $query->where('user_responsable_id', $user_responsable_id);
    }

    public function scopeWhereInZone($query, $zones_id)
    {
        return $query->whereIn('zone_id', $zones_id);
    }

    public function scopeWhereResponsableByRoleName($query, $roles_name)
    {
        return $query->where(function ($q) use ($roles_name) {
            $q->whereHas('responsable', function ($q) use ($roles_name) {
                $q->whereHas('roles', function ($q) use ($roles_name) {
                    $q->whereIn('name', $roles_name);
                });
            });
        });
    }

     /**
     * valida que exista responsable asignado 
     * 
     * @return bool 
     */
    public function existsAssignedResponsible() : bool 
    {
        return  $this->responsable ? true : false;
    }
}
