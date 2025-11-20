@extends('admin.layouts.app')

@section('pagetitle')
Edit Client - {{ $client->name }}
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Edit Client</h3>
                    <p class="text-muted mb-0">Update client information</p>
                </div>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                    <i data-feather="arrow-left" class="me-1"></i>
                    Back to Clients
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
                        <i data-feather="edit-3" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Client Editing Guide</h6>
                        <p class="mb-2">Update the client information below. Only modify fields that need changes.</p>
                        <small><strong>Tip:</strong> Leave the logo field empty to keep the current logo, or upload a new one to replace it.</small>
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
                        <i data-feather="edit-2" class="me-2"></i>
                        Edit Client Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('clients.update', $client->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label fw-bold">
                                        <i data-feather="user" class="me-1"></i>
                                        Client Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $client->name) }}"
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
                                           id="email" name="email" value="{{ old('email', $client->email) }}"
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
                                           id="phone" name="phone" value="{{ old('phone', $client->phone) }}"
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
                                           id="company" name="company" value="{{ old('company', $client->company) }}"
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

                            <!-- Current Logo Display -->
                            @if($client->logo)
                                <div class="mb-3" id="current-logo-block">
                                    <div class="card bg-light">
                                        <div class="card-body text-center py-3">
                                            <h6 class="card-title">Current Logo</h6>
                                            <img id="current-logo" src="{{ asset($client->logo) }}" alt="Current Logo"
                                                 class="img-thumbnail" style="max-width: 200px; max-height: 200px;" />
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="input-group">
                                <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                       id="logo" name="logo" accept="image/*">
                                <label class="input-group-text" for="logo">
                                    <i data-feather="upload" class="me-1"></i>
                                    Choose New Logo
                                </label>
                            </div>
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i data-feather="info" class="me-1"></i>
                                Accepted formats: JPG, PNG, GIF. Max size: 2MB. Leave empty to keep current logo.
                            </div>

                            <!-- New Image Preview -->
                            <div class="mt-3" id="preview-block" style="display:none;">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h6 class="card-title">New Logo Preview</h6>
                                        <img id="preview-image" src="#" alt="New Logo Preview"
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
                                      placeholder="Enter complete address">{{ old('address', $client->address) }}</textarea>
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
                                      placeholder="Any additional notes about the client">{{ old('notes', $client->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                         <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="gap: 16px;">
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                                <i data-feather="x" class="me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save" class="me-1"></i>
                                Update Client
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
    var currentLogoBlock = document.getElementById('current-logo-block');

    if (logoInput) {
        logoInput.addEventListener('change', function(event) {
            const [file] = logoInput.files;
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewBlock.style.display = 'block';
                    if (currentLogoBlock) {
                        currentLogoBlock.style.opacity = '0.5';
                    }
                };
                reader.readAsDataURL(file);
            } else {
                previewBlock.style.display = 'none';
                if (currentLogoBlock) {
                    currentLogoBlock.style.opacity = '1';
                }
            }
        });
    }
});
</script>
@endsection
