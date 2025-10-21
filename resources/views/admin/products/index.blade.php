@extends('admin.layouts.app')

@section('pagetitle')
Manage Products
@endsection

@section('pagecss')
<link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
<style>
    .row-selected {
        background-color: #92b7da !important;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endsection

@section('pagejs')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection

@section('content')
<div class="container pd-x-0">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                            <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </nav>
                    <h3 class="mb-1">Product Management</h3>
                    <p class="text-muted mb-0">Manage your hydraulic products, categories, and subcategories</p>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('products.create') }}" class="btn btn-success">
                        <i data-feather="plus" class="me-1"></i>
                        Add Product
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i data-feather="filter" class="me-2"></i>
                        Search & Filter Products
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="search" class="form-label fw-bold">Search Products</label>
                                <input name="search"
                                       id="search"
                                       type="search"
                                       class="form-control"
                                       placeholder="Search by name or description..."
                                       value="{{ request('search') }}">
                                <div class="form-text">Enter product name or description keywords</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="category" class="form-label fw-bold">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @if(request('category') == $cat->id) selected @endif>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Filter by product category</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="PUBLISHED" @if(request('status') == 'PUBLISHED') selected @endif>Published</option>
                                    <option value="PRIVATE" @if(request('status') == 'PRIVATE') selected @endif>Private</option>
                                </select>
                                <div class="form-text">Filter by publication status</div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="search" class="me-1"></i>
                                        Search
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                        <i data-feather="x" class="me-1"></i>
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Products Table -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i data-feather="star" class="me-2"></i>
                    Main Products
                </h5>
                <div class="d-flex align-items-center">
                    <span class="badge bg-success me-2">{{ $mainProducts->count() }} products</span>
                    @if($mainProducts->count() == 0)
                        <small class="text-muted">No main products yet</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($mainProducts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Status</th>
                                <th>Last Modified</th>
                                <th class="text-center" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mainProducts as $product)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    @if($product->description)
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($product->productCategory)
                                        <span class="badge bg-light text-dark">{{ $product->productCategory->name }}</span>
                                    @elseif($product->subcategory && $product->subcategory->category)
                                        <span class="badge bg-light text-dark">{{ $product->subcategory->category->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->subcategory)
                                        <span class="badge bg-info text-white">{{ $product->subcategory->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->status == 'PUBLISHED')
                                        <span class="badge bg-success">
                                            <i data-feather="check-circle" style="width: 12px; height: 12px;" class="me-1"></i>
                                            Published
                                        </span>
                                    @elseif($product->status == 'PRIVATE')
                                        <span class="badge bg-secondary">
                                            <i data-feather="lock" style="width: 12px; height: 12px;" class="me-1"></i>
                                            Private
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i data-feather="clock" style="width: 12px; height: 12px;" class="me-1"></i>
                                            {{ ucfirst(strtolower($product->status ?? 'Draft')) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->updated_at)
                                        <div class="text-muted">
                                            <small>{{ $product->updated_at->format('M d, Y') }}</small>
                                            <br>
                                            <small>{{ $product->updated_at->format('h:i A') }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-sm btn-primary" title="View Details" href="{{ route('products.show', $product->id) }}">
                                            <i data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Delete this product?')">
                                                <i data-feather="trash-2"></i>
                                            </a>
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
                        <i data-feather="star" class="text-muted" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h6 class="text-muted mb-2">No Main Products Yet</h6>
                    <p class="text-muted mb-3">Start by adding your first main product to the catalog.</p>
                    <a href="{{ route('products.create') }}" class="btn btn-success">
                        <i data-feather="plus" class="me-1"></i>
                        Add First Main Product
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Other Products Table -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i data-feather="layers" class="me-2"></i>
                    Other Products
                </h5>
                <div class="d-flex align-items-center">
                    <span class="badge bg-info me-2">{{ $otherProducts->count() }} products</span>
                    @if($otherProducts->count() == 0)
                        <small class="text-muted">No other products yet</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($otherProducts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Status</th>
                                <th>Last Modified</th>
                                <th class="text-center" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($otherProducts as $product)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    @if($product->description)
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($product->productCategory)
                                        <span class="badge bg-light text-dark">{{ $product->productCategory->name }}</span>
                                    @elseif($product->subcategory && $product->subcategory->category)
                                        <span class="badge bg-light text-dark">{{ $product->subcategory->category->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->subcategory)
                                        <span class="badge bg-info text-white">{{ $product->subcategory->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->status == 'PUBLISHED')
                                        <span class="badge bg-success">
                                            <i data-feather="check-circle" style="width: 12px; height: 12px;" class="me-1"></i>
                                            Published
                                        </span>
                                    @elseif($product->status == 'PRIVATE')
                                        <span class="badge bg-secondary">
                                            <i data-feather="lock" style="width: 12px; height: 12px;" class="me-1"></i>
                                            Private
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i data-feather="clock" style="width: 12px; height: 12px;" class="me-1"></i>
                                            {{ ucfirst(strtolower($product->status ?? 'Draft')) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->updated_at)
                                        <div class="text-muted">
                                            <small>{{ $product->updated_at->format('M d, Y') }}</small>
                                            <br>
                                            <small>{{ $product->updated_at->format('h:i A') }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-sm btn-primary" title="View Details" href="{{ route('products.show', $product->id) }}">
                                            <i data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Delete this product?')">
                                                <i data-feather="trash-2"></i>
                                            </a>
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
                    <h6 class="text-muted mb-2">No Other Products Yet</h6>
                    <p class="text-muted mb-3">Other products will appear here when you create products with tag = 2.</p>
                    <a href="{{ route('products.create') }}" class="btn btn-info">
                        <i data-feather="plus" class="me-1"></i>
                        Add Other Product
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function zoomImage(imageUrl) {
    $('#zoomedImage').attr('src', imageUrl);
    $('#imageZoomModal').modal('show');
}

// Initialize Feather icons when page loads
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection
