@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
@php
	// Filter products based on selected subcategory or selected category
	if (isset($selectedSubcategory)) {
		// Show products from specific subcategory
		$products = \App\Models\Product::where('subcategory_id', $selectedSubcategory->id)->get();
		$category = $selectedSubcategory->category;
		$subcategory = $selectedSubcategory;
	} elseif (isset($selectedCategory)) {
		// Category passed directly (no subcategories exist for this category)
		// Show ONLY products that have category_id matching AND no subcategory_id
		$products = \App\Models\Product::where('category_id', $selectedCategory->id)
			->whereNull('subcategory_id')
			->get();
		$category = $selectedCategory;
		$subcategory = null;
	} else {
		// Show all products
		$products = \App\Models\Product::all();
		$category = $products->first() && $products->first()->category ? $products->first()->category : null;
		$subcategory = $products->first() && $products->first()->subcategory ? $products->first()->subcategory : null;
	}
@endphp
<div class="section sub-pages-hyd-container mt-0 pt-0" style="background-color: rgb(255, 255, 255);">
	<div class="container-fluid">
		<div class="row col-12 px-3">
			<x-side-navigation :mainCategories="$mainCategories" :selectedCategory="$category ?? null"></x-side-navigation>
			<div class="col-12 col-md-10">
				<div class="mb-3">
					<small>
						@if($category)
							<a href="{{ route('products.by-category', $category->id) }}">{{ $category->name }}</a>
						@else
							<a href="{{ route('products') }}">Product Categories</a>
						@endif
						&gt;
						@if($subcategory)
							<span style="font-weight: 600;">{{ $subcategory->name }}</span>
						@elseif($category)
							<span style="font-weight: 600;">{{ $category->name }} Products</span>
						@else
							<span>All Products</span>
						@endif
					</small>
				</div>

				@if($subcategory && $subcategory->description)
					<div class="mb-3">
						<h4>{{ $subcategory->name }}</h4>
						<p class="pb-2" style="opacity: .8;">
							<small>{{ $subcategory->description }}</small>
						</p>
					</div>
				@elseif(isset($selectedCategory))
					@php
						$hasSubs = \App\Models\ProductSubcategory::where('category_id', $selectedCategory->id)->exists();
					@endphp
					@if(!$hasSubs && $selectedCategory->description)
						<div class="mb-3">
							<h4>{{ $selectedCategory->name }}</h4>
							<p class="pb-2" style="opacity: .8;">
								<small>{{ $selectedCategory->description }}</small>
							</p>
						</div>
					@endif
				@endif

				<div class="row col-12 mt-4">
					@forelse($products as $product)
						<div class="col-md-3 mb-4">
							<div class="card">
								<div class="card-header p-3 shadow bg-white">
									@if($product->image)
										<img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width:100%; height:200px; object-fit:cover;">
									@else
										<img src="{{ asset('storage/products/prd' . (($loop->index % 4) + 1) . '.jpg') }}" alt="{{ $product->name }}" style="width:100%; height:200px; object-fit:cover;">
									@endif
								</div>
								<div class="card-body">
									<div class="grid-info text-center">
										<h5 class="text-center">{{ $product->name }}</h5>
										<a href="{{ route('view-products', ['id' => $product->id]) }}" class="btn btn-warning btn-sm mt-2">View Details</a>
									</div>
								</div>
							</div>
						</div>
					@empty
						<div class="col-12">
							<div class="text-center py-5">
								<h5 class="text-muted">No products available</h5>
								@if($subcategory)
									<p class="text-muted">No products found for {{ $subcategory->name }}.</p>
									<a href="{{ route('products.by-category', $category->id) }}" class="btn btn-primary btn-sm">Back to {{ $category->name }}</a>
								@elseif(isset($selectedCategory))
									<p class="text-muted">No products found for {{ $selectedCategory->name }}.</p>
									<a href="{{ route('products') }}" class="btn btn-primary btn-sm">View All Categories</a>
								@else
									<p class="text-muted">Please add some products to display them here.</p>
								@endif
							</div>
						</div>
					@endforelse
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
