@extends('theme.main')

@section('pagecss')
<style>
/* Compact pagination styling for equipments page */
.pagination {
	display: inline-flex;
	padding-left: 0;
	margin: 0;
	list-style: none;
}
.pagination .page-item { margin: 0 .18rem; }
.pagination .page-link {
	color: #0b2e4a;
	background: #fff;
	border: 1px solid #e9ecef;
	padding: .38rem .62rem;
	font-size: .95rem;
	border-radius: .35rem;
}
.pagination .page-link:hover { background: #f1f5f8; color: #0b2e4a; }
.pagination .page-item.active .page-link {
	background-color: #163a5b;
	border-color: #163a5b;
	color: #fff;
}
.pagination .page-item.disabled .page-link { color: #6c757d; pointer-events: none; background: transparent; border-color: transparent; }

@media (max-width: 576px) {
	.pagination .page-link { padding: .28rem .48rem; font-size: .88rem; }
}
</style>
@endsection

@section('content')
<div class="section sub-pages-hyd-container mt-0 pt-0" style="background-color: white;">
	<div class="container-fluid px-4 mx-4">

		<h3>Equipments</h3>
		<p class="text-muted" style="line-height:1.6; text-align:justify;">
			Hydrautechnik supplies and services a wide range of industrial and mobile equipment for hydraulic and lubrication systems. We offer equipment procurement, on-site installation, preventive maintenance, testing and inspection, and certified repair services. Our inventory includes pumps, power units, filtration systems, valves, and related accessories — all supported by experienced technicians and genuine spare parts to ensure reliable operation and long-term performance.
		</p>

		<div class="row col-12">

			@forelse($equipments ?? collect() as $equipment)
				<div class="col-md-3">
					<div class="card">
						<div class="card-header p-3 shadow bg-white">
							@php
								$img = $equipment->image ?? '';
								// if image is a storage path, use asset() helper; otherwise fallback to theme image
								if($img && (str_contains($img, 'storage/') || str_starts_with($img, 'storage/'))){
									$imgUrl = asset($img);
								} elseif($img) {
									$imgUrl = asset($img);
								} else {
									$imgUrl = asset('images/equipments/he1.jpg');
								}
							@endphp
							<img src="{{ $imgUrl }}" alt="{{ $equipment->name }}">
						</div>
						<div class="card-body">
							<div class="grid-info">
								<h5><a href="#">{{ $equipment->name }}</a></h5>
								<p style="opacity: .8">{{ $equipment->description }}</p>
							</div>
						</div>
					</div>
				</div>
			@empty
				<div class="col-12">
					<p>No equipments found.</p>
				</div>
			@endforelse

		</div>

		{{-- Pagination links for equipments (5 per page) --}}
		<div class="row">
			<div class="col-12 d-flex justify-content-center mt-4">
				{!! $equipments->links('pagination::bootstrap-4') !!}
			</div>
		</div>

	</div>
</div>
@endsection

@section('pagejs')
<script>

</script>
@endsection
