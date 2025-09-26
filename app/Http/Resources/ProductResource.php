<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'short_description' => $this->short_description,
            'product_details' => $this->when($request->routeIs('*.show'), $this->product_details),
            'specifications' => $this->when($request->routeIs('*.show'), $this->specifications),
            'price' => $this->price,
            'formatted_price' => '₹' . number_format($this->price, 2),
            'status' => $this->status,
            'status_label' => $this->status ? 'Active' : 'Inactive',
            'primary_image_url' => $this->main_image_url,
            'has_images' => $this->hasImages(),
            'images_count' => $this->getImageCount(),
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'sub_category' => new SubCategoryResource($this->whenLoaded('subCategory')),
            'segment' => new SegmentResource($this->whenLoaded('segment')),
            'sub_segment' => new SubSegmentResource($this->whenLoaded('subSegment')),
            'hierarchy' => $this->when(
                $this->relationLoaded('category') && 
                $this->relationLoaded('subCategory') && 
                $this->relationLoaded('segment') && 
                $this->relationLoaded('subSegment'), 
                function() {
                    return [
                        'category' => $this->category->title,
                        'sub_category' => $this->subCategory->title,
                        'segment' => $this->segment->title,
                        'sub_segment' => $this->subSegment->title,
                        'breadcrumb' => $this->category->title . ' > ' . 
                                       $this->subCategory->title . ' > ' . 
                                       $this->segment->title . ' > ' . 
                                       $this->subSegment->title
                    ];
                }
            ),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'formatted_created_at' => $this->created_at?->format('M d, Y'),
        ];
    }
}
