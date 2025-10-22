@extends('admin.layouts.app')

@section('pagetitle')
Edit Product Subcategory
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Edit Subcategory</h3>
                    <p class="text-muted mb-0">Update subcategory information and settings</p>
                </div>
                <a href="{{ route('products.create_subcategory') }}" class="btn btn-outline-secondary">
                    <i data-feather="arrow-left" class="me-1"></i>
                    Back to Subcategories
                </a>
            </div>
        </div>
    </div>

    <!-- Information Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0">
                <div class="d-flex">
                    <div class="me-3">
                        <i data-feather="edit-3" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Editing: {{ $subcategory->name }}</h6>
                        <p class="mb-0">Currently under category: <strong>{{ $subcategory->category->name }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="edit-3" class="me-2"></i>
                        Update Subcategory Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update_subcategory', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-bold">
                                Parent Category <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Choose a main category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if($subcategory->category_id == $category->id) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Select which main category this subcategory belongs to</div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">
                                Subcategory Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control"
                                   value="{{ $subcategory->name }}"
                                   placeholder="e.g., Gear Pumps, Ball Valves, Quick Couplings"
                                   required>
                            <div class="form-text">Enter a specific name for this subcategory</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">
                                Description <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea id="description"
                                      name="description"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Describe what type of products belong in this subcategory...">{{ $subcategory->description }}</textarea>
                            <div class="form-text">Provide details about products in this subcategory</div>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">
                                Subcategory Image <span class="text-muted">(Optional)</span>
                            </label>

                            @if($subcategory->image)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($subcategory->image) }}"
                                             alt="Current Image"
                                             class="img-thumbnail me-3"
                                             style="max-width: 120px; max-height: 120px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1">Current Image</h6>
                                            <p class="text-muted mb-0">Upload a new image to replace this one</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input type="file"
                                   id="image"
                                   name="image"
                                   class="form-control"
                                   accept="image/*"
                                   onchange="previewImage(this)">

                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <img id="previewImg"
                                         src=""
                                         alt="New Preview"
                                         class="img-thumbnail me-3"
                                         style="max-width: 120px; max-height: 120px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1 text-success">New Image Preview</h6>
                                        <p class="text-muted mb-0">This will replace the current image</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-text mt-2">
                                <i data-feather="upload" class="me-1" style="width: 14px; height: 14px;"></i>
                                Supported: JPEG, PNG, JPG, GIF (Max: 2MB)
                                @if($subcategory->image)
                                    <br><small class="text-muted">Leave empty to keep current image</small>
                                @endif
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i data-feather="save" class="me-2"></i>
                                Update Subcategory
                            </button>
                            <a href="{{ route('products.create_subcategory') }}" class="btn btn-outline-secondary">
                                <i data-feather="x" class="me-1"></i>
                                Cancel Changes
                            </a>
                        </div>
                    </form>
                </div>
            </div>
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
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection
