@extends('admin.layouts.app')

@section('pagetitle')
Add New Equipment
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Add New Equipment</h3>
                    <p class="text-muted mb-0">Create a new equipment item for your hydraulic catalog</p>
                </div>
                <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary">
                    <i data-feather="arrow-left" class="me-1"></i>
                    Back to Equipment
                </a>
            </div>
        </div>
    </div>

    <!-- Helpful Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0">
                <div class="d-flex">
                    <div class="me-3">
                        <i data-feather="info" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Equipment Creation Guide</h6>
                        <p class="mb-2">Fill out the form below to add a new hydraulic equipment to your catalog.</p>
                        <small><strong>Tip:</strong> Use clear, descriptive names and detailed descriptions for better organization.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="plus-circle" class="me-2"></i>
                        Equipment Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('equipments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Equipment Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">
                                Equipment Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   placeholder="e.g., Hydraulic Power Unit HPU-500"
                                   required>
                            <div class="form-text">Enter a clear, descriptive name for the equipment</div>
                        </div>

                        <!-- Category Selection -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-3">
                                <i data-feather="folder" class="me-2"></i>
                                Classification
                            </h6>

                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">
                                    Category <span class="text-muted">(Optional)</span>
                                </label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Choose category...</option>
                                    @foreach($categories ?? collect() as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Select an appropriate category for organization</div>
                            </div>
                        </div>

                        <!-- Equipment Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                Equipment Description <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea id="description"
                                      name="description"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Provide a detailed description of the equipment, its features, and specifications...">{{ old('description') }}</textarea>
                            <div class="form-text">
                                <i data-feather="file-text" class="me-1"></i>
                                Write a comprehensive description to help identify and understand the equipment
                            </div>
                        </div>

                        <!-- Equipment Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">
                                Equipment Image <span class="text-muted">(Optional)</span>
                            </label>
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
                                         alt="Preview"
                                         class="img-thumbnail me-3"
                                         style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1 text-success">Image Preview</h6>
                                        <p class="text-muted mb-0">This image will be used for the equipment</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-text">
                                <i data-feather="upload" class="me-1"></i>
                                Supported: JPEG, PNG, JPG, GIF (Max: 2MB)
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary me-md-2">
                                <i data-feather="x" class="me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i data-feather="save" class="me-2"></i>
                                Create Equipment
                            </button>
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
