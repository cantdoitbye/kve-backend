@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-lg-2 col-md-4 col-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-1">{{ $stats['categories'] }}</h4>
                        <p class="mb-0 text-light">Categories</p>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-layer-group fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-1">{{ $stats['sub_categories'] }}</h4>
                        <p class="mb-0 text-light">Sub Categories</p>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-sitemap fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-1">{{ $stats['segments'] }}</h4>
                        <p class="mb-0 text-light">Segments</p>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-puzzle-piece fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-1">{{ $stats['sub_segments'] }}</h4>
                        <p class="mb-0 text-light">Sub Segments</p>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-th-large fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-6">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-1">{{ $stats['products'] }}</h4>
                        <p class="mb-0 text-light">Total Products</p>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-box fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-6">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-1">{{ $stats['active_products'] }}</h4>
                        <p class="mb-0 text-light">Active Products</p>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Recent Products -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-clock me-2 text-primary"></i>Recent Products
                </h5>
            </div>
            <div class="card-body">
                @if($recent_products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->main_image)
                                                <img src="{{ Storage::url($product->main_image) }}" 
                                                     class="rounded me-3" width="40" height="40" 
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $product->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($product->short_description, 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->category->title }}</td>
                                    <td class="fw-bold text-success">₹{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $product->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No products found</h5>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add First Product
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Product
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-layer-group me-2"></i>Add Category
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>View All Products
                    </a>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-info-circle me-2 text-info"></i>System Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ PHP_VERSION }}</h4>
                        <small class="text-muted">PHP Version</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ app()::VERSION }}</h4>
                        <small class="text-muted">Laravel Version</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection