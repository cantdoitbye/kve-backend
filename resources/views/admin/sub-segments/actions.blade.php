<div class="btn-group btn-group-sm">
    <a href="{{ route('admin.sub-segments.edit', $subSegment) }}" 
       class="btn btn-outline-primary" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-outline-danger" 
            onclick="confirmDelete('{{ route('admin.sub-segments.destroy', $subSegment) }}')" 
            title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>