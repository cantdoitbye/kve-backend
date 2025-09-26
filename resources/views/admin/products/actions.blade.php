<div class="btn-group btn-group-sm">
    <a href="{{ route('admin.products.show', $product) }}" 
       class="btn btn-outline-info" title="View Details">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('admin.products.edit', $product) }}" 
       class="btn btn-outline-primary" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <a href="{{ route('admin.products.images.index', $product) }}" 
       class="btn btn-outline-success" title="Manage Images">
        <i class="fas fa-images"></i>
    </a>
    <button type="button" class="btn btn-outline-danger" 
            onclick="confirmDelete('{{ route('admin.products.destroy', $product) }}')" 
            title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>