<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
        'real_code',
        'regular_price',
        'regular_price_str',
        'stock_user'
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

    public function stocks_history()
    {
        return $this->hasMany('App\Models\ProductStockHistory');
    }

    # Appends
    public function getRealCodeAttribute()
    {
        if ($this->code) {
            return $this->code;
        }

        if ($parent = $this->product_parent) {
            return $parent->code;
        }

        return null;
    }

    public function getRegularPriceAttribute()
    {
        return $this->getRegularPrice();
    }
    
    public function getRegularPriceStrAttribute()
    {
        return '$ ' . number_format($this->regular_price, 2, '.', ',');
    }

    public function getStockUserAttribute()
    {
        $user = Auth::user();

        if ($stock_column = $user->getColumnStock()) {
            return $this->$stock_column;
        }

        return 0;
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

    public function subtractStockUser($order_product_id, $qty)
    {
        $user = Auth::user();
        $column_stock = $user->getColumnStock();
        $old_stock = $this->stock_user;    
        $new_stock = ($old_stock - $qty);

        if ($column_stock) {
            $this->$column_stock = $new_stock;
            $this->save();

            $this->stocks_history()->create([
                'order_product_id' => $order_product_id,
                'user_id' => $user->id,
                'new_stock' => $new_stock,
                'old_stock' => $old_stock,
                'order_product_qty' => $qty,
                'stock' => $column_stock
            ]);
        }
    }
}
