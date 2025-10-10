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
            'sku' => $this->sku,
            'short_description' => $this->short_description,
            'product_details' => $this->product_details,
            'specifications' => $this->specifications,
            'price' => $this->price,
            'formatted_price' => '₹' . number_format($this->price, 2),
            'status' => $this->status,
            'status_label' => $this->status ? 'Active' : 'Inactive',
            
            // Image Information
            'primary_image_url' => $this->main_image_url,
            'has_images' => $this->hasImages(),
            'images_count' => $this->getImageCount(),
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            
            'disclaimer' => $this->disclaimer,
            'features' => $this->features,
            

            // Optional Fields - Service Information (always present, even if null)
            'service_info' => $this->service_info,
            'has_service_info' => !is_null($this->service_info) && is_array($this->service_info) && count($this->service_info) > 0,
            
            // Optional Fields - What's Included (always present, even if null)
            'included' => $this->included,
            'has_included' => !is_null($this->included) && is_array($this->included) && count($this->included) > 0,
            
            // Optional Fields - Documentation (always present, even if null)
            'documentation' => $this->documentation,
            'has_documentation' => !is_null($this->documentation) && is_array($this->documentation),
            
                'partner' => $this->partner,
            'has_partner' => !is_null($this->partner) && is_array($this->partner) && isset($this->partner['link']) && isset($this->partner['label']),
            

            // Optional Fields - Input Types (always present, even if null)
            'input_types' => $this->input_types,
            'has_input_types' => !is_null($this->input_types) && is_array($this->input_types) && count($this->input_types) > 0,
            
            // Optional Fields - Output Types (always present, even if null)
            'output_types' => $this->output_types,
            'has_output_types' => !is_null($this->output_types) && is_array($this->output_types) && count($this->output_types) > 0,
            
            'is_sustainable' => $this->is_sustainable,

            // Category Relations
            'category' => new CategoryResource($this->whenLoaded('category')),
            'sub_category' => new SubCategoryResource($this->whenLoaded('subCategory')),
            'segment' => new SegmentResource($this->whenLoaded('segment')),
            'sub_segment' => new SubSegmentResource($this->whenLoaded('subSegment')),
            
            // Hierarchy Information
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
            
            // Timestamps
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'formatted_created_at' => $this->created_at?->format('M d, Y'),
        ];
    }
}