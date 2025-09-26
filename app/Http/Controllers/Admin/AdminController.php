<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Segment;
use App\Models\SubSegment;

class AdminController extends Controller
{
      public function dashboard()
    {
        $stats = [
            'categories' => Category::count(),
            'sub_categories' => SubCategory::count(),
            'segments' => Segment::count(),
            'sub_segments' => SubSegment::count(),
            'products' => Product::count(),
            'active_products' => Product::where('status', true)->count(),
        ];

        $recent_products = Product::with(['category', 'subCategory'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_products'));
    }
}
