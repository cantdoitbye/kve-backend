<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
 public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'status_label' => $this->status ? 'Active' : 'Inactive',
            'category_id' => $this->category_id,
            'products_count' => $this->whenCounted('products'),
            'segments_count' => $this->whenCounted('segments'),
            
            // Parent category information
            'category' => $this->whenLoaded('category', function() {
                return [
                    'id' => $this->category->id,
                    'title' => $this->category->title,
                    'slug' => $this->category->slug,
                ];
            }),
            
            // Child segments information
            'segments' => $this->whenLoaded('segments', function() {
                return $this->segments->map(function($segment) {
                    return [
                        'id' => $segment->id,
                        'title' => $segment->title,
                        'slug' => $segment->slug,
                        'products_count' => $segment->products_count ?? 0,
                        'sub_segments_count' => $segment->sub_segments_count ?? 0,
                    ];
                });
            }),
            
            // URL for frontend navigation
            'url' => "/products?sub_category={$this->id}",
            
            // Timestamps
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'formatted_created_at' => $this->created_at?->format('M d, Y'),
            'formatted_updated_at' => $this->updated_at?->format('M d, Y'),
        ];
    }
}
