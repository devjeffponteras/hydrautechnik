@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Add Product</h4>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="subcategory_id" class="form-label">Subcategory</label>
                    <select name="category_id" id="categorySelect" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <select name="subcategory_id" id="subcategorySelect" class="form-control mt-2" required>
                        <option value="">Select Subcategory</option>
                        @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" data-category="{{ $subcategory->category->id }}">{{ $subcategory->category->name }} - {{ $subcategory->name }}</option>
                        @endforeach
                    </select>
                    <small id="noSubInfo" class="form-text text-muted mt-1" style="display:none;">Selected category has no subcategories. Product will be created without subcategory.</small>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="specification" class="form-label">Specifications</label>
                    <textarea name="specification" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="ixu" class="form-label">IXU</label>
                    <input type="text" name="ixu" class="form-control" value="{{ old('ixu') }}" placeholder="Enter IXU value">
                </div>
                <div class="mb-3">
                    <label for="olx" class="form-label">OLX</label>
                    <input type="text" name="olx" class="form-control" value="{{ old('olx') }}" placeholder="Enter OLX value">
                </div>
                <div class="mb-3">
                    <label for="fam_atex" class="form-label">FAM ATEX</label>
                    <input type="text" name="fam_atex" class="form-control" value="{{ old('fam_atex') }}" placeholder="Enter FAM ATEX value">
                </div>
                <div class="mb-3">
                    <label for="olsw" class="form-label">OLSW</label>
                    <input type="text" name="olsw" class="form-control" value="{{ old('olsw') }}" placeholder="Enter OLSW value">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <small class="form-text text-muted">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                </div>
                <button type="submit" class="btn btn-success">Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
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

document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('categorySelect');
    const subcategorySelect = document.getElementById('subcategorySelect');
    const noSubInfo = document.getElementById('noSubInfo');

    function updateSubcategories() {
        const selectedCat = categorySelect.value;
        let has = false;

        Array.from(subcategorySelect.options).forEach(opt => {
            const cat = opt.getAttribute('data-category');
            if (!opt.value) { // keep placeholder visible
                opt.style.display = '';
                return;
            }
            if (cat === selectedCat) {
                opt.style.display = '';
                has = true;
            } else {
                opt.style.display = 'none';
            }
        });

        if (!has) {
            subcategorySelect.style.display = 'none';
            subcategorySelect.removeAttribute('required');
            noSubInfo.style.display = '';
        } else {
            subcategorySelect.style.display = '';
            subcategorySelect.required = true;
            noSubInfo.style.display = 'none';
        }
    }

    categorySelect.addEventListener('change', updateSubcategories);
});
</script>
@endsection
