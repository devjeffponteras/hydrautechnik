@props(['selectedCategory' => null, 'mainCategories' => null])

<div class="col-12 col-md-2 p-2">
	<div class="position-sticky" style="top: 20px;">
		<div class="card shadow side-panel-nav d-flex align-items-center mb-2">
			<h5 class="mt-2 mb-0 pb-2">Products Category</h5>
		</div>
		<nav class="card shadow nav-tree side-panel-nav mb-0 p-3 pb-4" style="max-height: calc(100vh - 120px); overflow-y: auto;">
			@php
				// Always load all categories so side navigation consistently shows every category
				$sideCategories = \App\Models\ProductCategory::getAllCategories()->get(['id','name']);
			@endphp

			<ul style="list-style: none; padding-left: 0; margin: 0;">
				{{-- Removed "All Categories" option by request --}}

				@forelse($sideCategories as $cat)
					<li class="d-flex align-items-center mb-2 {{ $selectedCategory && $selectedCategory->id == $cat->id ? 'fw-bold' : '' }}">
						<i class="bi-chevron-right" style="margin-right: 8px; flex-shrink: 0;"></i>
						<a href="{{ route('products.by-category', $cat->id) }}"
						   style="text-transform: none !important; text-align: start; font-weight: {{ $selectedCategory && $selectedCategory->id == $cat->id ? '700' : '500' }}; font-size: 14px; text-decoration: none; color: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;"
						   title="{{ $cat->name }}">
							{{ $cat->name }}
						</a>
					</li>
				@empty
					<li class="d-flex align-items-center text-muted" style="font-size: 13px;">
						No categories available
					</li>
				@endforelse
			</ul>
		</nav>
	</div>
</div>

<style>
.nav-tree a {
	color: inherit !important;
	transition: color 0.2s ease;
}

.nav-tree a:hover {
	color: #f0ad4e !important;
}

.nav-tree .fw-bold a {
	font-weight: 700 !important;
}

.nav-tree li {
	display: flex;
	align-items: center;
	width: 100%;
}

.nav-tree li a {
	flex: 1;
	min-width: 0;
}
</style>
