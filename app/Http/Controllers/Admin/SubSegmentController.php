<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubSegment;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Segment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
class SubSegmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subSegments = SubSegment::with(['segment.subCategory.category']);
            
            return DataTables::of($subSegments)
                            ->addIndexColumn()

                ->addColumn('action', function ($subSegment) {
                    return view('admin.sub-segments.actions', compact('subSegment'));
                })
                ->addColumn('hierarchy', function ($subSegment) {
                    return $subSegment->segment->subCategory->category->title . ' > ' . 
                           $subSegment->segment->subCategory->title . ' > ' . 
                           $subSegment->segment->title;
                })
                ->addColumn('status', function ($subSegment) {
                    return $subSegment->status ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.sub-segments.index');
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        return view('admin.sub-segments.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|unique:sub_segments,title',
            'segment_id' => 'required|exists:segments,id',
            'status' => 'boolean'
        ]);

        SubSegment::create($request->all());

        return redirect()->route('admin.sub-segments.index')
            ->with('success', 'Sub Segment created successfully.');
    }

    public function edit(SubSegment $subSegment)
    {
        $categories = Category::where('status', true)->get();
        $subCategories = SubCategory::where('category_id', $subSegment->segment->subCategory->category_id)->get();
        $segments = Segment::where('sub_category_id', $subSegment->segment->sub_category_id)->get();
        return view('admin.sub-segments.edit', compact('subSegment', 'categories', 'subCategories', 'segments'));
    }

    public function update(Request $request, SubSegment $subSegment)
    {
        $request->validate([
            'title' => 'required|max:255|unique:sub_segments,title,' . $subSegment->id,
            'segment_id' => 'required|exists:segments,id',
            'status' => 'boolean'
        ]);

        $subSegment->update($request->all());

        return redirect()->route('admin.sub-segments.index')
            ->with('success', 'Sub Segment updated successfully.');
    }

    public function destroy(SubSegment $subSegment)
    {
        try {
            $subSegment->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Cannot delete sub segment with related records.']);
        }
    }
}
