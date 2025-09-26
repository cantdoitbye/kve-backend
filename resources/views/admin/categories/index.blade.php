@extends('layouts.admin')

@section('title', 'Categories - Admin Panel')
@section('page-title', 'Categories Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold">
            <i class="fas fa-layer-group me-2 text-primary"></i>All Categories
        </h5>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Category
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="categories-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        window.dataTable = $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.categories.index') }}",
            columns: [
                {data: 'id', name: 'id', width: '60px'},
                {data: 'title', name: 'title'},
                {data: 'slug', name: 'slug'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '120px'}
            ],
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search categories..."
            }
        });
    });
</script>
@endpush