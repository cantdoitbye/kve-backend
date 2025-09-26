<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryHierarchyResource extends JsonResource
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
            'products_count' => $this->products_count ?? 0,
            'url' => "/products?category={$this->id}",
            'sub_categories' => $this->whenLoaded('subCategories', function() {
                return $this->subCategories->map(function($subCategory) {
                    return [
                        'id' => $subCategory->id,
                        'title' => $subCategory->title,
                        'slug' => $subCategory->slug,
                        'products_count' => $subCategory->products_count ?? 0,
                        'url' => "/products?sub_category={$subCategory->id}",
                        'segments' => $subCategory->segments->map(function($segment) {
                            return [
                                'id' => $segment->id,
                                'title' => $segment->title,
                                'slug' => $segment->slug,
                                'products_count' => $segment->products_count ?? 0,
                                'url' => "/products?segment={$segment->id}",
                                'sub_segments' => $segment->subSegments->map(function($subSegment) {
                                    return [
                                        'id' => $subSegment->id,
                                        'title' => $subSegment->title,
                                        'slug' => $subSegment->slug,
                                        'products_count' => $subSegment->products_count ?? 0,
                                        'url' => "/products?sub_segment={$subSegment->id}",
                                    ];
                                })
                            ];
                        })
                    ];
                });
            })
        ];
    }
}
