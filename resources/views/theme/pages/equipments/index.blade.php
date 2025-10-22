@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
<div class="section sub-pages-hyd-container mt-0 pt-0" style="background-color: white;">
	<div class="container-fluid px-4 mx-4">

		<h3>Equipments</h3>
		<p class="pb-4" style="opacity: .8;">
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl. Fusce eleifend ullamcorper lacinia.
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

	</div>
</div>
@endsection

@section('pagejs')
<script>

</script>
@endsection
