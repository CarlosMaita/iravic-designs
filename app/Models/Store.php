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

    /**
     * Relación muchos a muchos con Product a través de la tabla pivote product_store.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_store')
                    ->withPivot('stock'); // Incluir la columna 'stock' en la relación
    }
}
