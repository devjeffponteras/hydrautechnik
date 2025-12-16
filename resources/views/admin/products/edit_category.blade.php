@extends('admin.layouts.app')

@section('pagetitle')
Edit Product Category
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Edit Category</h3>
                    <p class="text-muted mb-0">Update category information and manage existing categories</p>
                </div>
                <a href="{{ route('products.create_category') }}" class="btn btn-outline-secondary">
                    <i data-feather="arrow-left" class="me-1"></i>
                    Back to Categories
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Edit Category Form (full width) -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="edit-3" class="me-2"></i>
                        Edit Category
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        <i data-feather="info" class="me-1"></i>
                        Update the category information below.
                    </p>

                    <form action="{{ route('products.update_category', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control"
                                   value="{{ $category->name }}"
                                   placeholder="e.g., Hydraulic Pumps, Valves, Fittings"
                                   required>
                            <div class="form-text">Enter a clear, descriptive name for the category</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                Description <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea id="description"
                                      name="description"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Brief description of what products belong in this category...">{{ $category->description }}</textarea>
                            <div class="form-text">Provide additional details about this category</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i data-feather="save" class="me-2"></i>
                                Update Category
                            </button>
                            <a href="{{ route('products.create_category') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- All categories list removed on edit page; only edit form is shown --}}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection

