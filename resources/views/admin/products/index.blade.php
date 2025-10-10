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
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Manage Products</h4>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-8 col-md-12 mb-2 mb-lg-0">
            <form class="form-inline flex-wrap" method="GET" action="{{ route('products.index') }}">
                <input name="search" type="search" class="form-control mr-2 mb-2" placeholder="Search by Name or Description" value="{{ request('search') }}">
                <select name="category" class="form-control mr-2 mb-2">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @if(request('category') == $cat->id) selected @endif>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="subcategory" class="form-control mr-2 mb-2">
                    <option value="">All Subcategories</option>
                    @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" @if(request('subcategory') == $subcategory->id) selected @endif>
                            {{ $subcategory->category->name }} - {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary mb-2"><i data-feather="search"></i></button>
            </form>
        </div>
        <div class="col-lg-4 col-md-12 text-lg-right text-md-left">
            <div class="btn-group mb-2" role="group">
                <a href="{{ route('products.create') }}" class="btn btn-success mr-2">
                    <i data-feather="plus"></i> Add Product
                </a>
                <a href="{{ route('products.create_subcategory') }}" class="btn btn-warning mr-2">
                    <i data-feather="folder-plus"></i> Manage Subcategories
                </a>
                <a href="{{ route('products.create_category') }}" class="btn btn-info">
                    <i data-feather="folder-plus"></i> Manage Categories
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Specifications</th>
                        <th>IXU</th>
                        <th>OLX</th>
                        <th>FAM ATEX</th>
                        <th>OLSW</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->productCategory ? $product->productCategory->name : ($product->subcategory && $product->subcategory->category ? $product->subcategory->category->name : '-') }}</td>
                        <td>{{ $product->subcategory ? $product->subcategory->name : '-' }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="Product Image" style="width:60px; height:60px; object-fit:cover; border-radius:4px; border:1px solid #ddd; cursor: pointer;"
                                     onclick="zoomImage('{{ asset($product->image) }}')"
                                     onerror="this.src='{{ asset('storage/no-image.png') }}'; this.alt='No Image Available';">
                            @else
                                <div style="width:60px; height:60px; background-color:#f8f9fa; border:1px solid #ddd; border-radius:4px; display:flex; align-items:center; justify-content:center; color:#6c757d; font-size:12px;">
                                    No Image
                                </div>
                            @endif
<!-- Image Zoom Modal -->
<div class="modal fade" id="imageZoomModal" tabindex="-1" role="dialog" aria-labelledby="imageZoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageZoomModalLabel">Product Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="zoomedImage" src="" alt="Zoomed Product Image" style="max-width:100%; max-height:70vh; border-radius:8px; border:1px solid #ddd;">
            </div>
        </div>
    </div>
</div>
                        </td>
                        <td>
                            {{ \Illuminate\Support\Str::limit($product->description, 30) }}
                        </td>
                        <td>
                            {{ \Illuminate\Support\Str::limit($product->specification, 30) }}
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($product->ixu ?? '-', 30) }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($product->olx ?? '-', 30) }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($product->fam_atex ?? '-', 30) }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($product->olsw ?? '-', 30) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-primary" title="View Details" onclick="viewProduct({{ $product->id }})">
                                    <i data-feather="eye"></i>
                                </button>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info" title="Edit">
                                    <i data-feather="edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Delete this product?')">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Product Details Modal -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="productModalBody">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function zoomImage(imageUrl) {
    $('#zoomedImage').attr('src', imageUrl);
    $('#imageZoomModal').modal('show');
}
function viewProduct(productId) {
    // Show modal
    $('#productModal').modal('show');

    // Load product details via AJAX from the correct admin controller
    $.ajax({
        url: '/admin-panel/products/' + productId + '/details',
        type: 'GET',
        success: function(response) {
            $('#productModalBody').html(response);
        },
        error: function(xhr, status, error) {
            $('#productModalBody').html('<div class="alert alert-danger">Error loading product details.</div>');
        }
    });
}
</script>
@endsection
