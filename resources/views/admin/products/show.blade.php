<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 text-center">
            @if($product->image)
                <img src="{{ asset($product->image) }}" alt="Product Image" style="width:180px; height:180px; object-fit:cover; border-radius:8px; border:1px solid #ddd; margin-bottom: 10px;"
                     onerror="this.src='{{ asset('storage/no-image.png') }}'; this.alt='No Image Available';">
            @else
                <div style="width:180px; height:180px; background-color:#f8f9fa; border:1px solid #ddd; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#6c757d; font-size:16px; margin-bottom: 10px;">
                    No Image
                </div>
            @endif
        </div>
        <div class="col-md-8">
            <h4>{{ $product->name }}</h4>
            <p><strong>Category:</strong> {{ $product->productCategory ? $product->productCategory->name : ($product->subcategory && $product->subcategory->category ? $product->subcategory->category->name : '-') }}</p>
            <p><strong>Subcategory:</strong> {{ $product->subcategory ? $product->subcategory->name : '-' }}</p>
            <p><strong>Description:</strong> {{ $product->description }}</p>
            <p><strong>Specification:</strong></p>
            @if($product->specification)
                <ul class="mb-2">
                @foreach(preg_split('/\r?\n/', $product->specification) as $spec)
                    @if(trim($spec) !== '')
                        <li>{{ $spec }}</li>
                    @endif
                @endforeach
                </ul>
            @endif
            <p><strong>IXU:</strong> {!! nl2br(e($product->ixu ?? '-')) !!}</p>
            <p><strong>OLX:</strong> {!! nl2br(e($product->olx ?? '-')) !!}</p>
            <p><strong>FAM ATEX:</strong> {!! nl2br(e($product->fam_atex ?? '-')) !!}</p>
            <p><strong>OLSW:</strong> {!! nl2br(e($product->olsw ?? '-')) !!}</p>
            <p><strong>Created At:</strong> {{ $product->created_at }}</p>
            <p><strong>Updated At:</strong> {{ $product->updated_at }}</p>
        </div>
    </div>
</div>
