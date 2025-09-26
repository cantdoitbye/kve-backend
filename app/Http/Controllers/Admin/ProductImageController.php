<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function index(Product $product)
    {
        $product->load('images');
        return view('admin.products.images.index', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt_texts.*' => 'nullable|string|max:255'
        ]);

        $sortOrder = ProductImage::where('product_id', $product->id)->max('sort_order') ?? -1;
        $altTexts = $request->input('alt_texts', []);

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('products', 'public');
            $sortOrder++;

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'alt_text' => $altTexts[$index] ?? $product->title,
                'sort_order' => $sortOrder,
                'is_primary' => false
            ]);
        }

        return redirect()->back()->with('success', 'Images uploaded successfully.');
    }

    public function destroy(ProductImage $productImage)
    {
        try {
            $productImage->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting image.']);
        }
    }

    public function setPrimary(ProductImage $productImage)
    {
        try {
            ProductImage::setPrimaryImage($productImage->product_id, $productImage->id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error setting primary image.']);
        }
    }

    public function updateOrder(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:product_images,id',
            'images.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            foreach ($request->input('images') as $imageData) {
                ProductImage::where('id', $imageData['id'])
                    ->where('product_id', $product->id)
                    ->update(['sort_order' => $imageData['sort_order']]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating image order.']);
        }
    }
}
