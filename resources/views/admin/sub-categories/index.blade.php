<?php
// resources/views/admin/sub-categories/index.blade.php
?>
@extends('layouts.admin')

@section('title', 'Sub Categories - Admin Panel')
@section('page-title', 'Sub Categories Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold">
            <i class="fas fa-sitemap me-2 text-primary"></i>All Sub Categories
        </h5>
        <a href="{{ route('admin.sub-categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Sub Category
        </a>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-select" id="category-filter">
                    <option value="">All Categories</option>
                    @foreach($categories ?? [] as $category)
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
                    <button type="button" class="btn btn-outline-secondary" onclick="exportSubCategories()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="refreshTable()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="sub-categories-table">
                <thead>
                    <tr>
                        <th width="60px">ID</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Category</th>
                        <th width="100px">Status</th>
                        <th width="120px">Created</th>
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
        dataTable = $('#sub-categories-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.sub-categories.index') }}",
                data: function(d) {
                    d.category_id = $('#category-filter').val();
                    d.status = $('#status-filter').val();
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'title', name: 'title'},
                {data: 'slug', name: 'slug'},
                {data: 'category_name', name: 'category.title'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search sub categories...",
                processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                emptyTable: "No sub categories found",
                info: "Showing _START_ to _END_ of _TOTAL_ sub categories",
                infoEmpty: "Showing 0 to 0 of 0 sub categories",
                infoFiltered: "(filtered from _MAX_ total sub categories)"
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
        
        // Show success message
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            icon: 'info',
            title: 'Table refreshed'
        });
    }
    
    function exportSubCategories() {
        // You can implement export functionality here
        Swal.fire({
            title: 'Export Sub Categories',
            text: 'Choose export format:',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-file-excel"></i> Excel',
            cancelButtonText: '<i class="fas fa-file-csv"></i> CSV',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#17a2b8'
        }).then((result) => {
            if (result.isConfirmed) {
                // Export to Excel
                window.open('{{ route("admin.sub-categories.index") }}?export=excel', '_blank');
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Export to CSV
                window.open('{{ route("admin.sub-categories.index") }}?export=csv', '_blank');
            }
        });
    }
</script>
@endpush