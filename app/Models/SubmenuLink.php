<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmenuLink extends Model
{
    use HasFactory;

    protected $table = 'submenu_links';

    protected $fillable = [
        'title',
        'url',
        'order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Scope to get only active links
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to order by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
