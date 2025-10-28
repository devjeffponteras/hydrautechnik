@extends('admin.layouts.app')

@section('pagetitle')
Manage Equipments
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Equipment Management</h3>
                    <p class="text-muted mb-0">Manage your hydraulic equipment catalog</p>
                </div>
                <a href="{{ route('equipments.create') }}" class="btn btn-success">
                    <i data-feather="plus" class="me-1"></i>
                    Add New Equipment
                </a>
            </div>
        </div>
    </div>

    <!-- Equipment Statistics -->
    <div class="row mb-4 justify-content-center">
        <div class="col-md-3 col-lg-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Equipment</h6>
                            <h3 class="mb-0">{{ isset($equipments) ? $equipments->count() : 0 }}</h3>
                        </div>
                        <i data-feather="tool" style="width: 32px; height: 32px;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">With Images</h6>
                            <h3 class="mb-0">{{ isset($equipments) ? $equipments->where('image', '!=', null)->count() : 0 }}</h3>
                        </div>
                        <i data-feather="image" style="width: 32px; height: 32px;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Categories</h6>
                            <h3 class="mb-0">{{ isset($equipments) ? $equipments->pluck('category')->unique()->filter()->count() : 0 }}</h3>
                        </div>
                        <i data-feather="folder" style="width: 32px; height: 32px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipment Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i data-feather="list" class="me-2"></i>
                        All Equipment
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if(isset($equipments) && $equipments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">#</th>
                                        <th class="px-4 py-3">Image</th>
                                        <th class="px-4 py-3">Equipment Name</th>
                                        <th class="px-4 py-3">Category</th>
                                        <th class="px-4 py-3">Description</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipments as $equipment)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-light text-dark">{{ $loop->iteration }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($equipment->image)
                                                <img src="{{ asset($equipment->image) }}"
                                                     alt="{{ $equipment->name }}"
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
                                                <h6 class="mb-0">{{ $equipment->name }}</h6>
                                                <small class="text-muted">ID: {{ $equipment->id }}</small>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($equipment->category)
                                                <span class="badge bg-primary">{{ $equipment->category->name }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Category</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div style="max-width: 200px;">
                                                {{ \Illuminate\Support\Str::limit($equipment->description, 80) }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('equipments.show', $equipment->id) }}" class="btn btn-sm btn-secondary mr-1" data-toggle="tooltip" title="View Equipment">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="{{ route('equipments.edit', $equipment->id) }}" class="btn btn-sm btn-info mr-1" data-toggle="tooltip" title="Edit Equipment">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <form action="{{ route('equipments.destroy', $equipment->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete Equipment" onclick="return confirm('Delete this equipment?')">
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
                                <i data-feather="tool" style="width: 48px; height: 48px;" class="text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Equipment Found</h5>
                            <p class="text-muted mb-4">You haven't added any equipment yet. Get started by adding your first equipment item.</p>
                            <a href="{{ route('equipments.create') }}" class="btn btn-success">
                                <i data-feather="plus" class="me-1"></i>
                                Add Your First Equipment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($equipments) && method_exists($equipments, 'hasPages') && $equipments->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $equipments->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@section('pagejs')
<script src="https://unpkg.com/feather-icons"></script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		if (window.feather) {
			feather.replace();
		}
		// Enable Bootstrap tooltips if using Bootstrap
		if (window.$ && $.fn.tooltip) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
</script>
@endsection
