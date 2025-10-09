@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
<div class="section sub-pages-hyd-container mt-0 pt-0" style="background-color: white;">
	<div class="container-fluid">

		<div class="row col-12 px-3">

			<!-- left side nav -->
			@php
				$mainCategories = \App\Models\ProductCategory::all();
			@endphp
			<x-side-navigation :mainCategories="$mainCategories" :selectedCategory="$selectedCategory ?? null" />

			<!-- main content -->
			<div class="col-12 col-md-10">
				@php
					$categories = \App\Models\ProductCategory::all();

					// Filter subcategories based on selected category
					if (isset($selectedCategory)) {
						$subCategories = \App\Models\ProductSubcategory::with('category')
							->where('category_id', $selectedCategory->id)
							->get();

						// Also get products directly attached to this category that have no subcategory
						$categoryProducts = \App\Models\Product::where('category_id', $selectedCategory->id)
							->whereNull('subcategory_id')
							->get();
					} else {
						$subCategories = \App\Models\ProductSubcategory::with('category')->get();
						$categoryProducts = collect();
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
				@else
					{{-- Show all categories as cards with storage when no filter is selected --}}
					<div class="row col-12 mt-2">
						@foreach($categories as $cat)
							<div class="col-md-3 mb-4">
								<div class="card">
									<div class="card-header p-3 shadow bg-white">
										@if($cat->image)
											@php
												$rawPath = str_replace('\\', '/', $cat->image ?? '');
												$isFull = \Illuminate\Support\Str::startsWith($rawPath, ['http://', 'https://', '//']);
												if (!$isFull) {
													if (\Illuminate\Support\Str::startsWith($rawPath, 'public/')) {
														$rawPath = 'storage/' . substr($rawPath, 7);
														$finalUrl = asset($rawPath);
													} elseif (\Illuminate\Support\Str::startsWith($rawPath, 'storage/') || \Illuminate\Support\Str::startsWith($rawPath, 'storage/')) {
														$finalUrl = asset(ltrim($rawPath, '/'));
													} else {
														$rawPath = ltrim($rawPath, '/');
														$finalUrl = asset($rawPath);
													}
												} else {
													$finalUrl = $rawPath;
												}
											@endphp
											<img src="{{ $finalUrl }}" alt="{{ $cat->name }}" style="width:100%; height:200px; object-fit:cover;">
										@else
											<img src="{{ asset('images/products/prd' . (($loop->index % 4) + 1) . '.jpg') }}" alt="{{ $cat->name }}" style="width:100%; height:200px; object-fit:cover;">
										@endif
									</div>
									<div class="card-body">
										<div class="grid-info text-center">
											<h5 class="text-center">{{ $cat->name }}</h5>
											<a href="{{ route('products.by-category', $cat->id) }}" class="btn btn-warning btn-sm">View Products</a>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				@endif

				<div class="row col-12 mt-4">
					@forelse($subCategories as $sub)
						<div class="col-md-3 mb-4">
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
					@empty
						<div class="col-12">
							<div class="text-center py-5">
								<h5 class="text-muted">No subcategories available</h5>
								@if(isset($selectedCategory))
									<p class="text-muted">No subcategories found for {{ $selectedCategory->name }}.</p>
									<a href="{{ route('products') }}" class="btn btn-primary btn-sm">View All Categories</a>
								@else
									<p class="text-muted">Please add some subcategories to display them here.</p>
								@endif
							</div>
						</div>
					@endforelse

					{{-- Show products that belong directly to the category (no subcategory) --}}
					@if(isset($categoryProducts) && $categoryProducts->count())
						@foreach($categoryProducts as $product)
							<div class="col-md-3 mb-4">
								<div class="card">
									<div class="card-header p-3 shadow bg-white">
										@if($product->image)
											@php
												$rawPath = str_replace('\\', '/', $product->image ?? '');
												$isFull = \Illuminate\Support\Str::startsWith($rawPath, ['http://', 'https://', '//']);
												if (!$isFull) {
													if (\Illuminate\Support\Str::startsWith($rawPath, 'public/')) {
														$rawPath = 'storage/' . substr($rawPath, 7);
														$finalUrl = asset($rawPath);
													} elseif (\Illuminate\Support\Str::startsWith($rawPath, 'storage/') || \Illuminate\Support\Str::startsWith($rawPath, 'storage/')) {
														$finalUrl = asset(ltrim($rawPath, '/'));
													} else {
														$rawPath = ltrim($rawPath, '/');
														$finalUrl = asset($rawPath);
													}
												} else {
													$finalUrl = $rawPath;
												}
											@endphp
											<img src="{{ $finalUrl }}" alt="{{ $product->name }}" style="width:100%; height:200px; object-fit:cover;">
										@else
											<img src="{{ asset('/images/products/prd' . (($loop->index % 4) + 1) . '.jpg') }}" alt="{{ $product->name }}" style="width:100%; height:200px; object-fit:cover;">
										@endif
									</div>
									<div class="card-body">
										<div class="grid-info text-center">
											<h5 class="text-center">{{ $product->name }}</h5>
											<a href="{{ route('view-products', ['id' => $product->id]) }}" class="btn btn-warning btn-sm">View Details</a>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					@endif
				</div>

			</div>
		</div>

	</div>
</div>
@endsection

@section('pagejs')
<script>

</script>
@endsection
