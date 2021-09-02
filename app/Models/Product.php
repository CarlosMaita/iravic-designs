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
        'name_full',
        'real_code',
        'regular_price',
        'regular_price_str',
        'stock_user'
    ];

    # Boot
    public static function boot()
    {
        parent::boot();

        static::saved(function ($product) {
            $user = Auth::user();

            if ($product->isDirty('stock_depot')) {
                $old_stock = (int) $product->getRawOriginal('stock_depot');
                $new_stock = $product->stock_depot;
                $qty = $new_stock - $old_stock;
                $product->addStockHistoryRecord($user->id, 'Actualización de stock', $new_stock, $old_stock, $qty, 'stock_depot');
            }

            if ($product->isDirty('stock_local')) {
                $old_stock = (int) $product->getRawOriginal('stock_local');
                $new_stock = $product->stock_local;
                $qty = $new_stock - $old_stock;
                $product->addStockHistoryRecord($user->id, 'Actualización de stock', $new_stock, $old_stock, $qty, 'stock_local');
            }

            if ($product->isDirty('stock_truck')) {
                $old_stock = (int) $product->getRawOriginal('stock_truck');
                $new_stock = $product->stock_truck;
                $qty = $new_stock - $old_stock;
                $product->addStockHistoryRecord($user->id, 'Actualización de stock', $new_stock, $old_stock, $qty, 'stock_truck');
            }
        });
    }

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

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage');
    }

    public function product_combinations()
    {
        return $this->hasMany('App\Models\Product')->orderBy('color_id');
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
    public function getNameFullAttribute()
    {
        $name = $this->name;

        if ($this->color) {
            $name .= ' - Color: ' . $this->color->name;
        }

        if ($this->size) {
            $name .= ' - Talla: ' . $this->size->name;
        }

        return $name;
    }

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
        if ($stock_column = Auth::user()->getColumnStock()) {
            return $this->$stock_column;
        }

        return 0;
    }

    # Scopes
    public function scopeWhereInBrand($query, $brands_id)
    {
        return $query->whereIn('brand_id', $brands_id);
    }

    public function scopeWhereInCategory($query, $categories_id)
    {
        return $query->whereIn('category_id', $categories_id);
    }

    public function scopeWhereInColor($query, $colors)
    {
        return $query->where(function ($q) use ($colors) {
            $q->whereHas('product_combinations', function ($q) use ($colors) {
                $q->whereIn('color_id', $colors);
            });
        });
    }
    
    public function scopeWhereInGender($query, $genders)
    {
        return $query->whereIn('gender', $genders);
    }

    public function scopeWhereInSize($query, $sizes)
    {
        return $query->where(function ($q) use ($sizes) {
            $q->whereHas('product_combinations', function ($q) use ($sizes) {
                $q->whereIn('size_id', $sizes);
            });
        });
    }

    # Methods
    public function addStockHistoryRecord($user_id, $action, $new_stock, $old_stock, $qty, $stock, $order_product_id = null)
    {
        $attributes = array(
            'user_id' => $user_id,
            'order_product_id' => $order_product_id,
            'action' => $action,
            'new_stock' => $new_stock,
            'old_stock' => $old_stock,
            'qty' => $qty,
            'stock' => $stock
        );

        $this->stocks_history()->create($attributes);
    }

    public function getRegularPrice()
    {
        $parent = $this->product_parent;

        if ($parent && ($parent->is_price_generic || !$this->price)) {
            return $parent->regular_price;
        }

        return $this->price ? $this->price : 0;
    }

    public function subtractStockUser($order_product_id, $qty, $action)
    {
        $user = Auth::user();
        $column_stock = $user->getColumnStock();
        $old_stock = $this->stock_user;
        $new_stock = ($old_stock - $qty);

        if ($column_stock) {
            // $this->$column_stock = $new_stock;
            // $this->save();
            
            $product = $this;
            Product::withoutEvents(function () use ($product, $column_stock, $new_stock) {
                $product = Product::find($product->id);
                $product->$column_stock = $new_stock;
                $product->save();

                return $product;
            });

            // $this->stocks_history()->create([
            //     'order_product_id' => $order_product_id,
            //     'user_id' => $user->id,
            //     'new_stock' => $new_stock,
            //     'old_stock' => $old_stock,
            //     'order_product_qty' => $qty,
            //     'stock' => $column_stock
            // ]);

            $this->addStockHistoryRecord($user->id, $action, $new_stock, $old_stock, $qty, $column_stock, $order_product_id);
        }
    }
}
