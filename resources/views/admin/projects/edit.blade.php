@extends('admin.layouts.app')

@section('pagetitle')
Edit Project
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1">Edit Project</h3>
                <p class="text-muted mb-0">Update project details</p>
            </div>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back to Projects</a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="edit" class="me-2"></i>
                        Update Project
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Project Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Project Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $project->name) }}" required>
                            <div class="form-text">Update the project title</div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Project Description <span class="text-muted">(Optional)</span></label>
                            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Describe the project, scope, and key outcomes...">{{ old('description', $project->description) }}</textarea>
                            <div class="form-text"><i data-feather="file-text" class="me-1"></i> A concise description helps users understand the project.</div>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category" class="form-label fw-bold">Project Category</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">Select category (optional)</option>
                                <option value="COMPLETED" {{ old('category', $project->category)=='COMPLETED' ? 'selected' : '' }}>Completed Projects</option>
                                <option value="ONGOING" {{ old('category', $project->category)=='ONGOING' ? 'selected' : '' }}>Ongoing Projects</option>
                                <option value="OTHER" {{ old('category', $project->category)=='OTHER' ? 'selected' : '' }}>Other Projects</option>
                            </select>
                            <div class="form-text">Choose which group this project belongs to.</div>
                        </div>

                        <!-- Publication Status -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Publication Status</label>
                            <div class="form-check form-switch">
                                @php $initialStatus = old('status', $project->status ?? 'PUBLISHED'); @endphp
                                <input type="checkbox" class="form-check-input" id="statusSwitch" name="is_published" value="1" {{ strtoupper($initialStatus) == 'PUBLISHED' ? 'checked' : '' }}>
                                <input type="hidden" name="status" id="statusValue" value="{{ strtoupper($initialStatus) }}">
                                <label class="form-check-label" for="statusSwitch">
                                    <span id="statusText" class="fw-semibold {{ strtoupper($initialStatus) == 'PUBLISHED' ? 'text-success' : 'text-muted' }}">{{ strtoupper($initialStatus) == 'PUBLISHED' ? 'Published' : 'Private' }}</span>
                                </label>
                            </div>
                            <div class="form-text">Toggle to publish or unpublish this project</div>
                        </div>

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">Project Image <span class="text-muted">(Optional)</span></label>

                            <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">

                            <div id="imagePreview" class="mt-3" style="display: {{ !empty($project->image) ? 'block' : 'none' }};">
                                <div class="d-flex align-items-center">
                                    <img id="previewImg" src="{{ !empty($project->image) ? asset($project->image) : '' }}" alt="Preview" class="img-thumbnail me-3" style="max-width: 180px; max-height: 120px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1 text-success">Current Image</h6>
                                        <p class="text-muted mb-0">Upload to replace the current image</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-text"><i data-feather="upload" class="me-1"></i> Supported: JPEG, PNG, JPG, GIF (Max: 2MB)</div>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="gap: 16px;">
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                                <i data-feather="x" class="me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i data-feather="save" class="me-2"></i>
                                Update Project
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
                // Use PRIVATE to match ProjectsController validation and UI semantics
                statusValue.value = 'PRIVATE';
                statusText.textContent = 'Private';
                statusText.className = 'fw-semibold text-muted';
            }
        });
    }
});
</script>
@endsection
