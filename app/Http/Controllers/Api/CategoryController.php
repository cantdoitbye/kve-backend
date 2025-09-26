<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryHierarchyResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Segment;
use App\Models\SubSegment;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories
     */
     public function index(Request $request)
    {
        $query = Category::where('status', true);
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $perPage = $request->input('per_page', 20);
        $categories = $query->with(['subCategories' => function($q) {
            $q->where('status', true)->withCount('segments');
        }])
        ->withCount(['subCategories', 'products'])
        ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories),
            'message' => 'Categories retrieved successfully'
        ]);
    }
    
    /**
     * Get single category with details
     */
     public function show(Category $category)
    {
        $category->load([
            'subCategories' => function($q) {
                $q->where('status', true)
                  ->with(['segments' => function($sq) {
                      $sq->where('status', true)
                        ->withCount('subSegments');
                  }])
                  ->withCount(['segments', 'products']);
            }
        ]);
        
        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
            'message' => 'Category retrieved successfully'
        ]);
    }
    
    /**
     * Get sub categories for a specific category
     */
    public function getSubCategories(Category $category)
    {
        $subCategories = $category->subCategories()
            ->where('status', true)
            ->with(['segments' => function($q) {
                $q->where('status', true)->withCount('subSegments');
            }])
            ->withCount(['segments', 'products'])
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $subCategories,
            'message' => 'Sub categories retrieved successfully'
        ]);
    }
    
    /**
     * Get all sub categories with optional category filter
     */
     public function getSubCategoriesAll(Request $request)
    {
        $query = SubCategory::where('status', true)->with('category');
            
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $perPage = $request->input('per_page', 20);
        $subCategories = $query->withCount(['segments', 'products'])->paginate($perPage);
            
        return response()->json([
            'success' => true,
            'data' => SubCategoryResource::collection($subCategories),
            'message' => 'Sub categories retrieved successfully'
        ]);
    }
    
    /**
     * Get single sub category
     */
    public function showSubCategory(SubCategory $subCategory)
    {
        $subCategory->load([
            'category',
            'segments' => function($q) {
                $q->where('status', true)
                  ->with(['subSegments' => function($sq) {
                      $sq->where('status', true);
                  }])
                  ->withCount('subSegments');
            }
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $subCategory,
            'message' => 'Sub category retrieved successfully'
        ]);
    }
    
    /**
     * Get segments for a sub category
     */
    public function getSegments(SubCategory $subCategory)
    {
        $segments = $subCategory->segments()
            ->where('status', true)
            ->with(['subSegments' => function($q) {
                $q->where('status', true);
            }])
            ->withCount(['subSegments', 'products'])
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $segments,
            'message' => 'Segments retrieved successfully'
        ]);
    }
    
    /**
     * Get all segments with optional filters
     */
    public function getSegmentsAll(Request $request)
    {
        $query = Segment::where('status', true)
            ->with(['subCategory.category']);
            
        if ($request->filled('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }
        
        if ($request->filled('category_id')) {
            $query->whereHas('subCategory', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $perPage = $request->input('per_page', 20);
        $segments = $query->withCount(['subSegments', 'products'])
            ->paginate($perPage);
            
        return response()->json([
            'success' => true,
            'data' => $segments,
            'message' => 'Segments retrieved successfully'
        ]);
    }
    
    /**
     * Get single segment
     */
    public function showSegment(Segment $segment)
    {
        $segment->load([
            'subCategory.category',
            'subSegments' => function($q) {
                $q->where('status', true);
            }
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $segment,
            'message' => 'Segment retrieved successfully'
        ]);
    }
    
    /**
     * Get sub segments for a segment
     */
    public function getSubSegments(Segment $segment)
    {
        $subSegments = $segment->subSegments()
            ->where('status', true)
            ->withCount('products')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $subSegments,
            'message' => 'Sub segments retrieved successfully'
        ]);
    }
    
    /**
     * Get all sub segments with optional filters
     */
    public function getSubSegmentsAll(Request $request)
    {
        $query = SubSegment::where('status', true)
            ->with(['segment.subCategory.category']);
            
        if ($request->filled('segment_id')) {
            $query->where('segment_id', $request->segment_id);
        }
        
        if ($request->filled('sub_category_id')) {
            $query->whereHas('segment', function($q) use ($request) {
                $q->where('sub_category_id', $request->sub_category_id);
            });
        }
        
        if ($request->filled('category_id')) {
            $query->whereHas('segment.subCategory', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $perPage = $request->input('per_page', 20);
        $subSegments = $query->withCount('products')
            ->paginate($perPage);
            
        return response()->json([
            'success' => true,
            'data' => $subSegments,
            'message' => 'Sub segments retrieved successfully'
        ]);
    }
    
    /**
     * Get single sub segment
     */
    public function showSubSegment(SubSegment $subSegment)
    {
        $subSegment->load([
            'segment.subCategory.category'
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $subSegment,
            'message' => 'Sub segment retrieved successfully'
        ]);
    }
    
    /**
     * Get complete hierarchy for navbar
     */
    public function getNavbarHierarchy()
    {
        $categories = Category::where('status', true)
            ->with([
                'subCategories' => function($q) {
                    $q->where('status', true)
                      ->with([
                          'segments' => function($sq) {
                              $sq->where('status', true)
                                ->with([
                                    'subSegments' => function($ssq) {
                                        $ssq->where('status', true)
                                           ->withCount('products');
                                    }
                                ])
                                ->withCount('products');
                          }
                      ])
                      ->withCount('products');
                }
            ])
            ->withCount('products')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => CategoryHierarchyResource::collection($categories),
            'message' => 'Navbar hierarchy retrieved successfully'
        ]);
    }
    
    /**
     * Get complete hierarchy for a specific category
     */
    public function getHierarchy(Category $category)
    {
        $category->load([
            'subCategories' => function($q) {
                $q->where('status', true)
                  ->with([
                      'segments' => function($sq) {
                          $sq->where('status', true)
                            ->with([
                                'subSegments' => function($ssq) {
                                    $ssq->where('status', true)
                                       ->withCount('products');
                                }
                            ])
                            ->withCount('products');
                      }
                  ])
                  ->withCount('products');
            }
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Category hierarchy retrieved successfully'
        ]);
    }
    
    /**
     * Get category children for dynamic navbar
     */
    public function getCategoryChildren(Category $category, Request $request)
    {
        $type = $request->input('type', 'sub_categories'); // sub_categories, segments, sub_segments
        
        switch ($type) {
            case 'sub_categories':
                $data = $category->subCategories()->where('status', true)->get();
                break;
            case 'segments':
                $data = Segment::whereHas('subCategory', function($q) use ($category) {
                    $q->where('category_id', $category->id);
                })->where('status', true)->get();
                break;
            case 'sub_segments':
                $data = SubSegment::whereHas('segment.subCategory', function($q) use ($category) {
                    $q->where('category_id', $category->id);
                })->where('status', true)->get();
                break;
            default:
                $data = [];
        }
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => ucfirst($type) . ' retrieved successfully'
        ]);
    }

}
