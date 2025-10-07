<?php
// resources/views/admin/products/images/index.blade.php
?>
@extends('layouts.admin')

@section('title', 'Manage Product Images - Admin Panel')
@section('page-title', 'Manage Images: ' . $product->title)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css">
<style>
    .sortable-ghost {
        opacity: 0.4;
        background: #f0f0f0;
    }
    
    .image-item {
        transition: all 0.3s ease;
        cursor: move;
    }
    
    .image-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    
    .primary-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        padding: 6px 12px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .image-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }
    
    .image-card {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
    }
    
    .image-card img {
        transition: transform 0.3s ease;
    }
    
    .image-card:hover img {
        transform: scale(1.05);
    }
    
    .alt-text-input {
        transition: border-color 0.3s ease;
    }
    
    .alt-text-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .drag-handle {
        cursor: grab;
        padding: 8px;
        background: rgba(0,0,0,0.05);
        border-radius: 4px;
        margin-bottom: 8px;
        text-align: center;
    }
    
    .drag-handle:active {
        cursor: grabbing;
    }
    
    .upload-zone {
        border: 2px dashed #cbd5e0;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s ease;
    }
    
    .upload-zone:hover {
        border-color: #667eea;
        background: #f7fafc;
    }
    
    .preview-container {
        max-height: 300px;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Product Info Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            @if($product->main_image_url)
                                <img src="{{ $product->main_image_url }}" 
                                     class="rounded me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="fw-bold mb-1">{{ $product->title }}</h4>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ $product->category->title }} > {{ $product->subCategory->title }} > 
                                    {{ $product->segment->title }} > {{ $product->subSegment->title }}
                                </p>
                                <span class="badge bg-info mt-2">
                                    <i class="fas fa-images me-1"></i>
                                    {{ $product->images->count() }} Images
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="btn-group">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i>Edit Product
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload New Images Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-cloud-upload-alt me-2 text-success"></i>Upload New Images
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.images.store', $product) }}" 
                      method="POST" 
                      enctype="multipart/form-data" 
                      id="upload-form">
                    @csrf
                    
                    <div class="upload-zone">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="mb-3 mb-md-0">
                                    <label for="new-images" class="form-label fw-bold">
                                        <i class="fas fa-images me-2"></i>Select Images
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('images.*') is-invalid @enderror" 
                                           id="new-images" 
                                           name="images[]" 
                                           multiple 
                                           accept="image/*" 
                                           required>
                                    @error('images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Select multiple images. Maximum 2MB each. Supported: JPEG, PNG, JPG, GIF
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <button type="button" 
                                        class="btn btn-outline-info mb-2" 
                                        onclick="toggleAltTextFields()">
                                    <i class="fas fa-tags me-1"></i>Add Alt Text
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-upload me-1"></i>Upload Images
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Alt text fields container -->
                    <div id="upload-alt-text-container" class="mt-3" style="display: none;">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-pen me-2"></i>Alt Text for SEO
                        </h6>
                        <div id="alt-text-fields"></div>
                    </div>
                    
                    <!-- Image preview container -->
                    <div id="upload-preview" class="mt-3"></div>
                </form>
            </div>
        </div>

        <!-- Current Images Management Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-images me-2 text-primary"></i>
                        Current Images <span class="badge bg-primary">{{ $product->images->count() }}</span>
                    </h5>
                    @if($product->images->count() > 1)
                        <div class="text-muted small">
                            <i class="fas fa-arrows-alt me-1"></i>Drag to reorder images
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($product->images->count() > 0)
                    <div id="sortable-images" class="row g-4">
                        @foreach($product->images as $image)
                        <div class="col-md-4 col-lg-3 image-item" data-id="{{ $image->id }}">
                            <div class="card image-card h-100">
                                <!-- Primary Badge -->
                                @if($image->is_primary)
                                    <span class="badge bg-primary primary-badge">
                                        <i class="fas fa-star me-1"></i>Primary
                                    </span>
                                @endif
                                
                                <!-- Action Buttons -->
                                <div class="image-actions">
                                    <div class="btn-group btn-group-sm">
                                        @if(!$image->is_primary)
                                            <button type="button" 
                                                    class="btn btn-success" 
                                                    onclick="setPrimaryImage({{ $image->id }})" 
                                                    title="Set as Primary"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="removeImage({{ $image->id }})" 
                                                title="Delete Image"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Drag Handle -->
                                @if($product->images->count() > 1)
                                <div class="drag-handle">
                                    <i class="fas fa-grip-horizontal text-muted"></i>
                                </div>
                                @endif

                                <!-- Image -->
                                <img src="{{ $image->image_url }}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;" 
                                     alt="{{ $image->alt_text }}"
                                     loading="lazy">
                                
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="form-label small text-muted mb-1">
                                            <i class="fas fa-tag me-1"></i>Alt Text for SEO
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-sm alt-text-input" 
                                               value="{{ $image->alt_text }}" 
                                               data-id="{{ $image->id }}"
                                               placeholder="Enter alt text"
                                               onblur="updateAltText({{ $image->id }}, this.value)">
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-sort-numeric-down me-1"></i>
                                            Order: <strong>{{ $image->sort_order }}</strong>
                                        </small>
                                        <small class="text-muted">
                                            {{ \Illuminate\Support\Str::limit(basename($image->image_path), 15) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($product->images->count() > 1)
                    <div class="text-center mt-4">
                        <button type="button" 
                                class="btn btn-primary" 
                                onclick="updateImageOrder()">
                            <i class="fas fa-save me-1"></i>Save New Order
                        </button>
                    </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-images fa-4x text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-2">No images uploaded yet</h5>
                        <p class="text-muted">Upload some images using the form above to get started</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let sortable;
    
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Initialize sortable if there are images
        if ($('#sortable-images .image-item').length > 1) {
            initializeSortable();
        }
        
        // Preview new images on selection
        $('#new-images').change(function() {
            previewNewImages(this.files);
        });
    });
    
    function initializeSortable() {
        const sortableContainer = document.getElementById('sortable-images');
        if (sortableContainer) {
            sortable = Sortable.create(sortableContainer, {
                animation: 200,
                ghostClass: 'sortable-ghost',
                handle: '.drag-handle',
                onEnd: function(evt) {
                    updateSortOrderDisplay();
                    
                    // Show save button notification
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'info',
                        title: 'Order changed! Click "Save New Order" to save.'
                    });
                }
            });
        }
    }
    
    function updateSortOrderDisplay() {
        $('#sortable-images .image-item').each(function(index) {
            $(this).find('small:contains("Order:")').html(`<i class="fas fa-sort-numeric-down me-1"></i>Order: <strong>${index}</strong>`);
        });
    }
    
    function updateImageOrder() {
        const images = [];
        $('#sortable-images .image-item').each(function(index) {
            images.push({
                id: $(this).data('id'),
                sort_order: index
            });
        });
        
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait while we update the image order',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: '{{ route("admin.products.images.update-order", $product) }}',
            type: 'POST',
            data: {
                images: images
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Image order updated successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error!', response.message || 'Failed to update order.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Failed to update image order.', 'error');
            }
        });
    }
    
    function setPrimaryImage(imageId) {
        Swal.fire({
            title: 'Set as Primary Image?',
            text: "This image will be displayed as the main product image.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, set as primary!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: `/admin/product-images/${imageId}/set-primary`,
                    type: 'POST'
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Primary image updated successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', result.value.message || 'Failed to set primary image.', 'error');
                }
            }
        });
    }
    
    function removeImage(imageId) {
        Swal.fire({
            title: 'Delete Image?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: `/admin/product-images/${imageId}`,
                    type: 'DELETE'
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Image has been deleted.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', result.value.message || 'Failed to delete image.', 'error');
                }
            }
        });
    }
    
    function updateAltText(imageId, altText) {
        // Debounce the update
        clearTimeout(window.altTextTimeout);
        window.altTextTimeout = setTimeout(() => {
            $.ajax({
                url: `/admin/product-images/${imageId}/update-alt`,
                type: 'POST',
                data: { alt_text: altText },
                success: function(response) {
                    if (response.success) {
                        // Show subtle success indicator
                        const input = $(`.alt-text-input[data-id="${imageId}"]`);
                        input.addClass('border-success');
                        setTimeout(() => {
                            input.removeClass('border-success');
                        }, 1000);
                    }
                }
            });
        }, 1000);
    }
    
    function previewNewImages(files) {
        const container = $('#upload-preview');
        container.empty();
        
        if (files.length > 0) {
            const heading = $('<h6 class="text-muted mb-3"><i class="fas fa-eye me-2"></i>Preview:</h6>');
            const previewContainer = $('<div class="preview-container"><div class="row g-3"></div></div>');
            const row = previewContainer.find('.row');
            
            Array.from(files).forEach(function(file, index) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = $(`
                            <div class="col-md-3">
                                <div class="card">
                                    <img src="${e.target.result}" 
                                         class="card-img-top" 
                                         style="height: 120px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted d-block text-truncate">${file.name}</small>
                                        <small class="text-muted">${(file.size / 1024).toFixed(2)} KB</small>
                                    </div>
                                </div>
                            </div>
                        `);
                        row.append(col);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            container.append(heading, previewContainer);
        }
    }
    
    function toggleAltTextFields() {
        const files = document.getElementById('new-images').files;
        const container = $('#upload-alt-text-container');
        const fieldsContainer = $('#alt-text-fields');
        
        if (files.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'No Images Selected',
                text: 'Please select images first',
                timer: 2000
            });
            return;
        }
        
        if (container.is(':visible')) {
            container.slideUp();
            return;
        }
        
        fieldsContainer.empty();
        
        Array.from(files).forEach(function(file, index) {
            const field = $(`
                <div class="mb-3">
                    <label class="form-label small fw-bold">
                        <i class="fas fa-image me-1"></i>${file.name}
                    </label>
                    <input type="text" 
                           class="form-control" 
                           name="alt_texts[${index}]" 
                           placeholder="Enter alt text for ${file.name}">
                </div>
            `);
            fieldsContainer.append(field);
        });
        
        container.slideDown();
    }
    
    // Form submission with loading state
    $('#upload-form').on('submit', function() {
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Uploading...');
    });
</script>
@endpush