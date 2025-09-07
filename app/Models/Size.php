<?php

namespace App\Models;

use App\TypeSize;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';
    
    protected $guarded = [];

    public $fillable = [
        'name'
    ];

    public $timestamps = true;

    public function type_size()
    {
        return $this->belongsTo(TypeSize::class);
    }

    /**
     * Get the products for the size.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'size_id');
    }

}
