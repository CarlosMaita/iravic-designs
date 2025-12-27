<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCta extends Model
{
    use HasFactory;

    protected $table = 'home_ctas';

    protected $fillable = [
        'title',
        'icon',
        'description',
        'cta_text',
        'cta_url',
        'order'
    ];

    /**
     * Get CTAs ordered by order field
     */
    public static function ordered()
    {
        return static::orderBy('order')->orderBy('id');
    }
}
