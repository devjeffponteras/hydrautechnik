@foreach($subcategories as $subcategory)
	<option value="{{ $subcategory->id }}">
		@for ($i = 1; $i <= $subcategory->categorylevel; $i++)
	        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    @endfor
		{{ $subcategory->name }}
	</option>
	
	@if(count($subcategory->child_categories))
		@include('admin.ecommerce.product-categories.subcategories',['subcategories' => $subcategory->child_categories])
	@endif
@endforeach