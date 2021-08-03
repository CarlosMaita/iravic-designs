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
        'is_price_generic',
        'is_regular',
        'gender',
        'price',
        'is_child_size',
        'stock_depot',
        'stock_local',
        'stock_truck'
    ];

    protected $softCascade = [
        'product_combinations'
    ];

    public $appends = [
        'regular_price',
        'regular_price_str'
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

    # Appends
    public function getRegularPriceAttribute()
    {
        return $this->getRegularPrice();
    }
    
    public function getRegularPriceStrAttribute()
    {
        return '$ ' . number_format($this->regular_price, 2, '.', ',');
    }

    # Methods
    public function getRegularPrice()
    {
        $parent = $this->product_parent;

        if ($parent && ($parent->is_price_generic || !$this->price)) {
            return $parent->regular_price;
        }

        return $this->price ? $this->price : 0;
    }
}
