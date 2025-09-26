<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubSegment extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'segment_id', 'status'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($subSegment) {
            $subSegment->slug = Str::slug($subSegment->title);
        });
        
        static::updating(function ($subSegment) {
            $subSegment->slug = Str::slug($subSegment->title);
        });
    }

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}