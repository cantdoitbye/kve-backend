<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Segment;
use App\Models\SubSegment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with(['category', 'subCategory', 'segment', 'subSegment', 'images']);
            
            if ($request->filled('category_id')) {
                $products->where('category_id', $request->category_id);
            }
            
            return DataTables::of($products)
                ->addColumn('action', function ($product) {
                    return view('admin.products.actions', compact('product'));
                })
                ->addColumn('image', function ($product) {
                    if ($product->main_image_url) {
                        return '<img src="' . $product->main_image_url . '" width="50" height="50" class="rounded object-cover">';
                    }
                    return '<div class="bg-gray-200 w-12 h-12 rounded flex items-center justify-content-center"><i class="fas fa-image text-gray-400"></i></div>';
                })
                ->addColumn('image_count', function ($product) {
                    $count = $product->getImageCount();
                    return $count > 0 ? '<span class="badge bg-info">' . $count . ' images</span>' : '<span class="badge bg-secondary">No images</span>';
                })
                ->addColumn('category_hierarchy', function ($product) {
                    return $product->category->title . ' > ' . 
                           $product->subCategory->title . ' > ' . 
                           $product->segment->title . ' > ' . 
                           $product->subSegment->title;
                })
                ->editColumn('price', function ($product) {
                    return '₹' . number_format($product->price, 2);
                })
                ->addColumn('status', function ($product) {
                    return $product->status ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>';
                })
                   ->addColumn('featured', function ($product) {
        $checked = $product->is_featured ? 'checked' : '';
        $badgeClass = $product->is_featured ? 'bg-warning' : 'bg-secondary';
        $badgeText = $product->is_featured ? 'Featured' : 'Regular';
        
        return '
            <div class="form-check form-switch">
                <input class="form-check-input" 
                       type="checkbox" 
                       role="switch"
                       id="featured-' . $product->id . '" 
                       ' . $checked . '
                       onchange="toggleFeatured(' . $product->id . ', this)">
                <label class="form-check-label" for="featured-' . $product->id . '">
                    <span class="badge ' . $badgeClass . ' text-white">
                        <i class="fas fa-star me-1"></i>
                        ' . $badgeText . '
                    </span>
                </label>
            </div>';
    })
                ->rawColumns(['action', 'image', 'image_count', 'status','featured'])
                ->make(true);
        }

        $categories = Category::all();
        return view('admin.products.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

   // Replace your store() and update() methods in ProductController with these clean versions
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|max:255|unique:products,title',
        'sku' => 'nullable|string|max:100',
        'short_description' => 'required',
        'price' => 'required|numeric|min:0',
        'product_details' => 'required',
        'category_id' => 'required|exists:categories,id',
        'sub_category_id' => 'required|exists:sub_categories,id',
        'segment_id' => 'required|exists:segments,id',
        'sub_segment_id' => 'required|exists:sub_segments,id',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        'alt_texts.*' => 'nullable|string|max:255',
         'disclaimer' => 'nullable|string',              // ADD THIS
    'features.*' => 'nullable|string|max:255', 
        
        // New optional fields validation
        'service_info.*.link_text' => 'nullable|string|max:255',
        'service_info.*.link' => 'nullable|url|max:500',
        'included.*' => 'nullable|string|max:255',
        'documentation.*.link_text' => 'nullable|string|max:255',
        'documentation.*.link' => 'nullable|url|max:500',
        'partner_label' => 'nullable|string|max:255',
        'partner_link' => 'nullable|url|max:500',
        'input_types' => 'nullable|json',
        'output_types' => 'nullable|json',
        'is_sustainable' => 'nullable|boolean',
    ]);

    DB::beginTransaction();
    
    try {
        // Prepare base data (exclude special fields that need processing)
        $data = $request->except([
            'images', 
            'alt_texts', 
            'service_info', 
            'documentation',
            'partner_label',
            'partner_link',
            'input_types', 
            'output_types', 
            'included',
             'features' 
        ]);
        
        // Handle service_info - filter out empty entries
        if ($request->has('service_info')) {
            $serviceInfo = array_filter($request->service_info, function($item) {
                return !empty($item['link_text']) && !empty($item['link']);
            });
            $data['service_info'] = !empty($serviceInfo) ? array_values($serviceInfo) : null;
        } else {
            $data['service_info'] = null;
        }
        
        // Handle included items - filter out empty entries
        if ($request->has('included')) {
            $included = array_filter($request->included, function($item) {
                return !empty($item);
            });
            $data['included'] = !empty($included) ? array_values($included) : null;
        } else {
            $data['included'] = null;
        }
        
        // Handle documentation - multiple entries
        if ($request->has('documentation')) {
            $documentation = array_filter($request->documentation, function($item) {
                return !empty($item['link_text']) && !empty($item['link']);
            });
            $data['documentation'] = !empty($documentation) ? array_values($documentation) : null;
        } else {
            $data['documentation'] = null;
        }
        
        // Handle partner - single entry
        if ($request->filled('partner_label') && $request->filled('partner_link')) {
            $data['partner'] = [
                'label' => $request->partner_label,
                'link' => $request->partner_link
            ];
        } else {
            $data['partner'] = null;
        }

        if ($request->has('features')) {
    $features = array_filter($request->features, function($item) {
        return !empty($item);
    });
    $data['features'] = !empty($features) ? array_values($features) : null;
} else {
    $data['features'] = null;
}
        
        // Handle input_types and output_types - decode JSON strings
        $data['input_types'] = $request->filled('input_types') ? json_decode($request->input_types, true) : null;
        $data['output_types'] = $request->filled('output_types') ? json_decode($request->output_types, true) : null;
        
        // Handle is_sustainable checkbox
        $data['is_sustainable'] = $request->has('is_sustainable') ? true : false;
        
        // Create product
        $product = Product::create($data);
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $this->uploadProductImages($product, $request->file('images'), $request->input('alt_texts', []));
        }
        
        DB::commit();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
            
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
    }
}


    public function show(Product $product)
    {
        $product->load(['category', 'subCategory', 'segment', 'subSegment', 'images']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $segments = Segment::where('sub_category_id', $product->sub_category_id)->get();
        $subSegments = SubSegment::where('segment_id', $product->segment_id)->get();
        
        $product->load('images');
        
        return view('admin.products.edit', compact('product', 'categories', 'subCategories', 'segments', 'subSegments'));
    }

    public function toggleFeatured(Request $request, Product $product)
{
    $request->validate([
        'is_featured' => 'required|boolean'
    ]);
    
    $product->update([
        'is_featured' => $request->is_featured
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Featured status updated successfully',
        'is_featured' => $product->is_featured
    ]);
}

 public function update(Request $request, Product $product)
{
    $request->validate([
        'title' => 'required|max:255|unique:products,title,' . $product->id,
        'sku' => 'nullable|string|max:100',
        'short_description' => 'required',
        'price' => 'required|numeric|min:0',
        'product_details' => 'required',
        'category_id' => 'required|exists:categories,id',
        'sub_category_id' => 'required|exists:sub_categories,id',
        'segment_id' => 'required|exists:segments,id',
        'sub_segment_id' => 'required|exists:sub_segments,id',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        'alt_texts.*' => 'nullable|string|max:255',
           'disclaimer' => 'nullable|string',              // ADD THIS
    'features.*' => 'nullable|string|max:255',
        
        // New optional fields validation
        'service_info.*.link_text' => 'nullable|string|max:255',
        'service_info.*.link' => 'nullable|url|max:500',
        'included.*' => 'nullable|string|max:255',
        'documentation.*.link_text' => 'nullable|string|max:255',
        'documentation.*.link' => 'nullable|url|max:500',
        'partner_label' => 'nullable|string|max:255',
        'partner_link' => 'nullable|url|max:500',
        'input_types' => 'nullable|json',
        'output_types' => 'nullable|json',
        'is_sustainable' => 'nullable|boolean',
    ]);

    DB::beginTransaction();
    
    try {
        // Prepare base data (exclude special fields that need processing)
        $data = $request->except([
            'images', 
            'alt_texts', 
            'remove_images', 
            'service_info', 
            'documentation',
            'partner_label',
            'partner_link',
            'input_types', 
            'output_types', 
            'included',
            'features'          // ADD THIS
        ]);
        
        // Handle service_info - filter out empty entries
        if ($request->has('service_info')) {
            $serviceInfo = array_filter($request->service_info, function($item) {
                return !empty($item['link_text']) && !empty($item['link']);
            });
            $data['service_info'] = !empty($serviceInfo) ? array_values($serviceInfo) : null;
        } else {
            $data['service_info'] = null;
        }
        
        // Handle included items - filter out empty entries
        if ($request->has('included')) {
            $included = array_filter($request->included, function($item) {
                return !empty($item);
            });
            $data['included'] = !empty($included) ? array_values($included) : null;
        } else {
            $data['included'] = null;
        }
        
        // Handle documentation - multiple entries
        if ($request->has('documentation')) {
            $documentation = array_filter($request->documentation, function($item) {
                return !empty($item['link_text']) && !empty($item['link']);
            });
            $data['documentation'] = !empty($documentation) ? array_values($documentation) : null;
        } else {
            $data['documentation'] = null;
        }
        
        // Handle partner - single entry
        if ($request->filled('partner_label') && $request->filled('partner_link')) {
            $data['partner'] = [
                'label' => $request->partner_label,
                'link' => $request->partner_link
            ];
        } else {
            $data['partner'] = null;
        }

        if ($request->has('features')) {
    $features = array_filter($request->features, function($item) {
        return !empty($item);
    });
    $data['features'] = !empty($features) ? array_values($features) : null;
} else {
    $data['features'] = null;
}
        
        // Handle input_types and output_types - decode JSON strings
        $data['input_types'] = $request->filled('input_types') ? json_decode($request->input_types, true) : null;
        $data['output_types'] = $request->filled('output_types') ? json_decode($request->output_types, true) : null;
        
        // Handle is_sustainable checkbox
        $data['is_sustainable'] = $request->has('is_sustainable') ? true : false;
        
        // Update product
        $product->update($data);
        
        // Handle image removal
        if ($request->filled('remove_images')) {
            $this->removeProductImages($request->input('remove_images'));
        }
        
        // Handle new image uploads
        if ($request->hasFile('images')) {
            $this->uploadProductImages($product, $request->file('images'), $request->input('alt_texts', []));
        }
        
        DB::commit();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
            
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Error updating product: ' . $e->getMessage())->withInput();
    }
}

    public function destroy(Product $product)
    {
        try {
            $product->delete(); // This will trigger the model boot method to delete images
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting product.']);
        }
    }

    // Image management methods
    public function removeImage(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id'
        ]);

        try {
            $image = ProductImage::findOrFail($request->image_id);
            $productId = $image->product_id;
            $wasPrimary = $image->is_primary;
            
            $image->delete();
            
            // If the deleted image was primary, set the next image as primary
            if ($wasPrimary) {
                $nextImage = ProductImage::where('product_id', $productId)->ordered()->first();
                if ($nextImage) {
                    $nextImage->update(['is_primary' => true]);
                }
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error removing image.']);
        }
    }

    public function setPrimaryImage(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id'
        ]);

        try {
            $image = ProductImage::findOrFail($request->image_id);
            ProductImage::setPrimaryImage($image->product_id, $image->id);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error setting primary image.']);
        }
    }

    public function updateImageOrder(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:product_images,id',
            'images.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            foreach ($request->input('images') as $imageData) {
                ProductImage::where('id', $imageData['id'])
                    ->update(['sort_order' => $imageData['sort_order']]);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating image order.']);
        }
    }

    // Private helper methods
    private function uploadProductImages(Product $product, array $images, array $altTexts = [])
    {
        $sortOrder = ProductImage::where('product_id', $product->id)->max('sort_order') ?? -1;
        
        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');
            $sortOrder++;
            
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'alt_text' => $altTexts[$index] ?? $product->title,
                'sort_order' => $sortOrder,
                'is_primary' => false // Will be set automatically by model boot method if it's the first image
            ]);
        }
    }

    private function removeProductImages(array $imageIds)
    {
        $images = ProductImage::whereIn('id', $imageIds)->get();
        
        foreach ($images as $image) {
            $image->delete();
        }
    }

    // AJAX endpoints for dependent dropdowns (unchanged)
    public function getSubCategories($categoryId)
    {
        return SubCategory::where('category_id', $categoryId)->get();
    }

    public function getSegments($subCategoryId)
    {
        return Segment::where('sub_category_id', $subCategoryId)->get();
    }

    public function getSubSegments($segmentId)
    {
        return SubSegment::where('segment_id', $segmentId)->get();
    }
}
