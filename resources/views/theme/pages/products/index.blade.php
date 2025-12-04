@extends('theme.main')

@section('pagecss')
<style>
/* Product card alignment */
.product-card-wrapper {
	display: flex;
	margin-bottom: 1.5rem;
}
.product-card-wrapper .card {
	display: flex;
	flex-direction: column;
	height: 100%;
	width: 100%;
}
.product-card-wrapper .card-header {
	flex-shrink: 0;
}
.product-card-wrapper .card-header img {
	width: 100%;
	height: 200px;
	object-fit: cover;
}
.product-card-wrapper .card-body {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
}
.product-card-wrapper .grid-info {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
}
.product-card-wrapper .grid-info .btn {
	margin-top: auto;
}
</style>
@endsection

@section('content')
<div class="section mt-0 pt-0" style="background-color: white;">
	<div class="container-fluid">

			<div class="d-flex flex-column flex-md-row">

			<!-- left side nav -->
			<x-side-navigation
				:mainCategories="$mainCategories"
				:selectedCategory="$selectedCategory ?? null"
				:otherProducts="$otherProducts"
			/>

			<!-- main content -->
			<div class="col-12 col-md-10 px-0 px-md-4">

				@php
					// Only get subcategories when a specific category is selected
					if (isset($selectedCategory)) {
						$subCategories = \App\Models\ProductSubcategory::with('category')
							->where('category_id', $selectedCategory->id)
							->get();
					}
				@endphp

				{{-- Show category description if a specific category is selected --}}
				@if(isset($selectedCategory))
					<div class="mb-3">
						<h4>{{ $selectedCategory->name }}</h4>
						@if($selectedCategory->description)
							<p class="pb-2" style="opacity: .8;">
								<small>{{ $selectedCategory->description }}</small>
							</p>
						@endif
					</div>

					{{-- Show subcategories when category is selected --}}
					@if(isset($subCategories) && $subCategories->count() > 0)
						<div class="row mt-2">
							@foreach($subCategories as $sub)
								<div class="col-12 col-md-3 product-card-wrapper">
									<div class="card">
										<div class="card-header p-3 shadow bg-white">
											@if($sub->image)
												@php
													$rawPath = str_replace('\\', '/', $sub->image ?? '');
													$isFull = \Illuminate\Support\Str::startsWith($rawPath, ['http://', 'https://', '//']);
													if (!$isFull) {
														if (\Illuminate\Support\Str::startsWith($rawPath, 'public/')) {
															$rawPath = 'storage/' . substr($rawPath, 7);
														} elseif (!\Illuminate\Support\Str::startsWith($rawPath, 'storage/')) {
															$rawPath = ltrim($rawPath, '/');
															$rawPath = 'storage/' . $rawPath;
														}
														$finalUrl = asset($rawPath);
													} else {
														$finalUrl = $rawPath;
													}
												@endphp
												<img src="{{ $finalUrl }}" alt="{{ $sub->name }}" style="width:100%; height:200px; object-fit:cover;">
											@else
												<img src="{{ asset('images/products/prd' . (($loop->index % 4) + 1) . '.jpg') }}" alt="{{ $sub->name }}" style="width:100%; height:200px; object-fit:cover;">
											@endif
										</div>
										<div class="card-body">
											<div class="grid-info text-center">
												<h5 class="text-center">{{ $sub->name }}</h5>
												<a href="{{ route('sub-products', ['subcategory' => $sub->id]) }}" class="btn btn-warning btn-sm">View Products</a>
											</div>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					@else
						<div class="row mt-2">
							<div class="col-12 text-center py-5">
								<h5 class="text-muted">No Subcategories Available</h5>
								<p class="text-muted">No subcategories found for {{ $selectedCategory->name }}.</p>
								<a href="{{ route('products') }}" class="btn btn-primary btn-sm">View All Products</a>
							</div>
						</div>
					@endif
				@else
					{{-- Show page title for all products --}}
					<div class="mb-3">
						<h4 class="mb-0">All Products</h4>
						<p class="pb-2 text-muted">
							<small>Browse all available products in our catalog</small>
						</p>
					</div>
				@endif

				{{-- Display All Products Section --}}
				@if(!isset($selectedCategory) && isset($allProducts) && $allProducts->count() > 0)
					<div class="row mt-2">
						@foreach($allProducts as $product)
							<div class="col-12 col-md-3 product-card-wrapper">
								<div class="card">
									<div class="card-header p-3 shadow bg-white">
										@if($product->image)
											@php
												$rawPath = str_replace('\\', '/', $product->image ?? '');
												$isFull = \Illuminate\Support\Str::startsWith($rawPath, ['http://', 'https://', '//']);
												if (!$isFull) {
													if (\Illuminate\Support\Str::startsWith($rawPath, 'public/')) {
														$rawPath = 'storage/' . substr($rawPath, 7);
													} elseif (!\Illuminate\Support\Str::startsWith($rawPath, 'storage/')) {
														$rawPath = ltrim($rawPath, '/');
														$rawPath = 'storage/' . $rawPath;
													}
													$finalUrl = asset($rawPath);
												} else {
													$finalUrl = $rawPath;
												}
											@endphp
											<img src="{{ $finalUrl }}" alt="{{ $product->name }}" style="width:100%; height:200px; object-fit:cover;">
										@else
											<img src="{{ asset('images/products/prd' . (($loop->index % 4) + 1) . '.jpg') }}" alt="{{ $product->name }}" style="width:100%; height:200px; object-fit:cover;">
										@endif
									</div>
									<div class="card-body">
										<div class="grid-info text-center">
											<h5 class="text-center">{{ $product->name }}</h5>
											<a href="{{ route('view-products', $product->id) }}" class="btn btn-warning btn-sm">View Details</a>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					</div>

					{{-- pagination for all products --}}
					<div class="row mt-3">
						<div class="col-12 d-flex justify-content-center">
							{!! $allProducts->appends(request()->input())->links('pagination::simple-bootstrap-4') !!}
						</div>
					</div>
				@elseif(!isset($selectedCategory))
					<div class="row mt-2">
						<div class="col-12 text-center py-5">
							<h5 class="text-muted">No Products Available</h5>
							<p class="text-muted">Please check back later for new products.</p>
						</div>
					</div>
				@endif

			</div>
		</div>

	</div>
</div>
@endsection

@section('pagejs')
<script>

</script>
@endsection
