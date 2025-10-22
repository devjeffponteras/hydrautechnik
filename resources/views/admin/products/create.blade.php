@extends('admin.layouts.app')

@section('pagetitle')
Add New Product
@endsection

@section('content')
<style>
.collapse:not(.show) {
    display: none;
}
.collapse.show {
    display: block;
}
</style>
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Add New Product</h3>
                    <p class="text-muted mb-0">Create a new product for your hydraulic catalog</p>
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
                        <h6 class="mb-1">Product Creation Guide</h6>
                        <p class="mb-2">Fill out the form below to add a new hydraulic product to your catalog.</p>
                        <small><strong>Tip:</strong> Use clear, descriptive names and detailed specifications for better customer experience.</small>
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
                        Product Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">
                                Product Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control"
                                   placeholder="e.g., Hydraulic Gear Pump HP-2000"
                                   required>
                            <div class="form-text">Enter a clear, descriptive name for the product</div>
                        </div>

                        <!-- Category & Subcategory Selection -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-3">
                                <i data-feather="folder" class="me-2"></i>
                                Category & Classification
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="categorySelect" class="form-label fw-bold">
                                        Category <span class="text-danger">*</span>
                                    </label>
                                    <select name="category_id" id="categorySelect" class="form-control" required>
                                        <option value="">Choose main category...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Select the main product category</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="subcategorySelect" class="form-label fw-bold">
                                        Subcategory <span class="text-muted">(Optional)</span>
                                    </label>
                                    <select name="subcategory_id" id="subcategorySelect" class="form-control">
                                        <option value="">Choose subcategory...</option>
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" data-category="{{ $subcategory->category->id }}">
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Select a more specific subcategory</div>
                                </div>
                            </div>

                            <div id="noSubInfo" class="alert alert-warning border-0" style="display:none;">
                                <div class="d-flex">
                                    <i data-feather="alert-circle" class="me-2 mt-1" style="width: 16px; height: 16px;"></i>
                                    <div>
                                        <strong>No subcategories available</strong><br>
                                        <small>The selected category doesn't have subcategories. Product will be created without subcategory.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                Product Description <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea id="description"
                                      name="description"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Provide a detailed description of the product, its features, and benefits..."></textarea>
                            <div class="form-text">
                                <i data-feather="file-text" class="me-1"></i>
                                Write a comprehensive description to help customers understand the product
                            </div>
                        </div>

                        <!-- Product Specifications -->
                        <div class="mb-4">
                            <label for="specification" class="form-label fw-bold">
                                Technical Specifications <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea id="specification"
                                      name="specification"
                                      class="form-control"
                                      rows="5"
                                      placeholder="Enter specifications (one per line)&#10;e.g.&#10;Operating Pressure: 250 bar&#10;Flow Rate: 45 L/min&#10;Temperature Range: -20°C to +80°C&#10;Connection Size: 1/2&quot; BSP"></textarea>
                            <div class="form-text">
                                <i data-feather="list" class="me-1"></i>
                                Enter each specification on a new line for clear formatting
                            </div>
                        </div>

                        <!-- Additional Technical Fields -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-muted mb-0">
                                    <i data-feather="settings" class="me-2"></i>
                                    Additional Technical Fields
                                </h6>
                                <button type="button"
                                        class="btn btn-outline-info btn-sm"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#additionalFields"
                                        aria-expanded="false"
                                        aria-controls="additionalFields">
                                    <i data-feather="plus" class="me-1"></i>
                                    Show Fields
                                </button>
                            </div>

                            <div class="collapse" id="additionalFields">
                                <div class="alert alert-light border">
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i data-feather="info" class="me-1"></i>
                                            These fields are for specialized hydraulic product specifications. Leave empty if not applicable.
                                        </small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="ixu" class="form-label fw-bold">IXU</label>
                                                <input type="text"
                                                       id="ixu"
                                                       name="ixu"
                                                       class="form-control"
                                                       value="{{ old('ixu') }}"
                                                       placeholder="Enter IXU specification">
                                                <div class="form-text">IXU technical parameter</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="olx" class="form-label fw-bold">OLX</label>
                                                <input type="text"
                                                       id="olx"
                                                       name="olx"
                                                       class="form-control"
                                                       value="{{ old('olx') }}"
                                                       placeholder="Enter OLX specification">
                                                <div class="form-text">OLX technical parameter</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fam_atex" class="form-label fw-bold">FAM ATEX</label>
                                                <input type="text"
                                                       id="fam_atex"
                                                       name="fam_atex"
                                                       class="form-control"
                                                       value="{{ old('fam_atex') }}"
                                                       placeholder="Enter FAM ATEX specification">
                                                <div class="form-text">FAM ATEX certification details</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="olsw" class="form-label fw-bold">OLSW</label>
                                                <input type="text"
                                                       id="olsw"
                                                       name="olsw"
                                                       class="form-control"
                                                       value="{{ old('olsw') }}"
                                                       placeholder="Enter OLSW specification">
                                                <div class="form-text">OLSW technical parameter</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Classification & Settings -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-3">
                                <i data-feather="tag" class="me-2"></i>
                                Product Classification & Settings
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tag" class="form-label fw-bold">Product Type</label>
                                    <select id="tag" name="tag" class="form-control">
                                        <option value="">Main Product</option>
                                        <option value="2">Other Product</option>
                                    </select>
                                    <div class="form-text">
                                        <strong>Main Products:</strong> Featured products<br>
                                        <strong>Other Products:</strong> Additional catalog items
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Publication Status</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="statusSwitch"
                                               name="is_published"
                                               value="1"
                                               checked>
                                        <input type="hidden" name="status" id="statusValue" value="PUBLISHED">
                                        <label class="form-check-label" for="statusSwitch">
                                            <span id="statusText" class="fw-semibold text-success">Published</span>
                                        </label>
                                    </div>
                                    <div class="form-text">Publish or Private this product</div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">
                                Product Image <span class="text-muted">(Optional)</span>
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
                                        <p class="text-muted mb-0">This image will be used for the product</p>
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
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-md-2">
                                <i data-feather="x" class="me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i data-feather="save" class="me-2"></i>
                                Create Product
                            </button>
                        </div>
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
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    const categorySelect = document.getElementById('categorySelect');
    const subcategorySelect = document.getElementById('subcategorySelect');
    const noSubInfo = document.getElementById('noSubInfo');

    function updateSubcategories() {
        const selectedCat = categorySelect.value;
        let hasSubcategories = false;

        // Reset subcategory select
        subcategorySelect.selectedIndex = 0;

        Array.from(subcategorySelect.options).forEach(opt => {
            const cat = opt.getAttribute('data-category');
            if (!opt.value) { // keep placeholder visible
                opt.style.display = '';
                return;
            }
            if (cat === selectedCat) {
                opt.style.display = '';
                hasSubcategories = true;
            } else {
                opt.style.display = 'none';
            }
        });

        // Show/hide subcategory field and warning
        if (!hasSubcategories && selectedCat) {
            noSubInfo.style.display = 'block';
        } else {
            noSubInfo.style.display = 'none';
        }
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', updateSubcategories);
        // Initialize on page load
        updateSubcategories();
    }

    // Handle status toggle
    const statusSwitch = document.getElementById('statusSwitch');
    const statusValue = document.getElementById('statusValue');
    const statusText = document.getElementById('statusText');

    if (statusSwitch) {
        statusSwitch.addEventListener('change', function() {
            if (this.checked) {
                statusValue.value = 'PUBLISHED';
                statusText.textContent = 'Published';
                statusText.className = 'fw-semibold text-success';
            } else {
                statusValue.value = 'PRIVATE';
                statusText.textContent = 'Unpublished';
                statusText.className = 'fw-semibold text-muted';
            }
        });
    }

    // Handle additional fields toggle button
    const additionalFieldsCollapse = document.getElementById('additionalFields');
    const toggleBtn = document.querySelector('[data-bs-target="#additionalFields"]');

    if (additionalFieldsCollapse && toggleBtn) {
        // Manual toggle functionality in case Bootstrap is not working
        toggleBtn.addEventListener('click', function() {
            const isCollapsed = additionalFieldsCollapse.classList.contains('show');

            if (isCollapsed) {
                // Hide the fields
                additionalFieldsCollapse.classList.remove('show');
                toggleBtn.innerHTML = '<i data-feather="plus" class="me-1"></i>Show Fields';
            } else {
                // Show the fields
                additionalFieldsCollapse.classList.add('show');
                toggleBtn.innerHTML = '<i data-feather="minus" class="me-1"></i>Hide Fields';
            }

            // Refresh feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });

        // Bootstrap collapse events (if Bootstrap is loaded)
        additionalFieldsCollapse.addEventListener('show.bs.collapse', function () {
            toggleBtn.innerHTML = '<i data-feather="minus" class="me-1"></i>Hide Fields';
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });

        additionalFieldsCollapse.addEventListener('hide.bs.collapse', function () {
            toggleBtn.innerHTML = '<i data-feather="plus" class="me-1"></i>Show Fields';
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    }

    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
            </div>
        </div>
    </div>
</div>
@endsection
