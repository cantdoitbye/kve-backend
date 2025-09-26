<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'sort_order',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    public function getFullImageUrlAttribute()
    {
        return asset(Storage::url($this->image_path));
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    // Static methods
    public static function setPrimaryImage($productId, $imageId)
    {
        // Remove primary status from all images of this product
        self::where('product_id', $productId)->update(['is_primary' => false]);
        
        // Set the specified image as primary
        self::where('id', $imageId)->update(['is_primary' => true]);
    }

    // Boot method to handle model events
    protected static function boot()
    {
        parent::boot();

        // When deleting an image, delete the file from storage
        static::deleting(function ($image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        });

        // When creating the first image, make it primary
        static::created(function ($image) {
            $productImageCount = self::where('product_id', $image->product_id)->count();
            if ($productImageCount === 1) {
                $image->update(['is_primary' => true]);
            }
        });
    }
}