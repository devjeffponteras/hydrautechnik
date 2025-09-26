@php
    $parentCategories = \App\Models\Ecommerce\ProductCategory::where('parent_id', 0)->where('status', 'PUBLISHED')->orderBy('menu_order_no', 'asc')->get();
@endphp

<h3>Category</h3>
<div class="side-menu">
	<ul class="mb-0 pb-0">
		@foreach($parentCategories as $parentCategory)
			<li><a href="{{ route('product.front.list', $parentCategory->slug) }}"><div>{{$parentCategory->name}}</div></a>
				@if(count($parentCategory->child_categories))
                	@include('theme.layouts.components.product-categories-item',['subcategories' => $parentCategory->child_categories])
                @endif
			</li>
		@endforeach
	</ul>
</div>