<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'store_type_id'];

    public function type()
    {
        return $this->belongsTo(StoreType::class, 'store_type_id');
    }
}
