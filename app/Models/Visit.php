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
        'comment',
        'date',
        'is_completed',
        'completed_date'
    ];

    # Boot
    protected static function boot()
    {
        parent::boot();

        Visit::updated(function ($model) {
            if ($model->schedule->id != $model->getOriginal('schedule_id')) {
                $schedule = Schedule::find($model->getOriginal('schedule_id'));

                if ($schedule->visits()->count()) {
                    $schedule->delete();
                }
            }
        });

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

    public function responsable()
    {
        return $this->belongsTo('App\User', 'user_responsable_id', 'id');
    }
    
    public function schedule()
    {
        return $this->belongsTo('App\Models\Schedule');
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
