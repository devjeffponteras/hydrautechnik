@extends('admin.layouts.app')

@section('pagetitle')
Edit Service
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Edit Service</h3>
                    <p class="text-muted mb-0">Update service details</p>
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

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="edit" class="me-2"></i>
                        Update Service
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Helpful Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info border-0">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i data-feather="info" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Service Update Guide</h6>
                                        <p class="mb-2">Update the fields below to change the service details shown on the front page.</p>
                                        <small><strong>Tip:</strong> Keep the description concise and update the image only if necessary. Published services are visible to visitors.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Service Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Service Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $service->name) }}" required>
                            <div class="form-text">Update the service title</div>
                        </div>

                        <!-- Service Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Service Description <span class="text-muted">(Optional)</span></label>
                            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Describe the service, benefits, and scope...">{{ old('description', $service->description) }}</textarea>
                            <div class="form-text"><i data-feather="file-text" class="me-1"></i> A helpful description improves the service page performance in search.</div>
                        </div>

                        <!-- Publication Status -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Publication Status</label>
                            <div class="form-check form-switch">
                                @php $initialStatus = old('status', $service->status ?? 'PUBLISHED'); @endphp
                                <!-- ensure we always send a value for is_published (0 when unchecked, 1 when checked) -->
                                <input type="hidden" name="is_published" value="0">
                                <input type="checkbox" class="form-check-input" id="statusSwitch" name="is_published" value="1" {{ strtoupper($initialStatus) == 'PUBLISHED' ? 'checked' : '' }}>
                                <input type="hidden" name="status" id="statusValue" value="{{ strtoupper($initialStatus) }}">
                                <label class="form-check-label" for="statusSwitch">
                                    <span id="statusText" class="fw-semibold {{ strtoupper($initialStatus) == 'PUBLISHED' ? 'text-success' : 'text-muted' }}">{{ strtoupper($initialStatus) == 'PUBLISHED' ? 'Published' : 'Private' }}</span>
                                </label>
                            </div>
                            <div class="form-text">Toggle to publish or unpublish this service</div>
                        </div>

                        <!-- Service Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">Service Image <span class="text-muted">(Optional)</span></label>

                            <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">

                            <div id="imagePreview" class="mt-3" style="display: {{ !empty($service->image) ? 'block' : 'none' }};">
                                <div class="d-flex align-items-center">
                                    <img id="previewImg" src="{{ !empty($service->image) ? asset($service->image) : '' }}" alt="Preview" class="img-thumbnail me-3" style="max-width: 180px; max-height: 120px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1 text-success">Current Image</h6>
                                        <p class="text-muted mb-0">Upload to replace the current image</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-text"><i data-feather="upload" class="me-1"></i> Supported: JPEG, PNG, JPG, GIF (Max: 2MB)</div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="gap: 16px;">
                            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                                <i data-feather="x" class="me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i data-feather="save" class="me-2"></i>
                                Update Service
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
                statusValue.value = 'PRIVATE';
                statusText.textContent = 'Private';
                statusText.className = 'fw-semibold text-muted';
            }
        });
    }
});
</script>
@endsection
