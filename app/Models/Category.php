<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    protected $table = 'categories';
    
    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $fillable = [
        'name', 'base_category_id', 'image_banner', 'bg_banner'
    ];

    protected static function boot()
    {
        parent::boot();
        // static::creating(function ($category) {
        //     if (empty($category->slug)) {
        //         $category->slug = \Illuminate\Support\Str::slug($category->name);
        //     }
        // });
    }

    public function baseCategory()
    {
        return $this->hasOne(BaseCategory::class, 'id', 'base_category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
