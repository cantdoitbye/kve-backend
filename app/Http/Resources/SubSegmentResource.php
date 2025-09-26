<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubSegmentResource extends JsonResource
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
            'segment_id' => $this->segment_id,
            'products_count' => $this->whenCounted('products'),
            'segment' => new SegmentResource($this->whenLoaded('segment')),
            'sub_category' => $this->whenLoaded('segment.subCategory', function() {
                return new SubCategoryResource($this->segment->subCategory);
            }),
            'category' => $this->whenLoaded('segment.subCategory.category', function() {
                return new CategoryResource($this->segment->subCategory->category);
            }),
            'hierarchy' => $this->when($this->relationLoaded('segment'), function() {
                return [
                    'category' => $this->segment->subCategory->category->title ?? 'N/A',
                    'sub_category' => $this->segment->subCategory->title ?? 'N/A',
                    'segment' => $this->segment->title,
                    'sub_segment' => $this->title
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
