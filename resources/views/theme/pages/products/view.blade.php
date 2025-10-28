@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
<div class="section sub-pages-hyd-container mt-0 pt-0" style="background-color: white;">
	<div class="container-fluid">

		<div class="row col-12 px-3">

			<!-- left side nav -->
			<x-side-navigation
				:mainCategories="$mainCategories"
				:selectedCategory="$selectedCategory ?? null"
				:otherProducts="$otherProducts"
			/>

			<!-- main content -->
			<div class="col-12 col-md-10">
				<p class="pb-2" style="opacity: .8;">
					<small>
						@php
							// Debug: Let's see what we have
							$category = null;
							$subcategory = null;

							// Try multiple ways to get the category
							if (isset($product->category) && $product->category) {
								$category = $product->category;
							}
							// Try through subcategory
							elseif (isset($product->subcategory) && $product->subcategory && isset($product->subcategory->category)) {
								$category = $product->subcategory->category;
							}
							// Try direct category_id lookup
							elseif (isset($product->category_id) && $product->category_id) {
								$category = \App\Models\ProductCategory::find($product->category_id);
							}

							// Get subcategory
							if (isset($product->subcategory) && $product->subcategory) {
								$subcategory = $product->subcategory;
							}
							// Try direct subcategory_id lookup
							elseif (isset($product->subcategory_id) && $product->subcategory_id) {
								$subcategory = \App\Models\ProductSubcategory::find($product->subcategory_id);
								if ($subcategory && $subcategory->category && !$category) {
									$category = $subcategory->category;
								}
							}
						@endphp

						@if($category)
							<a href="{{ route('products') }}?category={{ $category->id }}">{{ $category->name }}</a>
							&gt;
							@if($subcategory)
								<a href="{{ route('sub-products') }}?subcategory={{ $subcategory->id }}">{{ $subcategory->name }}</a>
								&gt;
							@endif
							<span style="text-decoration: underline;">{{ $product->name ?? 'Product' }}</span>
						@else
							{{-- Debug: Show what we have if no category --}}
							<span style="text-decoration: underline;">{{ $product->name ?? 'Product' }}</span>
							{{-- Uncomment below for debugging
							<br><small style="color: red;">Debug: No category found. Product ID: {{ $product->id ?? 'N/A' }}, Category ID: {{ $product->category_id ?? 'N/A' }}, Subcategory ID: {{ $product->subcategory_id ?? 'N/A' }}</small>
							--}}
						@endif
					</small>
					<h3>
						{{ $product->name ?? '' }}
					</h3>
				</p>

				<div class="row col-12">
					<div class="col-12 col-md-6 p-3 pt-0">
						<div class="view-sub-heading mb-3">
							<small>{{ $product->description ?? '' }}</small>
						</div>
											@if(!empty($product->specification))
											<div class="view-bullets px-3 pt-3">
												<ul>
												@foreach(preg_split('/\r?\n/', $product->specification) as $spec)
                                                @if(trim($spec) !== '')
                                                    <li>{{ $spec }}</li>
                                                @endif
												@endforeach
												</ul>
											</div>
											@endif

						@php
							$hasAdditionalFields = $product->ixu || $product->olx || $product->fam_atex || $product->olsw;
						@endphp

						@if($hasAdditionalFields)
						<div class="view-accordion">
							@if($product->ixu)
							<div class="toggle toggle-border">
								<div class="toggle-header">
									<div class="toggle-icon">
										<i class="toggle-closed uil uil-plus"></i>
										<i class="toggle-open uil uil-minus"></i>
									</div>
									<div class="toggle-title">
										IXU
									</div>
								</div>
								<div class="toggle-content">{!! nl2br(e($product->ixu)) !!}</div>
							</div>
							@endif

							@if($product->olx)
							<div class="toggle toggle-border">
								<div class="toggle-header">
									<div class="toggle-icon">
										<i class="toggle-closed uil uil-plus"></i>
										<i class="toggle-open uil uil-minus"></i>
									</div>
									<div class="toggle-title">
										OLX
									</div>
								</div>
								<div class="toggle-content">{!! nl2br(e($product->olx)) !!}</div>
							</div>
							@endif

							@if($product->fam_atex)
							<div class="toggle toggle-border">
								<div class="toggle-header">
									<div class="toggle-icon">
										<i class="toggle-closed uil uil-plus"></i>
										<i class="toggle-open uil uil-minus"></i>
									</div>
									<div class="toggle-title">
										FAM ATEX
									</div>
								</div>
								<div class="toggle-content">{!! nl2br(e($product->fam_atex)) !!}</div>
							</div>
							@endif

							@if($product->olsw)
							<div class="toggle toggle-border">
								<div class="toggle-header">
									<div class="toggle-icon">
										<i class="toggle-closed uil uil-plus"></i>
										<i class="toggle-open uil uil-minus"></i>
									</div>
									<div class="toggle-title">
										OLSW
									</div>
								</div>
								<div class="toggle-content">{!! nl2br(e($product->olsw)) !!}</div>
							</div>
							@endif
						</div>
						@endif
					</div>
					<div class="col-12 col-md-6">
						<div class="card side-panel-nav rounded bg-white shadow p-4">
							@if(!empty($product->image))
								<img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width:100%; height:600px; object-fit:cover;">
							@else
								<img src="{{ asset('storage/products/prd1.jpg') }}" alt="{{ !empty($product->name) ? $product->name : 'Product Image' }}" style="width:100%; height:300px; object-fit:cover;">
							@endif
						</div>
					</div>
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
