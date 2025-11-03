@extends('admin.layouts.app')

@section('pagetitle')
Create Service
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Create Service</h3>
                    <p class="text-muted mb-0">Add a new service to the website</p>
                </div>
                <a href="{{ route('services.index') }}" class="btn btn-secondary">Back to Services</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Helpful Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0">
                <div class="d-flex">
                    <div class="me-3">
                        <i data-feather="info" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Service Creation Guide</h6>
                        <p class="mb-2">Fill out the form below to add a new service visible on the company capabilities page.</p>
                        <small><strong>Tip:</strong> Use a clear service title and a helpful description. Upload an image (optional) sized around 1200x800 for best appearance.</small>
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
                        Service Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Service Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Service Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="e.g., Hydraulic System Design" value="{{ old('name') }}" required>
                            <div class="form-text">Give the service a clear, descriptive title</div>
                        </div>

                        <!-- Service Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Service Description <span class="text-muted">(Optional)</span></label>
                            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Describe the service, benefits, and scope...">{{ old('description') }}</textarea>
                            <div class="form-text"><i data-feather="file-text" class="me-1"></i> A helpful description improves the service page performance in search.</div>
                        </div>

                        <!-- Publication Status -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Publication Status</label>
                            <div class="form-check form-switch">
                                <!-- ensure we always send a value for is_published (0 when unchecked, 1 when checked) -->
                                <input type="hidden" name="is_published" value="0">
                                <input type="checkbox" class="form-check-input" id="statusSwitch" name="is_published" value="1" checked>
                                <input type="hidden" name="status" id="statusValue" value="PUBLISHED">
                                <label class="form-check-label" for="statusSwitch">
                                    <span id="statusText" class="fw-semibold text-success">Published</span>
                                </label>
                            </div>
                            <div class="form-text">Toggle to publish or unpublish this service</div>
                        </div>

                        <!-- Service Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">Service Image <span class="text-muted">(Optional)</span></label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">

                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <img id="previewImg" src="" alt="Preview" class="img-thumbnail me-3" style="max-width: 180px; max-height: 120px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1 text-success">Image Preview</h6>
                                        <p class="text-muted mb-0">This image will appear on the service details page</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-text"><i data-feather="upload" class="me-1"></i> Supported: JPEG, PNG, JPG, GIF (Max: 2MB)</div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary me-md-2">
                                <i data-feather="x" class="me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i data-feather="save" class="me-2"></i>
                                Create Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
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
                // Use PRIVATE to align with Projects and controller validation
                statusValue.value = 'PRIVATE';
                statusText.textContent = 'Private';
                statusText.className = 'fw-semibold text-muted';
            }
        });
    }
});
</script>
@endsection
