<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Segment;
use App\Models\SubSegment;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all products with filtering and pagination
     */
    public function index(Request $request)
    {
         $query = Product::where('status', true)
            ->with([
                'category:id,title,slug',
                'subCategory:id,title,slug',
                'segment:id,title,slug',
                'subSegment:id,title,slug',
                'images' => function($q) {
                    $q->where('is_primary', true)->orWhere('sort_order', 0);
                }
            ]);
        
        // Apply filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }
        
        if ($request->filled('segment_id')) {
            $query->where('segment_id', $request->segment_id);
        }
        
        if ($request->filled('sub_segment_id')) {
            $query->where('sub_segment_id', $request->sub_segment_id);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Sort options
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $allowedSortFields = ['title', 'price', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $perPage = $request->input('per_page', 20);
        $products = $query->paginate($perPage);
        
        // Transform the data to include image URLs
        // $products->getCollection()->transform(function ($product) {
        //     $product->primary_image_url = $product->main_image_url;
        //     return $product;
        // });
        
        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
            'message' => 'Products retrieved successfully'
        ]);
    }
    
    /**
     * Get single product with full details
     */
      public function show(Product $product)
    {
        $product->load([
            'category',
            'subCategory',
            'segment',
            'subSegment',
            'images' => function($q) {
                $q->orderBy('sort_order', 'asc');
            }
        ]);
        
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
            'message' => 'Product retrieved successfully'
        ]);
    }
    
    /**
     * Get products by category
     */
    public function getByCategory(Category $category, Request $request)
    {
        $query = $category->products()
            ->where('status', true)
            ->with([
                'subCategory:id,title,slug',
                'segment:id,title,slug',
                'subSegment:id,title,slug',
                'images' => function($q) {
                    $q->where('is_primary', true)->orWhere('sort_order', 0);
                }
            ]);
        
        return $this->applyFiltersAndPaginate($query, $request, 'Products by category retrieved successfully');
    }
    
    /**
     * Get products by sub category
     */
    public function getBySubCategory(SubCategory $subCategory, Request $request)
    {
        $query = $subCategory->products()
            ->where('status', true)
            ->with([
                'category:id,title,slug',
                'segment:id,title,slug',
                'subSegment:id,title,slug',
                'images' => function($q) {
                    $q->where('is_primary', true)->orWhere('sort_order', 0);
                }
            ]);
        
        return $this->applyFiltersAndPaginate($query, $request, 'Products by sub category retrieved successfully');
    }
    
    /**
     * Get products by segment
     */
    public function getBySegment(Segment $segment, Request $request)
    {
        $query = $segment->products()
            ->where('status', true)
            ->with([
                'category:id,title,slug',
                'subCategory:id,title,slug',
                'subSegment:id,title,slug',
                'images' => function($q) {
                    $q->where('is_primary', true)->orWhere('sort_order', 0);
                }
            ]);
        
        return $this->applyFiltersAndPaginate($query, $request, 'Products by segment retrieved successfully');
    }
    
    /**
     * Get products by sub segment
     */
    public function getBySubSegment(SubSegment $subSegment, Request $request)
    {
        $query = $subSegment->products()
            ->where('status', true)
            ->with([
                'category:id,title,slug',
                'subCategory:id,title,slug',
                'segment:id,title,slug',
                'images' => function($q) {
                    $q->where('is_primary', true)->orWhere('sort_order', 0);
                }
            ]);
        
        return $this->applyFiltersAndPaginate($query, $request, 'Products by sub segment retrieved successfully');
    }
    
    /**
     * Apply common filters and pagination
     */
    private function applyFiltersAndPaginate($query, Request $request, $message)
    {
        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Sort options
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $allowedSortFields = ['title', 'price', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $perPage = $request->input('per_page', 20);
        $products = $query->paginate($perPage);
        
        // Transform the data to include image URLs
        $products->getCollection()->transform(function ($product) {
            $product->primary_image_url = $product->main_image_url;
            return $product;
        });
        
        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => $message
        ]);
    }
}