@extends('layouts.admin')

@section('title', 'Edit Product - Admin Panel')
@section('page-title', 'Edit Product: ' . $product->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-edit me-2 text-primary"></i>Edit Product
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Title, SKU, and Price Row -->
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Product Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $product->title) }}" 
                                       placeholder="Enter product title" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="sku" class="form-label fw-bold">SKU</label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                       id="sku" name="sku" value="{{ old('sku', $product->sku) }}" 
                                       placeholder="Product SKU">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label fw-bold">Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $product->price) }}" 
                                       placeholder="0.00" step="0.01" min="0">
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
                                  placeholder="Brief product description" required>{{ old('short_description', $product->short_description) }}</textarea>
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
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                        id="sub_category_id" name="sub_category_id" required>
                                    <option value="">Select Sub Category</option>
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ old('sub_category_id', $product->sub_category_id) == $subCategory->id ? 'selected' : '' }}>
                                            {{ $subCategory->title }}
                                        </option>
                                    @endforeach
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
                                        id="segment_id" name="segment_id" required>
                                    <option value="">Select Segment</option>
                                    @foreach($segments as $segment)
                                        <option value="{{ $segment->id }}" {{ old('segment_id', $product->segment_id) == $segment->id ? 'selected' : '' }}>
                                            {{ $segment->title }}
                                        </option>
                                    @endforeach
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
                                        id="sub_segment_id" name="sub_segment_id" required>
                                    <option value="">Select Sub Segment</option>
                                    @foreach($subSegments as $subSegment)
                                        <option value="{{ $subSegment->id }}" {{ old('sub_segment_id', $product->sub_segment_id) == $subSegment->id ? 'selected' : '' }}>
                                            {{ $subSegment->title }}
                                        </option>
                                    @endforeach
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
                                  id="product_details" name="product_details" rows="8">{{ old('product_details', $product->product_details) }}</textarea>
                        @error('product_details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="specifications" class="form-label fw-bold">Specifications</label>
                        <textarea class="form-control tinymce @error('specifications') is-invalid @enderror" 
                                  id="specifications" name="specifications" rows="6">{{ old('specifications', $product->specifications) }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NEW OPTIONAL FIELDS START HERE -->
                    
                    <div class="mb-3">
    <label for="disclaimer" class="form-label fw-bold">
        Disclaimer <span class="text-muted">(Optional)</span>
    </label>
    <textarea class="form-control tinymce @error('disclaimer') is-invalid @enderror" 
              id="disclaimer" 
              name="disclaimer" 
              rows="4" 
              placeholder="Enter product disclaimer text">{{ old('disclaimer', $product->disclaimer) }}</textarea>
    @error('disclaimer')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Add any disclaimers or important notes about this product</small>
</div>

<!-- Features (Optional) -->
<div class="mb-3">
    <label class="form-label fw-bold">
        Features <span class="text-muted">(Optional)</span>
    </label>
    <div id="features-container">
        @php
            $features = old('features', $product->features ?? []);
        @endphp
        
        @if(!empty($features) && is_array($features))
            @foreach($features as $index => $feature)
                <div class="input-group mb-2 feature-item">
                    <input type="text" 
                           class="form-control @error('features.'.$index) is-invalid @enderror" 
                           name="features[]" 
                           value="{{ $feature }}" 
                           placeholder="Enter feature">
                    <button type="button" class="btn btn-outline-danger" onclick="removeFeatureItem(this)">
                        <i class="fas fa-times"></i>
                    </button>
                    @error('features.'.$index)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
        @else
            <div class="input-group mb-2 feature-item">
                <input type="text" 
                       class="form-control" 
                       name="features[]" 
                       placeholder="Enter feature">
                <button type="button" class="btn btn-outline-danger" onclick="removeFeatureItem(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary" id="add-feature-btn">
        <i class="fas fa-plus me-1"></i>Add Feature
    </button>
    <small class="text-muted d-block mt-2">Add key features of this product</small>
</div>
                    <!-- Service Information Section -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Service Information Links</label>
                        <div id="service-info-container">
                            @php
                                $serviceInfoData = old('service_info', $product->service_info ?? []);
                            @endphp
                            
                            @if(is_array($serviceInfoData) && count($serviceInfoData) > 0)
                                @foreach($serviceInfoData as $index => $service)
                                    <div class="service-info-item border rounded p-3 mb-2 bg-light">
                                        <div class="row align-items-center">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="service_info[{{ $index }}][link_text]" 
                                                       placeholder="Link Text (e.g., User Manual)" value="{{ $service['link_text'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="url" class="form-control" name="service_info[{{ $index }}][link]" 
                                                       placeholder="https://example.com" value="{{ $service['link'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-service-info w-100">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-service-info">
                            <i class="fas fa-plus me-1"></i>Add Service Link
                        </button>
                        <small class="text-muted d-block mt-1">Add links to manuals, guides, or related services</small>
                    </div>

                    <!-- What's Included Section -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">What's Included</label>
                        <div id="included-container">
                            @php
                                $includedData = old('included', $product->included ?? []);
                            @endphp
                            
                            @if(is_array($includedData) && count($includedData) > 0)
                                @foreach($includedData as $index => $item)
                                    <div class="included-item mb-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="included[]" 
                                                   placeholder="Item included with product" value="{{ $item }}">
                                            <button type="button" class="btn btn-danger btn-sm remove-included">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-included">
                            <i class="fas fa-plus me-1"></i>Add Item
                        </button>
                        <small class="text-muted d-block mt-1">List items that come with the product (e.g., cables, accessories)</small>
                    </div>

                    <!-- Documentation Section - MULTIPLE -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Documentation</label>
                        <div id="documentation-container">
                            @php
                                $documentationData = old('documentation', $product->documentation ?? []);
                            @endphp
                            
                            @if(is_array($documentationData) && count($documentationData) > 0)
                                @foreach($documentationData as $index => $doc)
                                    <div class="documentation-item border rounded p-3 mb-2 bg-light">
                                        <div class="row align-items-center">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="documentation[{{ $index }}][link_text]" 
                                                       placeholder="Document Title (e.g., User Manual)" value="{{ $doc['link_text'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="url" class="form-control" name="documentation[{{ $index }}][link]" 
                                                       placeholder="https://example.com/document.pdf" value="{{ $doc['link'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-documentation w-100">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-documentation">
                            <i class="fas fa-plus me-1"></i>Add Documentation
                        </button>
                        <small class="text-muted d-block mt-1">Add links to PDF manuals or documentation files</small>
                    </div>

                    <!-- Partner Section - SINGLE -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Partner</label>
                        <div class="border rounded p-3 bg-light">
                            @php
                                $partnerData = old('partner') ?? $product->partner ?? null;
                                $partnerLabel = is_array($partnerData) ? ($partnerData['label'] ?? '') : '';
                                $partnerLink = is_array($partnerData) ? ($partnerData['link'] ?? '') : '';
                            @endphp
                            
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control @error('partner_label') is-invalid @enderror" 
                                           name="partner_label" placeholder="Partner Name" 
                                           value="{{ old('partner_label', $partnerLabel) }}">
                                    @error('partner_label')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-7">
                                    <input type="url" class="form-control @error('partner_link') is-invalid @enderror" 
                                           name="partner_link" placeholder="https://partner-website.com" 
                                           value="{{ old('partner_link', $partnerLink) }}">
                                    @error('partner_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Partner website or information link</small>
                        </div>
                    </div>

                    <!-- Input Types Section -->
                    <div class="mb-3">
                        <label for="input_types_text" class="form-label fw-bold">Input Types</label>
                        <input type="text" class="form-control @error('input_types') is-invalid @enderror" 
                               id="input_types_text" placeholder="Type and press Enter or comma (e.g., 60Hz, 1 Phase, 120V)">
                        @php
                            $inputTypesData = old('input_types', $product->input_types ?? []);
                            $inputTypesJson = is_array($inputTypesData) ? json_encode($inputTypesData) : '[]';
                        @endphp
                        <input type="hidden" name="input_types" id="input_types" value='{{ $inputTypesJson }}'>
                        <div id="input-types-tags" class="mt-2"></div>
                        @error('input_types')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Press Enter or comma after each type</small>
                    </div>

                    <!-- Output Types Section -->
                    <div class="mb-3">
                        <label for="output_types_text" class="form-label fw-bold">Output Types</label>
                        <input type="text" class="form-control @error('output_types') is-invalid @enderror" 
                               id="output_types_text" placeholder="Type and press Enter or comma (e.g., AC, DC, CV)">
                        @php
                            $outputTypesData = old('output_types', $product->output_types ?? []);
                            $outputTypesJson = is_array($outputTypesData) ? json_encode($outputTypesData) : '[]';
                        @endphp
                        <input type="hidden" name="output_types" id="output_types" value='{{ $outputTypesJson }}'>
                        <div id="output-types-tags" class="mt-2"></div>
                        @error('output_types')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Press Enter or comma after each type</small>
                    </div>

                    <!-- END OF NEW OPTIONAL FIELDS -->

                    <!-- Sustainability Checkbox -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_sustainable" 
                                   id="is_sustainable" value="1" {{ old('is_sustainable', $product->is_sustainable) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_sustainable">
                                <i class="fas fa-leaf text-success me-1"></i>Sustainable Product
                            </label>
                            <small class="text-muted d-block mt-1">Check if this product is environmentally sustainable</small>
                        </div>
                    </div>

                    <!-- Existing Images Display -->
                    @if($product->images->count() > 0)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Product Images</label>
                        <div class="row g-2">
                            @foreach($product->images as $image)
                            <div class="col-md-3">
                                <div class="card">
                                    <img src="{{ asset($image->image_path) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">{{ $image->filename }}</small>
                                        @if($image->is_primary)
                                            <br><span class="badge bg-primary">Primary</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            To manage images, use the <a href="{{ route('admin.products.images.index', $product) }}">Image Management</a> page
                        </small>
                    </div>
                    @endif

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" 
                                   id="status" value="1" {{ old('status', $product->status) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="status">
                                Active Status
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Update Product
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
        // ========================================
        // NEW OPTIONAL FIELDS JAVASCRIPT START
        // ========================================
        
        let serviceInfoIndex = {{ is_array($product->service_info ?? null) ? count($product->service_info) : 0 }};
        let documentationIndex = {{ is_array($product->documentation ?? null) ? count($product->documentation) : 0 }};
        
        // Service Info Management
        $('#add-service-info').click(function() {
            const container = $('#service-info-container');
            const html = `
                <div class="service-info-item border rounded p-3 mb-2 bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="service_info[${serviceInfoIndex}][link_text]" 
                                   placeholder="Link Text (e.g., User Manual)">
                        </div>
                        <div class="col-md-6">
                            <input type="url" class="form-control" name="service_info[${serviceInfoIndex}][link]" 
                                   placeholder="https://example.com">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-service-info w-100">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.append(html);
            serviceInfoIndex++;
        });

        // Remove service info
        $(document).on('click', '.remove-service-info', function() {
            $(this).closest('.service-info-item').remove();
        });

        // Documentation Management - MULTIPLE
        $('#add-documentation').click(function() {
            const container = $('#documentation-container');
            const html = `
                <div class="documentation-item border rounded p-3 mb-2 bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="documentation[${documentationIndex}][link_text]" 
                                   placeholder="Document Title (e.g., User Manual)">
                        </div>
                        <div class="col-md-6">
                            <input type="url" class="form-control" name="documentation[${documentationIndex}][link]" 
                                   placeholder="https://example.com/document.pdf">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-documentation w-100">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.append(html);
            documentationIndex++;
        });

        // Remove documentation
        $(document).on('click', '.remove-documentation', function() {
            $(this).closest('.documentation-item').remove();
        });

        // Included Items Management
        $('#add-included').click(function() {
            const container = $('#included-container');
            const html = `
                <div class="included-item mb-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="included[]" 
                               placeholder="Item included with product">
                        <button type="button" class="btn btn-danger btn-sm remove-included">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.append(html);
        });

        // Remove included item
        $(document).on('click', '.remove-included', function() {
            $(this).closest('.included-item').remove();
        });

           $('#add-feature-btn').on('click', function() {
        const container = $('#features-container');
        const newItem = $(`
            <div class="input-group mb-2 feature-item">
                <input type="text" class="form-control" name="features[]" placeholder="Enter feature">
                <button type="button" class="btn btn-outline-danger" onclick="removeFeatureItem(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `);
        container.append(newItem);
    });

        // Tags Management for Input/Output Types
        function initializeTagsInput(inputId, hiddenId, tagsContainerId) {
            const input = document.getElementById(inputId);
            const hidden = document.getElementById(hiddenId);
            const tagsContainer = document.getElementById(tagsContainerId);
            let tags = [];

            // Load existing tags
            if (hidden.value) {
                try {
                    tags = JSON.parse(hidden.value);
                    renderTags();
                } catch (e) {
                    tags = [];
                }
            }

            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    const value = input.value.trim().replace(',', '');
                    addTag(value);
                }
            });

            input.addEventListener('blur', function() {
                const value = input.value.trim();
                if (value) {
                    addTag(value);
                }
            });

            function addTag(value) {
                if (value && !tags.includes(value)) {
                    tags.push(value);
                    input.value = '';
                    updateHidden();
                    renderTags();
                }
            }

            function removeTag(index) {
                tags.splice(index, 1);
                updateHidden();
                renderTags();
            }

            function updateHidden() {
                hidden.value = JSON.stringify(tags);
            }

            function renderTags() {
                tagsContainer.innerHTML = tags.map((tag, index) => `
                    <span class="badge bg-primary me-2 mb-2" style="font-size: 14px; padding: 8px 12px;">
                        ${tag}
                        <i class="fas fa-times ms-2" style="cursor: pointer;" onclick="window.removeTag${hiddenId}(${index})"></i>
                    </span>
                `).join('');
            }

            // Make removeTag function globally accessible
            window[`removeTag${hiddenId}`] = removeTag;
        }

        // Initialize tags inputs
        initializeTagsInput('input_types_text', 'input_types', 'input-types-tags');
        initializeTagsInput('output_types_text', 'output_types', 'output-types-tags');
        
        // ========================================
        // NEW OPTIONAL FIELDS JAVASCRIPT END
        // ========================================

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
                const currentValue = '{{ old('sub_category_id', $product->sub_category_id) }}';
                select.html('<option value="">Select Sub Category</option>');
                
                data.forEach(function(item) {
                    const selected = item.id == currentValue ? 'selected' : '';
                    select.append(`<option value="${item.id}" ${selected}>${item.title}</option>`);
                });
            });
        }

        function loadSubSegments(segmentId) {
            $.get(`/admin/products/sub-segments/${segmentId}`, function(data) {
                const select = $('#sub_segment_id');
                const currentValue = '{{ old('sub_segment_id', $product->sub_segment_id) }}';
                select.html('<option value="">Select Sub Segment</option>');
                
                data.forEach(function(item) {
                    const selected = item.id == currentValue ? 'selected' : '';
                    select.append(`<option value="${item.id}" ${selected}>${item.title}</option>`);
                });
            });
        }

        function resetSubsequentDropdowns(selectors) {
            selectors.forEach(function(selector) {
                $(selector).html('<option value="">Select Option</option>');
            });
        }

        function addFeature() {
    const container = document.getElementById('features-container');
    const newItem = document.createElement('div');
    newItem.className = 'input-group mb-2 feature-item';
    newItem.innerHTML = `
        <input type="text" class="form-control" name="features[]" placeholder="Enter feature">
        <button type="button" class="btn btn-outline-danger remove-feature" onclick="removeFeature(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(newItem);
}

function removeFeature(button) {
    const container = document.getElementById('features-container');
    const items = container.querySelectorAll('.feature-item');
    
    if (items.length > 1) {
        button.closest('.feature-item').remove();
    } else {
        // Keep at least one field, just clear its value
        button.closest('.feature-item').querySelector('input').value = '';
    }
}

function removeFeatureItem(button) {
    const container = $('#features-container');
    const items = container.find('.feature-item');
    
    if (items.length > 1) {
        $(button).closest('.feature-item').remove();
    } else {
        // Keep at least one field, just clear its value
        $(button).closest('.feature-item').find('input').val('');
    }
}
    });
</script>
@endpush