@extends('layouts.admin')

@section('title', 'Edit Segment - Admin Panel')
@section('page-title', 'Edit Segment: ' . $segment->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-edit me-2 text-primary"></i>Edit Segment
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.segments.update', $segment) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $segment->subCategory->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sub_category_id" class="form-label fw-bold">Sub Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('sub_category_id') is-invalid @enderror" 
                                        id="sub_category_id" name="sub_category_id" required>
                                    <option value="">Select Sub Category</option>
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ old('sub_category_id', $segment->sub_category_id) == $subCategory->id ? 'selected' : '' }}>
                                            {{ $subCategory->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sub_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $segment->title) }}" 
                               placeholder="Enter segment title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Slug will be auto-generated from title</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" 
                                   id="status" value="1" {{ old('status', $segment->status) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="status">
                                Active Status
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Update Segment
                        </button>
                        <a href="{{ route('admin.segments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#category_id').change(function() {
            const categoryId = $(this).val();
            const subCategorySelect = $('#sub_category_id');
            
            if (categoryId) {
                $.get(`/admin/products/sub-categories/${categoryId}`, function(data) {
                    const currentValue = '{{ old('sub_category_id', $segment->sub_category_id) }}';
                    subCategorySelect.html('<option value="">Select Sub Category</option>');
                    
                    data.forEach(function(item) {
                        const selected = item.id == currentValue ? 'selected' : '';
                        subCategorySelect.append(`<option value="${item.id}" ${selected}>${item.title}</option>`);
                    });
                });
            } else {
                subCategorySelect.html('<option value="">Select Sub Category</option>');
            }
        });
    });
</script>
@endpush