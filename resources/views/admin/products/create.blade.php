@extends('layouts.admin')

@section('title', 'Add Product - Admin Panel')
@section('page-title', 'Add New Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-plus me-2 text-success"></i>Create Product
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Product Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Enter product title" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label fw-bold">Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" 
                                       placeholder="0.00" step="0.01" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label fw-bold">Short Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                  id="short_description" name="short_description" rows="3" 
                                  placeholder="Brief product description" required>{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category Hierarchy -->
                    <div class="row">
                        <div class="col-md-3">
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

                        <div class="col-md-3">
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

                        <div class="col-md-3">
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

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="sub_segment_id" class="form-label fw-bold">Sub Segment <span class="text-danger">*</span></label>
                                <select class="form-select @error('sub_segment_id') is-invalid @enderror" 
                                        id="sub_segment_id" name="sub_segment_id" required disabled>
                                    <option value="">Select Sub Segment</option>
                                </select>
                                @error('sub_segment_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="product_details" class="form-label fw-bold">Product Details <span class="text-danger">*</span></label>
                        <textarea class="form-control tinymce @error('product_details') is-invalid @enderror" 
                                  id="product_details" name="product_details" rows="8">{{ old('product_details') }}</textarea>
                        @error('product_details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="specifications" class="form-label fw-bold">Specifications</label>
                        <textarea class="form-control tinymce @error('specifications') is-invalid @enderror" 
                                  id="specifications" name="specifications" rows="6">{{ old('specifications') }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Enhanced Image Upload Section -->
                    <div class="mb-3">
                        <label for="images" class="form-label fw-bold">Product Images</label>
                        <div class="border rounded p-3 bg-light">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                           id="images" name="images[]" multiple accept="image/*">
                                    @error('images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Select multiple images for the product. Maximum 2MB each. First image will be set as primary.</small>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="addAltTextFields()">
                                        <i class="fas fa-tags me-1"></i>Add Alt Text
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Alt text fields (will be populated by JavaScript) -->
                            <div id="alt-text-container" class="mt-3" style="display: none;">
                                <h6 class="text-muted">Image Alt Text (for SEO):</h6>
                            </div>
                            
                            <!-- Image preview -->
                            <div id="image-preview" class="mt-3"></div>
                        </div>
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
                            <i class="fas fa-save me-1"></i>Save Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
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
    // Using a completely free WYSIWYG editor (Summernote) to avoid API key warnings.
    // Summernote is open-source and can be loaded via CDN.
    // Load Summernote CSS/JS dynamically and initialize it for the same selector.
    // Include CSS
    $('head').append('<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">');
    // Include JS
    $.getScript('https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js', function() {
        $('.tinymce').each(function() {
            $(this).summernote({
                placeholder: 'Write here...',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video', 'table']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        // Optional: handle image uploads via form submit or async endpoint.
                        // For now, disallow direct uploads to keep editor fully client-side without external APIs.
                        Swal.fire('Image Upload', 'Direct image upload is disabled. Please attach images using the Product Images field.', 'info');
                    }
                }
            });
        });
    });

    $(document).ready(function() {
        // Dependent dropdowns for category hierarchy
        $('#category_id').change(function() {
            const categoryId = $(this).val();
            resetSubsequentDropdowns(['#sub_category_id', '#segment_id', '#sub_segment_id']);
            
            if (categoryId) {
                loadSubCategories(categoryId);
            }
        });

        $('#sub_category_id').change(function() {
            const subCategoryId = $(this).val();
            resetSubsequentDropdowns(['#segment_id', '#sub_segment_id']);
            
            if (subCategoryId) {
                loadSegments(subCategoryId);
            }
        });

        $('#segment_id').change(function() {
            const segmentId = $(this).val();
            resetSubsequentDropdowns(['#sub_segment_id']);
            
            if (segmentId) {
                loadSubSegments(segmentId);
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

        function loadSubSegments(segmentId) {
            $.get(`/admin/products/sub-segments/${segmentId}`, function(data) {
                const select = $('#sub_segment_id');
                select.prop('disabled', false).html('<option value="">Select Sub Segment</option>');
                
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

        // Enhanced image preview with alt text
        $('#images').change(function() {
            const files = this.files;
            $('#image-preview').empty();
            $('#alt-text-container').hide().empty();
            
            if (files.length > 0) {
                const previewContainer = $('<div class="row g-2"></div>');
                
                Array.from(files).forEach(function(file, index) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = $(`
                                <div class="col-md-3">
                                    <div class="card">
                                        <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <small class="text-muted">${file.name}</small>
                                            ${index === 0 ? '<br><span class="badge bg-primary">Primary</span>' : ''}
                                        </div>
                                    </div>
                                </div>
                            `);
                            previewContainer.append(col);
                        };
                        reader.readAsDataURL(file);
                    }
                });
                
                $('#image-preview').append(previewContainer);
            }
        });
    });

    function addAltTextFields() {
        const files = document.getElementById('images').files;
        const container = $('#alt-text-container');
        
        if (files.length === 0) {
            Swal.fire('Info', 'Please select images first', 'info');
            return;
        }
        
        container.empty().show();
        container.append('<h6 class="text-muted mb-2">Image Alt Text (for SEO):</h6>');
        
        Array.from(files).forEach(function(file, index) {
            const field = $(`
                <div class="mb-2">
                    <label class="form-label small">${file.name}:</label>
                    <input type="text" class="form-control form-control-sm" 
                           name="alt_texts[${index}]" 
                           placeholder="Alt text for ${file.name}">
                </div>
            `);
            container.append(field);
        });
    }
</script>
@endpush