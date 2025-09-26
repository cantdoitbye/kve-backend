@extends('layouts.admin')

@section('title', 'Add Sub Segment - Admin Panel')
@section('page-title', 'Add New Sub Segment')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-plus me-2 text-success"></i>Create Sub Segment
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.sub-segments.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sub_category_id" class="form-label fw-bold">Sub Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('sub_category_id') is-invalid @enderror" 
                                        id="sub_category_id" name="sub_category_id" required disabled>
                                    <option value="">Select Sub Category</option>
                                </select>
                                @error('sub_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="segment_id" class="form-label fw-bold">Segment <span class="text-danger">*</span></label>
                                <select class="form-select @error('segment_id') is-invalid @enderror" 
                                        id="segment_id" name="segment_id" required disabled>
                                    <option value="">Select Segment</option>
                                </select>
                                @error('segment_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Enter sub segment title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Slug will be auto-generated from title</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" 
                                   id="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="status">
                                Active Status
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Save Sub Segment
                        </button>
                        <a href="{{ route('admin.sub-segments.index') }}" class="btn btn-secondary">
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
            resetSubsequentDropdowns(['#sub_category_id', '#segment_id']);
            
            if (categoryId) {
                loadSubCategories(categoryId);
            }
        });

        $('#sub_category_id').change(function() {
            const subCategoryId = $(this).val();
            resetSubsequentDropdowns(['#segment_id']);
            
            if (subCategoryId) {
                loadSegments(subCategoryId);
            }
        });

        function loadSubCategories(categoryId) {
            $.get(`/admin/products/sub-categories/${categoryId}`, function(data) {
                const select = $('#sub_category_id');
                select.prop('disabled', false).html('<option value="">Select Sub Category</option>');
                
                data.forEach(function(item) {
                    select.append(`<option value="${item.id}">${item.title}</option>`);
                });
            });
        }

        function loadSegments(subCategoryId) {
            $.get(`/admin/products/segments/${subCategoryId}`, function(data) {
                const select = $('#segment_id');
                select.prop('disabled', false).html('<option value="">Select Segment</option>');
                
                data.forEach(function(item) {
                    select.append(`<option value="${item.id}">${item.title}</option>`);
                });
            });
        }

        function resetSubsequentDropdowns(selectors) {
            selectors.forEach(function(selector) {
                $(selector).prop('disabled', true).html('<option value="">Select Option</option>');
            });
        }
    });
</script>
@endpush