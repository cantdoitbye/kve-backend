<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Segment extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'sub_category_id', 'status'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($segment) {
            $segment->slug = Str::slug($segment->title);
        });
        
        static::updating(function ($segment) {
            $segment->slug = Str::slug($segment->title);
        });
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function subSegments()
    {
        return $this->hasMany(SubSegment::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}