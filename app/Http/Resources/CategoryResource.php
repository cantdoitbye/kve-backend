<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'products_count' => $this->whenCounted('products'),
            'sub_categories_count' => $this->whenCounted('subCategories'),
            'sub_categories' => SubCategoryResource::collection($this->whenLoaded('subCategories')),
            'recent_products' => ProductResource::collection($this->whenLoaded('products')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'formatted_created_at' => $this->created_at?->format('M d, Y'),
        ];
    }
}
