<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
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

    public function getImageUrlAttribute()
    {
        return asset($this->image_path);
    }

    public function getFullImageUrlAttribute()
    {
        return asset($this->image_path);
    }

    public function getAbsolutePathAttribute()
    {
        return public_path($this->image_path);
    }

    // Check if image file exists
    public function getImageExistsAttribute()
    {
        return File::exists(public_path($this->image_path));
    }

    // Get image file size in KB
    public function getFileSizeAttribute()
    {
        if ($this->image_exists) {
            return round(File::size(public_path($this->image_path)) / 1024, 2);
        }
        return 0;
    }

    // Get image filename only
    public function getFilenameAttribute()
    {
        return basename($this->image_path);
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

    public function scopeActive($query)
    {
        return $query->whereHas('product', function($q) {
            $q->where('status', true);
        });
    }

    // Static methods
    public static function setPrimaryImage($productId, $imageId)
    {
        // Remove primary status from all images of this product
        self::where('product_id', $productId)->update(['is_primary' => false]);
        
        // Set the specified image as primary
        self::where('id', $imageId)->update(['is_primary' => true]);
    }

    // Helper method to get image dimensions
    public function getImageDimensions()
    {
        if ($this->image_exists) {
            $imagePath = public_path($this->image_path);
            list($width, $height) = getimagesize($imagePath);
            return [
                'width' => $width,
                'height' => $height,
                'ratio' => round($width / $height, 2)
            ];
        }
        return null;
    }

   

    // Boot method to handle model events
    protected static function boot()
    {
        parent::boot();

        // When deleting an image, delete the file from storage
        static::deleting(function ($image) {
           $imagePath = public_path($image->image_path);
if (File::exists($imagePath)) {
    File::delete($imagePath);
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