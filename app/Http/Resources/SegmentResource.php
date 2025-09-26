<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SegmentResource extends JsonResource
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
            'sub_category_id' => $this->sub_category_id,
            'products_count' => $this->whenCounted('products'),
            'sub_segments_count' => $this->whenCounted('subSegments'),
            'sub_category' => new SubCategoryResource($this->whenLoaded('subCategory')),
            'sub_segments' => SubSegmentResource::collection($this->whenLoaded('subSegments')),
            'category' => $this->whenLoaded('subCategory.category', function() {
                return new CategoryResource($this->subCategory->category);
            }),
            'hierarchy' => $this->when($this->relationLoaded('subCategory'), function() {
                return [
                    'category' => $this->subCategory->category->title ?? 'N/A',
                    'sub_category' => $this->subCategory->title,
                    'segment' => $this->title
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
