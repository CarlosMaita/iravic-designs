<?php

namespace App\Models;

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

    # Relationships
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function creator()
    {
        return $this->belongsTo('App\User');
    }

    public function responsable()
    {
        return $this->belongsTo('App\User');
    }
    
    public function schedule()
    {
        return $this->belongsTo('App\Models\Schedule');
    }
}
