<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    
    protected $table = 'products';
    protected $guarded = [];
    public $fillable = [
        'brand_id',
        'category_id',
        'color_id',
        'product_id',
        'size_id',
        'name',
        'code',
        'cover',
        'is_regular',
        'gender',
        'is_child_size',
        'stock_depot',
        'stock_local',
        'stock_truck'
    ];

    protected $softCascade = [
        'product_combinations'
    ];

    # Relationships
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function color()
    {
        return $this->belongsTo('App\Models\Color');
    }

    public function product_combinations()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function product_parent()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo('App\Models\Size');
    }
}
