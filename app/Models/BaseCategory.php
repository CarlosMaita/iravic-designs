<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseCategory extends Model
{
    protected $fillable = ['name', 'has_gender', 'has_size'];

    /**
     * Categories belonging to this base category.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
