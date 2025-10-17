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
							$category = $product->category ?? null;
							$subcategory = $product->subcategory ?? null;
						@endphp
						@if($category)
							<a href="{{ route('products') }}">{{ $category->name }}</a>
						@else
							<a href="{{ route('products') }}">Product Categories</a>
						@endif
						&gt;
						@if($subcategory)
							<a href="{{ route('sub-products') }}">{{ $subcategory->name }}</a>
						@else
							<a href="{{ route('sub-products') }}">Sub Categories</a>
						@endif
						&gt;
						<span style="text-decoration: underline;">{{ $product->name ?? 'Product' }}</span>
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
						<div class="view-accordion">
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
								<div class="toggle-content">{!! nl2br(e($product->ixu ?? 'No information available')) !!}</div>
							</div>
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
								<div class="toggle-content">{!! nl2br(e($product->olx ?? 'No information available')) !!}</div>
							</div>
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
								<div class="toggle-content">{!! nl2br(e($product->fam_atex ?? 'No information available')) !!}</div>
							</div>
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
								<div class="toggle-content">{!! nl2br(e($product->olsw ?? 'No information available')) !!}</div>
							</div>
						</div>
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
