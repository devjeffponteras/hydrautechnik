@extends('admin.layouts.app')

@section('pagetitle')
Product Categories Management
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Product Categories</h3>
                    <p class="text-muted mb-0">Create and manage product categories for better organization</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i data-feather="arrow-left" class="me-1"></i>
                    Back to Products
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
                        <h6 class="mb-1">What are Product Categories?</h6>
                        <p class="mb-2">Categories are the main groups that help organize your products into logical sections for easier browsing and management.</p>
                        <small><strong>Example:</strong> "Hydraulic Pumps", "Valves & Controls", "Hoses & Fittings", "Filtration Systems"</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Add Category Form -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="plus-circle" class="me-2"></i>
                        Create New Category
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        <i data-feather="info" class="me-1"></i>
                        Add a new product category to help organize your products better.
                    </p>

                    <form action="{{ route('products.store_category') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control"
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
                                      placeholder="Brief description of what products belong in this category..."></textarea>
                            <div class="form-text">Provide additional details about this category</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i data-feather="save" class="me-2"></i>
                                Create Category
                            </button>
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
                        Categories Overview
                    </h5>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        <!-- Category Status Summary -->
                        <div class="alert alert-light border-0 mb-4">
                            @php
                                // Get subcategories count for each category
                                $subcategories = \App\Models\ProductSubcategory::all();
                                // Count categories that have products either directly (category_id)
                                // or via subcategories (products linked to a subcategory)
                                $categoriesWithProducts = $categories->filter(function($cat) {
                                    return \App\Models\Product::where(function($q) use ($cat){
                                        $q->where('category_id', $cat->id)
                                          ->orWhereHas('subcategory', function($q2) use ($cat){
                                              $q2->where('category_id', $cat->id);
                                          });
                                    })->exists();
                                });
                                $categoriesWithSubcategories = $categories->filter(function($cat) use ($subcategories) {
                                    return $subcategories->where('category_id', $cat->id)->count() > 0;
                                });
                            @endphp

                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="mb-1">
                                        <span class="badge bg-info fs-6">{{ $categories->count() }}</span>
                                    </div>
                                    <small class="text-muted">Total Categories</small>
                                </div>
                                <div class="col-4">
                                    <div class="mb-1">
                                        <span class="badge bg-success fs-6">{{ $categoriesWithSubcategories->count() }}</span>
                                    </div>
                                    <small class="text-muted">With Subcategories</small>
                                </div>
                                <div class="col-4">
                                    <div class="mb-1">
                                        <span class="badge bg-primary fs-6">{{ $categoriesWithProducts->count() }}</span>
                                    </div>
                                    <small class="text-muted">With Products</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-light border-0 mb-3">
                            <i data-feather="layers" class="me-1"></i>
                            <strong>{{ $categories->count() }}</strong> categories found. Click
                            <i data-feather="edit" class="mx-1" style="width: 14px; height: 14px;"></i> to edit or
                            <i data-feather="trash-2" class="mx-1" style="width: 14px; height: 14px;"></i> to delete.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Category Name</th>
                                        <th>Subcategories</th>
                                        <th>Products</th>
                                        <th style="width: 120px;" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    @php
                                        $subCount = $subcategories->where('category_id', $category->id)->count();
                                        // Count products directly assigned to this category OR assigned to any of its subcategories
                                        $productCount = \App\Models\Product::where(function($q) use ($category){
                                            $q->where('category_id', $category->id)
                                              ->orWhereHas('subcategory', function($q2) use ($category){
                                                  $q2->where('category_id', $category->id);
                                              });
                                        })->count();
                                    @endphp
                                    <tr>
                                        <td class="fw-bold text-muted">#{{ $category->id }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $category->name }}</div>
                                            @if($category->description)
                                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($category->description, 40) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subCount > 0)
                                                <span class="badge bg-success">{{ $subCount }}</span>
                                            @else
                                                <span class="badge bg-light text-muted">0</span>
                                                <small class="text-muted d-block">
                                                    <a href="{{ route('products.create_subcategory') }}" class="text-decoration-none">Add subcategories</a>
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($productCount > 0)
                                                <span class="badge bg-primary">{{ $productCount }}</span>
                                            @else
                                                <span class="badge bg-light text-muted">0</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('products.edit_category', $category->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Edit">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <form action="{{ route('products.destroy_category', $category->id) }}"
                                                      method="POST"
                                                      style="display: inline;"
                                                      class="confirm-delete-form" data-name="{{ $category->name }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger btn-confirm-delete" title="Delete">
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
                                Start by creating your first product category using the form on the left.
                            </p>
                            <small class="text-muted">
                                💡 <strong>Tip:</strong> Good category names help organize products and make them easier to find.
                            </small>
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

    // Initialize Bootstrap tooltips
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endsection

@section('pagejs')
<!-- Delete confirmation modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">Confirm delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
            </div>
      <div class="modal-body">
        <p id="confirmDeleteMessage">Are you sure you want to delete this item?</p>
      </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteButton">Delete</button>
      </div>
    </div>
  </div>
</div>

<script>
    (function(){
        var formToSubmit = null;
        var deleteModalEl = document.getElementById('confirmDeleteModal');
        var deleteModal = null;
        try {
            if (typeof bootstrap !== 'undefined' && deleteModalEl) {
                deleteModal = new bootstrap.Modal(deleteModalEl);
            }
        } catch(e) {
            deleteModal = null;
        }

        function onConfirmButtonClick(e) {
            if (formToSubmit) {
                formToSubmit.submit();
                formToSubmit = null;
            }
        }

        document.addEventListener('click', function(ev){
            var btn = ev.target.closest && ev.target.closest('.btn-confirm-delete');
            if (!btn) return;
            ev.preventDefault();
            var form = btn.closest('form.confirm-delete-form');
            if (!form) return;
            var name = form.dataset.name || 'this item';
            var message = "Are you sure you want to delete '" + name + "'? This action cannot be undone.";

            if (deleteModal) {
                document.getElementById('confirmDeleteMessage').textContent = message;
                formToSubmit = form;
                deleteModal.show();
            } else {
                if (window.confirm(message)) {
                    form.submit();
                }
            }
        });

        var confirmBtn = document.getElementById('confirmDeleteButton');
        if (confirmBtn) confirmBtn.addEventListener('click', onConfirmButtonClick);
    })();
</script>
@endsection
