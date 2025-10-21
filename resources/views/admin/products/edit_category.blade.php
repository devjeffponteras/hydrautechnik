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
        <!-- Edit Category Form -->
        <div class="col-md-6">
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

        <!-- Categories List -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="list" class="me-2"></i>
                        All Categories
                    </h5>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="alert alert-light border-0 mb-3">
                            <i data-feather="layers" class="me-1"></i>
                            <strong>{{ $categories->count() }}</strong> categories found. Currently editing:
                            <span class="fw-bold text-warning">{{ $category->name }}</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th style="width: 120px;" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $cat)
                                    <tr @if($cat->id == $category->id) class="table-warning" @endif>
                                        <td class="fw-bold text-muted">#{{ $cat->id }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $cat->name }}</span>
                                            @if($cat->id == $category->id)
                                                <span class="badge bg-warning text-dark ms-2">Editing</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($cat->description)
                                                <span class="text-muted">{{ \Illuminate\Support\Str::limit($cat->description, 40) }}</span>
                                            @else
                                                <em class="text-muted">No description</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('products.edit_category', $cat->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Edit">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <form action="{{ route('products.destroy_category', $cat->id) }}"
                                                      method="POST"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Delete this category?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete">
                                                        <i data-feather="trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="inbox" class="text-muted" style="width: 48px; height: 48px;"></i>
                            </div>
                            <h6 class="text-muted mb-2">No Categories Yet</h6>
                            <p class="text-muted mb-3">
                                Start by creating your first product category.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
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

