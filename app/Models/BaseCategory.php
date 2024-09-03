<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class BaseCategory extends Model
{  
    protected $fillable = ['name']; 

    /**
     * Get the categories associated with the base category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    
}
