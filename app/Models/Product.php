<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'short_description', 'price', 'product_details',
        'specifications', 'category_id', 'sub_category_id', 'segment_id',
        'sub_segment_id', 'status', 'service_info', 'included', 'documentation', 'input_types', 'sku'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
          'service_info' => 'array',
        'included' => 'array',
        'documentation' => 'array',
        'input_types' => 'array',
        'output_types' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            $product->slug = Str::slug($product->title);
        });
        
        static::updating(function ($product) {
            $product->slug = Str::slug($product->title);
        });

        // When deleting a product, delete all associated images
        static::deleting(function ($product) {
            $product->images()->each(function ($image) {
                $image->delete(); // This will trigger the ProductImage boot method to delete files
            });
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function subSegment()
    {
        return $this->belongsTo(SubSegment::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->ordered();
    }

    // Accessors
    public function getPrimaryImageAttribute()
    {
        return $this->images()->primary()->first();
    }

    public function getMainImageAttribute()
    {
        $primaryImage = $this->primary_image;
        return $primaryImage ? $primaryImage->image_path : null;
    }

    public function getMainImageUrlAttribute()
    {
        $primaryImage = $this->primary_image;
        return $primaryImage ? $primaryImage->image_url : null;
    }

    // Helper methods
    public function hasImages()
    {
        return $this->images()->count() > 0;
    }

    public function getImageCount()
    {
        return $this->images()->count();
    }
}