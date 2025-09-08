<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SpecialOffer extends Model
{
    use HasFactory;

    protected $table = 'special_offers';
    
    private $filedisk = 'special-offers';

    protected $fillable = [
        'title',
        'description',
        'image',
        'start_date',
        'end_date',
        'is_active',
        'product_id',
        'discount_percentage',
        'order'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'discount_percentage' => 'decimal:2'
    ];

    public $appends = [
        'image_url',
        'is_current',
        'days_remaining'
    ];

    /**
     * Get the product associated with this special offer.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the image URL for the special offer.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk($this->filedisk)->exists($this->image)) {
            return asset(Storage::disk($this->filedisk)->url($this->image));
        }
        // Return a default image if no image is set
        return asset('images/default-special-offer.png');
    }

    /**
     * Check if the special offer is currently active and within the date range.
     */
    public function getIsCurrentAttribute()
    {
        $now = Carbon::now();
        return $this->is_active && 
               $this->start_date <= $now && 
               $this->end_date >= $now;
    }

    /**
     * Get the number of days remaining for this offer.
     */
    public function getDaysRemainingAttribute()
    {
        if (!$this->is_current) {
            return 0;
        }
        
        return Carbon::now()->diffInDays($this->end_date, false);
    }

    /**
     * Scope to get only active offers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only current offers (within date range).
     */
    public function scopeCurrent($query)
    {
        $now = Carbon::now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    /**
     * Scope to get offers ordered by priority.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')
                    ->orderBy('created_at', 'desc');
    }
}
