

@foreach($subcategories as $subcategory)
<ol class="dd-list">
    <li class="dd-item" data-type="category" data-id="{{$subcategory->category_id}}">
        <div class="dd-handle">{{$subcategory->product_category->name}}</div>
        @if(count($subcategory->subcategory))
            @include('admin.ecommerce.brands.menu-items',['subcategories' => $subcategory->subcategory])
        @endif
    </li>
</ol>
@endforeach