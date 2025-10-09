@extends('admin.layouts.app')

@section('pagetitle')
Manage Subcategories
@endsection

@section('content')
<div class="container">
    <h4 class="mb-3">Manage Subcategories</h4>
    
    <!-- Create Subcategory Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Add New Subcategory</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('products.store_subcategory') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Subcategory Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Subcategory Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <small class="form-text text-muted">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                </div>
                <button type="submit" class="btn btn-success">Create Subcategory</button>
            </form>
        </div>
    </div>

    <!-- Subcategories List -->
    <div class="card">
        <div class="card-header">
            <h5>Existing Subcategories</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subcategories as $subcategory)
                    <tr>
                        <td>{{ $subcategory->id }}</td>
                        <td>{{ $subcategory->name }}</td>
                        <td>{{ $subcategory->category->name }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($subcategory->description, 50) }}</td>
                        <td>
                            @if($subcategory->image)
                                <img src="{{ asset($subcategory->image) }}" alt="Subcategory Image" style="width:50px; height:50px; object-fit:cover; border-radius:4px; border:1px solid #ddd;">
                            @else
                                <div style="width:50px; height:50px; background-color:#f8f9fa; border:1px solid #ddd; border-radius:4px; display:flex; align-items:center; justify-content:center; color:#6c757d; font-size:10px;">
                                    No Image
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('products.edit_subcategory', $subcategory->id) }}" class="btn btn-sm btn-info" title="Edit">
                                    <i data-feather="edit"></i>
                                </a>
                                <form action="{{ route('products.destroy_subcategory', $subcategory->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Delete this subcategory?')">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No subcategories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
