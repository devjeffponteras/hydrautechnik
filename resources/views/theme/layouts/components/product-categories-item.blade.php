

<ul>
	@foreach($subcategories as $subcategory)
		<li><a href="{{ route('product.front.list', $subcategory->slug) }}"><div>{{$subcategory->name}}</div></a>
		@if(count($subcategory->child_categories))
			@include('theme.layouts.components.product-categories-item',['subcategories' => $subcategory->child_categories])
		@endif
		</li>
	@endforeach
</ul>