<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'category_id', 'status'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($subCategory) {
            $subCategory->slug = Str::slug($subCategory->title);
        });
        
        static::updating(function ($subCategory) {
            $subCategory->slug = Str::slug($subCategory->title);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function segments()
    {
        return $this->hasMany(Segment::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}