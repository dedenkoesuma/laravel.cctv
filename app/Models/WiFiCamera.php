<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WiFiCamera extends Model
{
    use HasFactory;
    protected $table = 'wifi_cameras';
    protected $fillable = [
        'slug',
        'name',
        'brand',
        'brand_logo',
        'subtitle',
        'price',
        'original_price',
        'main_image',
        'gallery_images',
        'resolutions',
        'specifications',
        'package_includes',
        'description',
        'status',
        'is_featured',
        'stock',
        'sold',
        'sku',
    ];

    protected $casts = [
        'features' => 'array',
        'gallery_images' => 'array',
        'resolutions' => 'array',
        'specifications' => 'array',
        'package_includes' => 'array',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    /**
     * Generate slug from name
     */
    public static function generateSlug($name)
    {
        return Str::slug($name);
    }

    /**
     * Get all gallery images including main image
     */
    public function getAllImages()
    {
        $images = [$this->main_image];
        
        if ($this->gallery_images) {
            $images = array_merge($images, $this->gallery_images);
        }
        
        return $images;
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for filter by brand
     */
    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get formatted original price
     */
    public function getFormattedOriginalPriceAttribute()
    {
        if ($this->original_price) {
            return 'Rp ' . number_format($this->original_price, 0, ',', '.');
        }
        return null;
    }

    /**
     * Check if product has discount
     */
    public function hasDiscount()
    {
        return $this->original_price && $this->original_price > $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentage()
    {
        if ($this->hasDiscount()) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }
}