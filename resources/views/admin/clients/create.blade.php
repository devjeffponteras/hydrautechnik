@extends('admin.layouts.app')

@section('pagetitle')
Create New Client
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Create New Client</h3>
                    <p class="text-muted mb-0">Add a new client to your database</p>
                </div>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                    <i data-feather="arrow-left" class="me-1"></i>
                    Back to Clients
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <!-- Helpful Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0">
                <div class="d-flex">
                    <div class="me-3">
                        <i data-feather="info" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Client Creation Guide</h6>
                        <p class="mb-2">Fill out the form below to add a new client to your database.</p>
                        <small><strong>Tip:</strong> Complete client profiles help with better relationship management and communication tracking.</small>
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
                        <i data-feather="user-plus" class="me-2"></i>
                        Client Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label fw-bold">
                                        <i data-feather="user" class="me-1"></i>
                                        Client Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Enter client full name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label fw-bold">
                                        <i data-feather="mail" class="me-1"></i>
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}"
                                           placeholder="client@example.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label fw-bold">
                                        <i data-feather="phone" class="me-1"></i>
                                        Phone Number
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}"
                                           placeholder="Enter phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="company" class="form-label fw-bold">
                                        <i data-feather="building" class="me-1"></i>
                                        Company Name
                                    </label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror"
                                           id="company" name="company" value="{{ old('company') }}"
                                           placeholder="Enter company name">
                                    @error('company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Company Logo -->
                        <div class="mb-4">
                            <label for="logo" class="form-label fw-bold">
                                <i data-feather="image" class="me-1"></i>
                                Company Logo
                            </label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                       id="logo" name="logo" accept="image/*">
                                <label class="input-group-text" for="logo">
                                    <i data-feather="upload" class="me-1"></i>
                                    Choose Logo
                                </label>
                            </div>
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i data-feather="info" class="me-1"></i>
                                Accepted formats: JPG, PNG, GIF. Max size: 2MB
                            </div>

                            <!-- Image Preview -->
                            <div class="mt-3" id="preview-block" style="display:none;">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h6 class="card-title">Logo Preview</h6>
                                        <img id="preview-image" src="#" alt="Logo Preview"
                                             class="img-thumbnail" style="max-width: 200px; max-height: 200px;" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold">
                                <i data-feather="map-pin" class="me-1"></i>
                                Address
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3"
                                      placeholder="Enter complete address">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">
                                <i data-feather="file-text" class="me-1"></i>
                                Notes
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Any additional notes about the client">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                                <i data-feather="x" class="me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i data-feather="save" class="me-1"></i>
                                Create Client
                            </button>
                        </div>
                    </form>
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

    // Image preview functionality
    var logoInput = document.getElementById('logo');
    var previewBlock = document.getElementById('preview-block');
    var previewImage = document.getElementById('preview-image');

    if (logoInput) {
        logoInput.addEventListener('change', function(event) {
            const [file] = logoInput.files;
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewBlock.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewBlock.style.display = 'none';
            }
        });
    }
});
</script>
@endsection
