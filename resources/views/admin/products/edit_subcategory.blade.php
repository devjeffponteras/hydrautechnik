@extends('admin.layouts.app')

@section('pagetitle')
Edit Subcategory
@endsection

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Subcategory</h4>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.update_subcategory', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if($subcategory->category_id == $category->id) selected @endif>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Subcategory Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $subcategory->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ $subcategory->description }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Subcategory Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    
                    @if($subcategory->image)
                        <div class="mt-2">
                            <p class="text-muted">Current Image:</p>
                            <img src="{{ asset($subcategory->image) }}" alt="Current Subcategory Image" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                    @endif
                    
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <p class="text-muted">New Image Preview:</p>
                        <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <small class="form-text text-muted">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                </div>
                <button type="submit" class="btn btn-success">Update Subcategory</button>
                <a href="{{ route('products.create_subcategory') }}" class="btn btn-secondary">Cancel</a>
            </form>
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
