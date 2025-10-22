@extends('admin.layouts.app')

@section('pagetitle')
Manage Clients
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Client Management</h3>
                    <p class="text-muted mb-0">Manage your client database and relationships</p>
                </div>
                <a href="{{ route('clients.create') }}" class="btn btn-success">
                    <i data-feather="plus" class="me-1"></i>
                    Add New Client
                </a>
            </div>
        </div>
    </div>

    <!-- Client Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Clients</h6>
                            <h3 class="mb-0">{{ isset($clients) ? $clients->count() : 0 }}</h3>
                        </div>
                        <i data-feather="users" style="width: 32px; height: 32px;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">With Logos</h6>
                            <h3 class="mb-0">{{ isset($clients) ? $clients->where('logo', '!=', null)->count() : 0 }}</h3>
                        </div>
                        <i data-feather="image" style="width: 32px; height: 32px;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">With Companies</h6>
                            <h3 class="mb-0">{{ isset($clients) ? $clients->where('company', '!=', null)->where('company', '!=', '')->count() : 0 }}</h3>
                        </div>
                        <i data-feather="building" style="width: 32px; height: 32px;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Recent Clients</h6>
                            <h3 class="mb-0">{{ isset($clients) ? $clients->where('created_at', '>=', now()->subDays(30))->count() : 0 }}</h3>
                        </div>
                        <i data-feather="calendar" style="width: 32px; height: 32px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i data-feather="list" class="me-2"></i>
                        All Clients
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if(isset($clients) && $clients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">#</th>
                                        <th class="px-4 py-3">Logo</th>
                                        <th class="px-4 py-3">Client Name</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3">Phone</th>
                                        <th class="px-4 py-3">Company</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($client->logo)
                                                <img src="{{ asset($client->logo) }}"
                                                     alt="{{ $client->name }}"
                                                     class="img-thumbnail"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                                     style="width: 50px; height: 50px;">
                                                    <i data-feather="image" class="text-muted" style="width: 20px; height: 20px;"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <h6 class="mb-0">{{ $client->name }}</h6>
                                                <small class="text-muted">ID: {{ $client->id }}</small>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($client->email)
                                                <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
                                            @else
                                                <span class="text-muted">No email</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($client->phone)
                                                <a href="tel:{{ $client->phone }}" class="text-decoration-none">{{ $client->phone }}</a>
                                            @else
                                                <span class="text-muted">No phone</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($client->company)
                                                <div class="d-flex align-items-center">
                                                    <i data-feather="building" class="me-2 text-muted" style="width: 16px; height: 16px;"></i>
                                                    {{ $client->company }}
                                                </div>
                                            @else
                                                <span class="text-muted">No company</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-secondary mr-1" data-toggle="tooltip" title="View Client">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-info mr-1" data-toggle="tooltip" title="Edit Client">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete Client" onclick="return confirm('Delete this client?')">
                                                        <i data-feather="trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="users" style="width: 48px; height: 48px;" class="text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Clients Found</h5>
                            <p class="text-muted mb-4">You haven't added any clients yet. Get started by adding your first client.</p>
                            <a href="{{ route('clients.create') }}" class="btn btn-success">
                                <i data-feather="plus" class="me-1"></i>
                                Add Your First Client
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($clients) && method_exists($clients, 'hasPages') && $clients->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $clients->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@section('pagejs')
 <script>window.APP_BASE_URL = "{{ url('/') }}/";</script>
 <script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.feather) {
            feather.replace();
        }
});
</script>
@endsection
