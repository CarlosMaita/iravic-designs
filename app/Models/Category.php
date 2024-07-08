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

    public $fillable = [
        'name', 'base_category_id'
    ];

    public function baseCategory()
    {
        return $this->hasOne(BaseCategory::class, 'id', 'base_category_id');
    }
}
