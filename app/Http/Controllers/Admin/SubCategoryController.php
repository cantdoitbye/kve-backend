<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
class SubCategoryController extends Controller
{
  public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SubCategory::with('category');
            
            // Apply category filter
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            
            // Apply status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($subCategory) {
                    return view('admin.sub-categories.actions', compact('subCategory'))->render();
                })
                ->addColumn('category_name', function ($subCategory) {
                    return $subCategory->category ? $subCategory->category->title : 'N/A';
                })
                ->addColumn('status', function ($subCategory) {
                    if ($subCategory->status) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->editColumn('created_at', function ($subCategory) {
                    return $subCategory->created_at->format('M d, Y');
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $categories = Category::where('status', true)->get();
        return view('admin.sub-categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        return view('admin.sub-categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|unique:sub_categories,title',
            'category_id' => 'required|exists:categories,id',
            'status' => 'boolean'
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? true : false;

        SubCategory::create($data);

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'Sub Category created successfully.');
    }

    public function show(SubCategory $subCategory)
    {
        $subCategory->load('category');
        return view('admin.sub-categories.show', compact('subCategory'));
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::where('status', true)->get();
        return view('admin.sub-categories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            'title' => 'required|max:255|unique:sub_categories,title,' . $subCategory->id,
            'category_id' => 'required|exists:categories,id',
            'status' => 'boolean'
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? true : false;

        $subCategory->update($data);

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'Sub Category updated successfully.');
    }

    public function destroy(SubCategory $subCategory)
    {
        try {
            // Check if sub category has related segments
            if ($subCategory->segments()->count() > 0) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cannot delete sub category. It has related segments.'
                ]);
            }

            // Check if sub category has related products
            if ($subCategory->products()->count() > 0) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cannot delete sub category. It has related products.'
                ]);
            }

            $subCategory->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error deleting sub category: ' . $e->getMessage()
            ]);
        }
    }
}
