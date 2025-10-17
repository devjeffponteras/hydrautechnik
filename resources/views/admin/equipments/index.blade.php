@extends('admin.layouts.app')

@section('pagetitle')
Manage Equipments
@endsection


@section('pagecss')
@endsection

@section('pagejs')
@endsection

@section('content')
<div class="container pd-x-0">
	<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
		<div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb breadcrumb-style1 mg-b-5">
					<li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
					<li class="breadcrumb-item active" aria-current="page">Equipments</li>
				</ol>
			</nav>
			<h4 class="mg-b-0 tx-spacing--1">Manage Equipments</h4>
		</div>
		<div class="text-right">
			<a href="{{ route('equipments.create') }}" class="btn btn-success"><i data-feather="plus"></i> Add Equipment</a>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Category</th>
						<th>Image</th>
						<th>Description</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($equipments ?? collect() as $equipment)
					<tr>
						<td>{{ $equipment->id }}</td>
						<td>{{ $equipment->name }}</td>
						<td>{{ $equipment->category ? $equipment->category->name : '-' }}</td>
						<td>
							@if($equipment->image)
								<img src="{{ asset($equipment->image) }}" alt="Equipment Image" style="width:60px; height:60px; object-fit:cover; border-radius:4px; border:1px solid #ddd;">
							@else
								<div style="width:60px; height:60px; background-color:#f8f9fa; border:1px solid #ddd; border-radius:4px; display:flex; align-items:center; justify-content:center; color:#6c757d; font-size:12px;">
									No Image
								</div>
							@endif
						</td>
						<td>{{ \Illuminate\Support\Str::limit($equipment->description, 50) }}</td>
						<td>
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
					@empty
					<tr>
						<td colspan="6" class="text-center">No equipments found.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

	<!-- Pagination placeholder if using pagination -->
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
