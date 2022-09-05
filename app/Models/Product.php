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
        'stock_user',
        'stock_total'
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

    public function products_ordered()
    {
        return $this->hasMany('App\Models\OrderProduct', 'product_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo('App\Models\Size');
    }

    public function stocks_history()
    {
        return $this->hasMany('App\Models\ProductStockHistory');
    }

    public function stocks_transfers()
    {
        return $this->hasMany('App\Models\ProductStockTransfer');
    }

    # Appends
    /**
     * Retorna label para el producto con el color y talla
     */
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

    /**
     * Retorna el codigo del producto. Si es combinancion, devolvera su codigo si lo tiene, sino, devuelve el del producto base
     */
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

    /**
     * Retorna en formato numerico, el precio del producto. Si es combinancion, devolvera su precio si lo tiene, sino, devuelve el del producto base
     */
    public function getRegularPriceAttribute()
    {
        return $this->getRegularPrice();
    }
    
    /**
     * Retorna en formato moneda, el precio del producto. Si es combinancion, devolvera su precio si lo tiene, sino, devuelve el del producto base
     */
    public function getRegularPriceStrAttribute()
    {
        return '$ ' . number_format($this->regular_price, 2, '.', ',');
    }

    /**
     * Retorna el tipo de stock a sociado al usuario
     */
    public function getStockUserAttribute()
    {
        if ($stock_column = Auth::user()->getColumnStock()) {
            return $this->$stock_column;
        }

        return 0;
    }

    /**
     * Retorna el total de stock, sumando todos los tipos de stocks
     */
    public function getStockTotalAttribute()
    {
        return ($this->stock_depot + $this->stock_local + $this->stock_truck);
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

    public function scopeWherePrice($query, $price, $operator)
    {
        return $query->where(function ($q) use ($operator, $price) {
            $q->whereHas('product_combinations', function ($q) use ($operator, $price) {
                $q->where('price', $operator, $price);
            })
            ->orWhere(function($q) use ($operator, $price) {
                $q->doesntHave('product_combinations')
                    ->where('price', $operator, $price);
            });
        });
    }

    # Methods

    /**
     * Almacena en BD, un registro de historial de cambio de stock de un producto
     * 
     * Se utiliza cuando un producto es:
     *  - Comprado
     *  - Devuelto
     *  - Transferido su stock de un tipo a otro
     */
    public function addStockHistoryRecord($user_id, $action, $new_stock, $old_stock, $qty, $stock, $order_product_id = null, $product_stock_transfer_id = null, $refund_product_id = null)
    {
        $attributes = array(
            'user_id' => $user_id,
            'order_product_id' => $order_product_id,
            'product_stock_transfer_id' => $product_stock_transfer_id,
            'refund_product_id' => $refund_product_id,
            'action' => $action,
            'new_stock' => $new_stock,
            'old_stock' => $old_stock,
            'qty' => $qty,
            'stock' => $stock
        );

        $this->stocks_history()->create($attributes);
    }

    /**
     * Retorna el precio del producto. Si es combinancion, devolvera su precio si lo tiene, sino, devuelve el del producto base
     */
    public function getRegularPrice()
    {
        return $this->price ? $this->price : $this->product_parent->price;
    }

    /**
     * Agrega/Devuelve cantidad devuelta, al stock asociado al usuario logueado.
     * Se usa cuando un producto es devuelto
     */
    public function addStockUser($refund_product_id, $qty, $action)
    {
        $user = Auth::user();
        $column_stock = $user->getColumnStock();
        $old_stock = $this->stock_user;
        $new_stock = ($old_stock + $qty);

        if ($column_stock) {
            $product = $this;
            Product::withoutEvents(function () use ($product, $column_stock, $new_stock) {
                $product = Product::find($product->id);
                $product->$column_stock = $new_stock;
                $product->save();

                return $product;
            });

            $this->addStockHistoryRecord($user->id, $action, $new_stock, $old_stock, $qty, $column_stock, null, null, $refund_product_id);
        }
    }

    /**
     * Disminuye cantidad comprada, al stock asociado al usuario logueado.
     * Se usa cuando un producto es comprado
     */
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
