<?php

namespace App;

use App\Models\Size;
use Illuminate\Database\Eloquent\Model;

class TypeSize extends Model
{
    
    protected $fillable = [
        'name'
    ];

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }
}
