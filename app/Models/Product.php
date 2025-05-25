<?php

namespace App\Models;

use App\Events\ProductStockChanged;
use App\Helpers\FormatHelper;
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
        'text_color',
        'product_id',
        'size_id',
        'name',
        'description',
        'code',
        'cover',
        'is_regular',
        'gender',
        'price',
        'price_card_credit',
        'price_credit',
        'is_child_size',
        'combination_index'
    ];

    protected $softCascade = [
        'product_combinations'
    ];

    public $appends = [
        'name_full',
        'real_code',
        'regular_price',
        'regular_price_str',
        'regular_price_card_credit',
        'regular_price_card_credit_str',
        'regular_price_credit',
        'regular_price_credit_str',
        'stock_total'
    ];

    # Boot
    public static function boot()
    {
        parent::boot();
       
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
        return $this->hasMany('App\Models\Product')->orderBy('color_id')->with('stores');
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

    /**
     * Relación muchos a muchos con Store a través de la tabla pivote product_store.
     */
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'product_store')
                    ->withPivot('stock'); // Icluir la columna 'stock' en la relaciónn
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
     * Retorna label para el producto con el color y talla si la categoría base tiene talla.
     * Considera las categorías eliminadas, ten en cuenta que las categorías tienen borrado suave.
     */
    public function getNameFullAttribute()
    {
        $name = $this->name;

        if ($this->color) {
            $name .= ' - Color: ' . $this->color->name;
        }
        $baseCategory = $this->category()->withTrashed()->first()->baseCategory;
        if ($baseCategory->has_size && $this->size) {
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
        return  $this->price ? $this->price : $this->product_parent->price ?? 0;
    }

    public function getRegularPriceCardCreditAttribute()
    {
        return  $this->price_card_credit ? $this->price_card_credit : $this->product_parent->price_card_credit ?? $this->regular_price;
    }

    public function getRegularPriceCreditAttribute()
    {
        return  $this->price_credit ? $this->price_credit : $this->product_parent->price_credit ?? $this->regular_price;
    }
    
    /**
     * Retorna en formato moneda, el precio del producto. Si es combinancion, devolvera su precio si lo tiene, sino, devuelve el del producto base
     */
    public function getRegularPriceStrAttribute()
    {
        return  FormatHelper::formatCurrency($this->regular_price);
    }

    public function getRegularPriceCardCreditStrAttribute()
    {
        return  FormatHelper::formatCurrency($this->regular_price_card_credit);
    }

    public function getRegularPriceCreditStrAttribute()
    {
        return  FormatHelper::formatCurrency($this->regular_price_credit);
    }

    /**
     * Retorna el total de stock, sumando todos los tipos de stocks
     */
    public function getStockTotalAttribute() : int
    {   
        return  $this->stores()->sum('stock');
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

    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'like', '%' . $keyword . '%')
            ->orWhere('description', 'like', '%' . $keyword . '%')
            ->orWhere('code', 'like', '%' . $keyword . '%');
    }

    # Methods

    /**
     * Agrega/Devuelve cantidad devuelta, al stock asociado al usuario logueado.
     * Se usa cuando un producto es devuelto
     */
    public function rollbackStockUser(int $storeId, int $quantity, string $action, int $refund_product_id)
    {

        $oldStock = $this->stores()->find($storeId)->pivot->stock;
        $newStock = $oldStock + $quantity;

        $this->stores()->updateExistingPivot($storeId, ['stock' => $newStock]);

        event(new ProductStockChanged(
            $this->id,
            $storeId,
            $oldStock,
            $newStock,
            $quantity,
            $action,
            auth()->id(),
            null,
            null,
            $refund_product_id
        ));
    }

    /**
     * Decrements the stock of the product in the specified store.
     *
     * @param int $storeId
     * @param int $quantity
     * @param string $action
     * @param int $orderProductId
     *
     * @throws \Exception
     */
    public function subtractStock(int $storeId, int $quantity, string $action, int $orderProductId)
    {
        $oldStock = $this->stores()->find($storeId)->pivot->stock;
        $newStock = $oldStock - $quantity;

        if ($newStock < 0) {
            throw new \Exception('Cannot subtract more stock than available');
        }

        $this->stores()->updateExistingPivot($storeId, ['stock' => $newStock]);

        event(new ProductStockChanged(
            $this->id,
            $storeId,
            $oldStock,
            $newStock,
            $quantity,
            $action,
            auth()->id(),
            null,
            $orderProductId
        ));
    }

    function hasPendingTransfer()
    {
        return $this->stocks_transfers()
            ->whereIn('is_accepted', [ProductStockTransfer::PENDING])
            ->exists();
    }
}
