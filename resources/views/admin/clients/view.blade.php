@extends('admin.layouts.app')

@section('pagetitle')
{{ $client->name }} - Client Details
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Client Details</h3>
                    <p class="text-muted mb-0">View complete information for this client</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-outline-primary">
                        <i data-feather="edit-2" class="me-1"></i>
                        Edit Client
                    </a>
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="arrow-left" class="me-1"></i>
                        Back to Clients
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Client Logo -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i data-feather="image" class="me-2"></i>
                        Client Logo
                    </h6>
                </div>
                <div class="card-body text-center d-flex align-items-center justify-content-center">
                    @if($client->logo)
                        <div>
                            <img src="{{ asset($client->logo) }}"
                                 alt="{{ $client->name }}"
                                 class="img-fluid rounded shadow"
                                 style="max-width: 100%; max-height: 300px; object-fit: cover;">
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="image" style="width: 48px; height: 48px;" class="text-muted"></i>
                            </div>
                            <h6 class="text-muted">No Logo Available</h6>
                            <p class="text-muted small mb-0">This client doesn't have a logo</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Client Information -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="user" class="me-2"></i>
                        {{ $client->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Client Name</label>
                                <div class="p-2 bg-light rounded">
                                    <i data-feather="user" class="me-2"></i>
                                    {{ $client->name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Email Address</label>
                                <div class="p-2 bg-light rounded">
                                    <i data-feather="mail" class="me-2"></i>
                                    {{ $client->email ?: 'No email provided' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Phone Number</label>
                                <div class="p-2 bg-light rounded">
                                    <i data-feather="phone" class="me-2"></i>
                                    {{ $client->phone ?: 'No phone number provided' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Company</label>
                                <div class="p-2 bg-light rounded">
                                    <i data-feather="building" class="me-2"></i>
                                    {{ $client->company ?: 'No company specified' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    @if($client->address)
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">
                                <i data-feather="map-pin" class="me-1"></i>
                                Address
                            </label>
                            <div class="p-3 bg-light rounded">
                                {{ $client->address }}
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">
                                <i data-feather="map-pin" class="me-1"></i>
                                Address
                            </label>
                            <div class="p-3 bg-light rounded text-muted">
                                <em>No address provided for this client.</em>
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($client->notes)
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">
                                <i data-feather="file-text" class="me-1"></i>
                                Notes
                            </label>
                            <div class="p-3 bg-light rounded">
                                {{ $client->notes }}
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">
                                <i data-feather="file-text" class="me-1"></i>
                                Notes
                            </label>
                            <div class="p-3 bg-light rounded text-muted">
                                <em>No notes available for this client.</em>
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
                                    {{ $client->created_at->format('M d, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-bold text-muted small">Last Updated</label>
                                <div class="text-muted small">
                                    <i data-feather="clock" class="me-1"></i>
                                    {{ $client->updated_at->format('M d, Y \a\t g:i A') }}
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
