@extends('admin.layouts.app')

@section('pagetitle')
{{ $product->name }} - Product Details
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Product Details</h3>
                    <p class="text-muted mb-0">View complete information for this hydraulic product</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary">
                        <i data-feather="edit-2" class="me-1"></i>
                        Edit Product
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="arrow-left" class="me-1"></i>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i data-feather="image" class="me-2"></i>
                        Product Image
                    </h6>
                </div>
                <div class="card-body text-center d-flex align-items-center justify-content-center">
                    @if($product->image)
                        <div>
                            <img src="{{ asset($product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="img-fluid rounded shadow"
                                 style="max-width: 100%; max-height: 300px; object-fit: cover;"
                                 onerror="this.src='{{ asset('storage/no-image.png') }}'; this.alt='No Image Available';">
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="image" style="width: 48px; height: 48px;" class="text-muted"></i>
                            </div>
                            <h6 class="text-muted">No Image Available</h6>
                            <p class="text-muted small mb-0">This product doesn't have an image</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="package" class="me-2"></i>
                        {{ $product->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Product Name</label>
                                <div class="p-2 bg-light rounded">
                                    <i data-feather="package" class="me-2"></i>
                                    {{ $product->name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Category</label>
                                <div class="p-2 bg-light rounded">
                                    <i data-feather="folder" class="me-2"></i>
                                    {{ $product->productCategory ? $product->productCategory->name : ($product->subcategory && $product->subcategory->category ? $product->subcategory->category->name : 'No Category') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Subcategory</label>
                                <div class="p-2 bg-light rounded">
                                    <i data-feather="folder-plus" class="me-2"></i>
                                    {{ $product->subcategory ? $product->subcategory->name : 'No Subcategory' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Publication Status</label>
                                <div class="p-2 bg-light rounded">
                                    @if($product->is_published)
                                        <i data-feather="eye" class="me-2 text-success"></i>
                                        <span class="text-success">Published</span>
                                    @else
                                        <i data-feather="eye-off" class="me-2 text-warning"></i>
                                        <span class="text-warning">Draft</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($product->description)
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">
                                <i data-feather="file-text" class="me-1"></i>
                                Description
                            </label>
                            <div class="p-3 bg-light rounded">
                                {{ $product->description }}
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">
                                <i data-feather="file-text" class="me-1"></i>
                                Description
                            </label>
                            <div class="p-3 bg-light rounded text-muted">
                                <em>No description provided for this product.</em>
                            </div>
                        </div>
                    @endif

                    <!-- Specification -->
                    @if($product->specification)
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">
                                <i data-feather="settings" class="me-1"></i>
                                Specifications
                            </label>
                            <div class="p-3 bg-light rounded">
                                <ul class="mb-0">
                                @foreach(preg_split('/\r?\n/', $product->specification) as $spec)
                                    @if(trim($spec) !== '')
                                        <li>{{ $spec }}</li>
                                    @endif
                                @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @php
                        $hasAdditionalFields = $product->ixu || $product->olx || $product->fam_atex || $product->olsw;
                    @endphp

                    @if($hasAdditionalFields)
                        <!-- Additional Information -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">
                                <i data-feather="info" class="me-1"></i>
                                Additional Information
                            </label>
                            <div class="p-3 bg-light rounded">
                                @if($product->ixu)
                                    <div class="mb-3">
                                        <strong class="text-primary">IXU:</strong>
                                        <div class="mt-1">{!! nl2br(e($product->ixu)) !!}</div>
                                    </div>
                                @endif
                                @if($product->olx)
                                    <div class="mb-3">
                                        <strong class="text-success">OLX:</strong>
                                        <div class="mt-1">{!! nl2br(e($product->olx)) !!}</div>
                                    </div>
                                @endif
                                @if($product->fam_atex)
                                    <div class="mb-3">
                                        <strong class="text-warning">FAM ATEX:</strong>
                                        <div class="mt-1">{!! nl2br(e($product->fam_atex)) !!}</div>
                                    </div>
                                @endif
                                @if($product->olsw)
                                    <div class="mb-0">
                                        <strong class="text-info">OLSW:</strong>
                                        <div class="mt-1">{!! nl2br(e($product->olsw)) !!}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-bold text-muted small">Created</label>
                                <div class="text-muted small">
                                    <i data-feather="calendar" class="me-1"></i>
                                    {{ $product->created_at->format('M d, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-bold text-muted small">Last Updated</label>
                                <div class="text-muted small">
                                    <i data-feather="clock" class="me-1"></i>
                                    {{ $product->updated_at->format('M d, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
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
