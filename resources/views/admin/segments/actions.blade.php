<div class="btn-group btn-group-sm">
    <a href="{{ route('admin.segments.edit', $segment) }}" 
       class="btn btn-outline-primary" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-outline-danger" 
            onclick="confirmDelete('{{ route('admin.segments.destroy', $segment) }}')" 
            title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>