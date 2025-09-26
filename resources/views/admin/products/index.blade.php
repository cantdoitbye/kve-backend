@extends('layouts.admin')

@section('title', 'Products - Admin Panel')
@section('page-title', 'Products Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold">
            <i class="fas fa-box me-2 text-primary"></i>All Products
        </h5>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Product
        </a>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-select" id="category-filter">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="status-filter">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary" onclick="exportProducts()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="refreshTable()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="products-table">
                <thead>
                    <tr>
                        <th width="80px">Image</th>
                        <th>Title</th>
                        <th>Category Hierarchy</th>
                        <th>Price</th>
                        <th width="100px">Images</th>
                        <th width="80px">Status</th>
                        <th width="100px">Created</th>
                        <th width="120px">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let dataTable;
    
    $(document).ready(function() {
        initializeDataTable();
        
        // Category filter
        $('#category-filter').change(function() {
            dataTable.ajax.reload();
        });
        
        // Status filter
        $('#status-filter').change(function() {
            dataTable.ajax.reload();
        });
    });
    
    function initializeDataTable() {
        dataTable = $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.products.index') }}",
                data: function(d) {
                    d.category_id = $('#category-filter').val();
                    d.status = $('#status-filter').val();
                }
            },
            columns: [
                {data: 'image', name: 'image', orderable: false, searchable: false},
                {data: 'title', name: 'title'},
                {data: 'category_hierarchy', name: 'category_hierarchy', orderable: false},
                {data: 'price', name: 'price'},
                {data: 'image_count', name: 'image_count', orderable: false, searchable: false},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order: [[6, 'desc']],
            pageLength: 25,
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search products...",
                processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            },
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
            drawCallback: function() {
                // Initialize tooltips
                $('[title]').tooltip();
            }
        });
        
        // Global reference for delete function
        window.dataTable = dataTable;
    }
    
    function refreshTable() {
        dataTable.ajax.reload();
        toastr.info('Table refreshed');
    }
    
    function exportProducts() {
        // You can implement export functionality here
        Swal.fire('Feature Coming Soon!', 'Export functionality will be available soon.', 'info');
    }
</script>
@endpush