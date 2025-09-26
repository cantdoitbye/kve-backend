<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Segment;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
class SegmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $segments = Segment::with(['subCategory.category']);
            
            return DataTables::of($segments)
                ->addColumn('action', function ($segment) {
                    return view('admin.segments.actions', compact('segment'));
                })
                ->addColumn('hierarchy', function ($segment) {
                    return $segment->subCategory->category->title . ' > ' . $segment->subCategory->title;
                })
                ->addColumn('status', function ($segment) {
                    return $segment->status ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.segments.index');
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        return view('admin.segments.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|unique:segments,title',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'status' => 'boolean'
        ]);

        Segment::create($request->all());

        return redirect()->route('admin.segments.index')
            ->with('success', 'Segment created successfully.');
    }

    public function edit(Segment $segment)
    {
        $categories = Category::where('status', true)->get();
        $subCategories = SubCategory::where('category_id', $segment->subCategory->category_id)->get();
        return view('admin.segments.edit', compact('segment', 'categories', 'subCategories'));
    }

    public function update(Request $request, Segment $segment)
    {
        $request->validate([
            'title' => 'required|max:255|unique:segments,title,' . $segment->id,
            'sub_category_id' => 'required|exists:sub_categories,id',
            'status' => 'boolean'
        ]);

        $segment->update($request->all());

        return redirect()->route('admin.segments.index')
            ->with('success', 'Segment updated successfully.');
    }

    public function destroy(Segment $segment)
    {
        try {
            $segment->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Cannot delete segment with related records.']);
        }
    }
}
