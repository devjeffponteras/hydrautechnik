@extends('admin.layouts.app')

@section('pagetitle')
Product Subcategories Management
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Product Subcategories</h3>
                    <p class="text-muted mb-0">Create subcategories to organize products within main categories</p>
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
                        <h6 class="mb-1">What are Subcategories?</h6>
                        <p class="mb-2">Subcategories help you organize products into more specific groups within main categories.</p>
                        <small><strong>Example:</strong> Category "Hydraulic Pumps" → Subcategories: "Gear Pumps", "Piston Pumps", "Vane Pumps"</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Create Subcategory Form -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="plus-circle" class="me-2"></i>
                        Create New Subcategory
                    </h5>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        <form action="{{ route('products.store_subcategory') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">
                                    Parent Category <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">Choose a main category...</option>
                                    @foreach($categories as $category)
                                        @php
                                            $subcount = $subcategories->where('category_id', $category->id)->count();
                                        @endphp
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                            @if($subcount > 0)
                                                ({{ $subcount }} subcategories)
                                            @else
                                                (no subcategories yet)
                                            @endif
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
                                          placeholder="Describe what type of products belong in this subcategory..."></textarea>
                                <div class="form-text">Provide details about products in this subcategory</div>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label fw-bold">
                                    Subcategory Image <span class="text-muted">(Optional)</span>
                                </label>
                                <input type="file"
                                       id="image"
                                       name="image"
                                       class="form-control"
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <img id="previewImg"
                                         src=""
                                         alt="Preview"
                                         class="img-thumbnail"
                                         style="max-width: 200px; max-height: 200px;">
                                </div>
                                <div class="form-text">
                                    <i data-feather="upload" class="me-1" style="width: 14px; height: 14px;"></i>
                                    Supported: JPEG, PNG, JPG, GIF (Max: 2MB)
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i data-feather="save" class="me-2"></i>
                                    Create Subcategory
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <i data-feather="alert-triangle" class="text-warning mb-3" style="width: 48px; height: 48px;"></i>
                            <h6 class="text-muted mb-2">No Categories Available</h6>
                            <p class="text-muted mb-3">You need to create at least one main category before adding subcategories.</p>
                            <a href="{{ route('products.create_category') }}" class="btn btn-primary">
                                <i data-feather="plus" class="me-1"></i>
                                Create Category First
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Subcategories List & Category Overview -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="layers" class="me-2"></i>
                        Subcategories Overview
                    </h5>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        <!-- Category Status Summary -->
                        <div class="alert alert-light border-0 mb-4">
                            @php
                                $categoriesWithSubs = $categories->filter(function($cat) use ($subcategories) {
                                    return $subcategories->where('category_id', $cat->id)->count() > 0;
                                });
                                $categoriesWithoutSubs = $categories->filter(function($cat) use ($subcategories) {
                                    return $subcategories->where('category_id', $cat->id)->count() == 0;
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
                                        <span class="badge bg-success fs-6">{{ $categoriesWithSubs->count() }}</span>
                                    </div>
                                    <small class="text-muted">With Subcategories</small>
                                </div>
                                <div class="col-4">
                                    <div class="mb-1">
                                        <span class="badge bg-warning fs-6">{{ $categoriesWithoutSubs->count() }}</span>
                                    </div>
                                    <small class="text-muted">Without Subcategories</small>
                                </div>
                            </div>
                        </div>

                        @if($categoriesWithoutSubs->count() > 0)
                            <div class="alert alert-warning border-0 mb-3">
                                <div class="d-flex">
                                    <i data-feather="alert-circle" class="me-2 mt-1" style="width: 16px; height: 16px;"></i>
                                    <div>
                                        <strong>Categories without subcategories:</strong>
                                        <div class="mt-1">
                                            @foreach($categoriesWithoutSubs as $cat)
                                                <span class="badge bg-warning text-dark me-1">{{ $cat->name }}</span>
                                            @endforeach
                                        </div>
                                        <small class="text-muted mt-1 d-block">Consider adding subcategories to better organize these categories.</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($subcategories->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">ID</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th style="width: 60px;">Image</th>
                                            <th style="width: 100px;" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subcategories as $subcategory)
                                        <tr>
                                            <td class="fw-bold text-muted">#{{ $subcategory->id }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $subcategory->name }}</div>
                                                @if($subcategory->description)
                                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($subcategory->description, 40) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $subcategory->category->name }}</span>
                                            </td>
                                            <td>
                                                @if($subcategory->image)
                                                    <img src="{{ asset($subcategory->image) }}"
                                                         alt="Subcategory Image"
                                                         class="img-thumbnail"
                                                         style="width:40px; height:40px; object-fit:cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center bg-light border rounded"
                                                         style="width:40px; height:40px;">
                                                        <i data-feather="image" class="text-muted" style="width:16px; height:16px;"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('products.edit_subcategory', $subcategory->id) }}"
                                                       class="btn btn-sm btn-info"
                                                       title="Edit">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    <form action="{{ route('products.destroy_subcategory', $subcategory->id) }}"
                                                          method="POST"
                                                          style="display:inline;"
                                                          onsubmit="return confirm('Delete this subcategory?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"
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
                                    <i data-feather="layers" class="text-muted" style="width: 48px; height: 48px;"></i>
                                </div>
                                <h6 class="text-muted mb-2">No Subcategories Yet</h6>
                                <p class="text-muted mb-3">
                                    Start by creating subcategories for your existing categories.
                                </p>
                                <small class="text-muted">
                                    💡 <strong>Tip:</strong> Subcategories help customers find products more easily.
                                </small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="alert-triangle" class="text-warning" style="width: 48px; height: 48px;"></i>
                            </div>
                            <h6 class="text-muted mb-2">No Categories Available</h6>
                            <p class="text-muted mb-3">
                                Create main categories first before adding subcategories.
                            </p>
                            <a href="{{ route('products.create_category') }}" class="btn btn-primary">
                                <i data-feather="plus" class="me-1"></i>
                                Create Categories
                            </a>
                        </div>
                    @endif
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
