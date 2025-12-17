<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'instructions',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope for active payment methods
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope to get ordered payment methods
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Relationship with payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method', 'code');
    }
}
