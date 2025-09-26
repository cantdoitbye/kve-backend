<div class="btn-group btn-group-sm">
    <a href="{{ route('admin.sub-categories.edit', $subCategory) }}" 
       class="btn btn-outline-primary" 
       title="Edit Sub Category"
       data-bs-toggle="tooltip">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" 
            class="btn btn-outline-danger" 
            onclick="confirmDelete('{{ route('admin.sub-categories.destroy', $subCategory) }}')" 
            title="Delete Sub Category"
            data-bs-toggle="tooltip">
        <i class="fas fa-trash"></i>
    </button>
</div>