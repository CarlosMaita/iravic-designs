<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $table = 'products_sizes';

    public $fillable = [
        'product_id',
        'size_id'
    ];
    
    # Relationships
    public function size()
    {
        return $this->belongsTo('App\Models\Size');
    }
}
