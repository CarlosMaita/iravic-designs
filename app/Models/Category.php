<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'base_category_id', 'image_banner', 'bg_banner'
    ];

    public function baseCategory()
    {
        return $this->belongsTo(BaseCategory::class, 'base_category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
