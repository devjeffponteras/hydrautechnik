@foreach($subcategories as $subcategory)
	<option value="{{ $subcategory->id }}" @if($product->category_id == $subcategory->id) selected @endif>
		@for ($i = 1; $i <= $subcategory->categorylevel; $i++)
	        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    @endfor
		{{ $subcategory->name }}
	</option>
	
	@if(count($subcategory->child_categories))
		@include('admin.ecommerce.product-categories.edit-subcategories',['subcategories' => $subcategory->child_categories])
	@endif
@endforeach