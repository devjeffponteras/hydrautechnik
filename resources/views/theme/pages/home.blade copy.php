@extends('theme.main')

@section('pagecss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endsection

@php
    $contents = $page->contents;

// FEATURED BOOKS
    $featuredProducts = \App\Models\Ecommerce\Product::where('is_featured', 1)->where('status', 'PUBLISHED')->whereRaw('LOWER(book_type) NOT IN (?, ?)', ['ebook', 'e-book'])->skip(0)->take(10)->orderByDesc('updated_at')->get();
    if($featuredProducts->count()){

        $featuredProductsHTML = '';

        foreach($featuredProducts as $key => $product) {
            $imageUrl = asset('storage/products/'.$product->photoPrimary);

            if(auth()->user()){

                $featuredProductsHTML .= '
                <div class="product">
                    <div class="grid-inner">
                        <div class="product-image h-translate-y all-ts">
                            <a href="'.route('product.details',$product->slug).'"><img src="'.$imageUrl.'" alt="'. $product->name .'"></a>
                            <div class="bg-overlay">
                                <div class="bg-overlay-content align-items-end justify-content-start flex-column">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" onclick="add_to_cart('. htmlspecialchars(json_encode($product->id), ENT_QUOTES, 'UTF-8') . ',' . htmlspecialchars(json_encode($product->discount_price > 0 ? $product->discount_price : $product->price), ENT_QUOTES, 'UTF-8') .',' . htmlspecialchars(json_encode($product->inventory), ENT_QUOTES, 'UTF-8') .',' . htmlspecialchars(json_encode($product->name), ENT_QUOTES, 'UTF-8') .',' . htmlspecialchars(json_encode($product->photoPrimary), ENT_QUOTES, 'UTF-8') .');" title="Add to Bag" data-hover-animate="fadeInRightSmall" href="javascript:void(0)" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-shopping-bag"></i></a>
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" title="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'Remove from Favorites' : 'Add to favorites') . '" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="'. route('add-to-favorites', [$product->id])  .'" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'icon-heart3 text-danger' : 'icon-heart3') . '"></i></a>
                                    <a hidden data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Wishlist" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="#" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-star"></i></a>
                                </div>
                                <div class="bg-overlay-bg bg-transparent"></div>
                            </div>
                        </div>
                        <div class="product-desc py-0">
                            <div class="product-title"><h3><a href="'.route('product.details',$product->slug).'">'. $product->name .'</a></h3></div>'.
                            ($product->discount_price > 0 || $product->discountedprice != $product->price ? '<div class="product-price"> <del class="me-1">'. number_format($product->price,2) .'</del> <ins class="text-light">'. number_format($product->discountedprice != $product->price ? $product->discountedprice : $product->discount_price, 2) .'</ins> </div>' : '<div class="product-price"><ins class="text-light">'. number_format($product->price,2) .'</ins></div>') . '
                            <div class="product-rating text-warning">';
            }
            else{
                
                $featuredProductsHTML .= '
                <div class="product">
                    <div class="grid-inner">
                        <div class="product-image h-translate-y all-ts">
                            <a href="'.route('product.details',$product->slug).'"><img src="'.$imageUrl.'" alt="'. $product->name .'"></a>
                            <div class="bg-overlay">
                                <div class="bg-overlay-content align-items-end justify-content-start flex-column">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Bag" data-hover-animate="fadeInRightSmall" href="#modal-register" data-lightbox="inline" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-shopping-bag"></i></a>
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" title="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'Remove from Favorites' : 'Add to favorites') . '" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="#modal-register" data-lightbox="inline" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'icon-heart3 text-danger' : 'icon-heart3') . '"></i></a>
                                    <a hidden data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Wishlist" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="#" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-star"></i></a>
                                </div>
                                <div class="bg-overlay-bg bg-transparent"></div>
                            </div>
                        </div>
                        <div class="product-desc py-0">
                            <div class="product-title"><h3><a href="'.route('product.details',$product->slug).'">'. $product->name .'</a></h3></div>'.
                            ($product->discount_price > 0 || $product->discountedprice != $product->price ? '<div class="product-price"> <del class="me-1">'. number_format($product->price,2) .'</del> <ins class="text-light">'. number_format($product->discountedprice != $product->price ? $product->discountedprice : $product->discount_price,2) .'</ins> </div>' : '<div class="product-price"><ins class="text-light">'. number_format($product->price,2) .'</ins></div>') . '
                            <div class="product-rating text-warning">';
            }

                            for($star = 1; $star <= 5; $star++):
                                $iconClass = ($star <= \App\Models\Ecommerce\ProductReview::getProductRating($product->id)) ? "3" : "-empty";
                                $featuredProductsHTML .= '<i class="icon-star'.$iconClass.'"></i>';
                            endfor;

            $featuredProductsHTML .= '
                        </div>
                    </div>
                </div>
            </div>';
        }

    } else {
        $featuredProductsHTML = '';
    }

    

// BEST SELLERS
    // $bestSellers = \App\Models\Ecommerce\ProductReview::select('products.id', 'products.category_id', 'products.book_type', 'products.type', 'products.sku', 'products.name', 'products.subtitle', 'products.slug', 'products.short_description', 'products.description', 'products.price', 'products.reorder_point', 'products.size', 'products.weight', 'products.texture', 'products.status', 'products.uom', 'products.is_featured', 'products.publication_date', 'products.created_by', 'products.meta_title', 'products.meta_keyword', 'products.meta_description', \DB::raw('SUM(product_reviews.rating) as total_rating'))
    //     ->where('products.status', 'PUBLISHED')
    //     ->rightJoin('products', 'products.id', '=', 'product_reviews.product_id')
    //     ->groupBy('products.id', 'products.category_id', 'products.book_type', 'products.type', 'products.sku', 'products.name', 'products.subtitle', 'products.slug', 'products.short_description', 'products.description', 'products.price', 'products.reorder_point', 'products.size', 'products.weight', 'products.texture', 'products.status', 'products.uom', 'products.is_featured', 'products.publication_date', 'products.created_by', 'products.meta_title', 'products.meta_keyword', 'products.meta_description')
    //     ->orderByRaw('SUM(product_reviews.rating) DESC')
    //     ->get();

    $bestSellers = \App\Models\Ecommerce\Product::where('is_best_seller', 1)->where('status', 'PUBLISHED')->whereRaw('LOWER(book_type) NOT IN (?, ?)', ['ebook', 'e-book'])->take(10)->get();
    if($bestSellers->count()){

        $bestSellersHTML = '';

        foreach($bestSellers as $key => $product) {
            $imageUrl = asset('storage/products/'.$product->photoPrimary);

            if(auth()->user()){

                $bestSellersHTML .= '
                <div class="product">
                    <div class="grid-inner">
                        <div class="product-image h-translate-y all-ts">
                            <a href="'.route('product.details',$product->slug).'"><img src="'.$imageUrl.'" alt="'. $product->name .'"></a>
                            <div class="bg-overlay">
                                <div class="bg-overlay-content align-items-end justify-content-start flex-column">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" onclick="add_to_cart('. htmlspecialchars(json_encode($product->id), ENT_QUOTES, 'UTF-8') . ',' . htmlspecialchars(json_encode($product->discount_price > 0 ? $product->discount_price : $product->price), ENT_QUOTES, 'UTF-8') .',' . htmlspecialchars(json_encode($product->inventory), ENT_QUOTES, 'UTF-8') .',' . htmlspecialchars(json_encode($product->name), ENT_QUOTES, 'UTF-8') .',' . htmlspecialchars(json_encode($product->photoPrimary), ENT_QUOTES, 'UTF-8') .');" title="Add to Bag" data-hover-animate="fadeInRightSmall" href="javascript:void(0)" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-shopping-bag"></i></a>
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" title="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'Remove from Favorites' : 'Add to favorites') . '" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="'. route('add-to-favorites', [$product->id])  .'" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'icon-heart3 text-danger' : 'icon-heart3') . '"></i></a>
                                    <a hidden data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Wishlist" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="#" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-star"></i></a>
                                </div>
                                <div class="bg-overlay-bg bg-transparent"></div>
                            </div>
                        </div>
                        <div class="product-desc py-0">
                            <div class="product-title"><h3><a href="'.route('product.details',$product->slug).'">'. $product->name .'</a></h3></div>'.
                            ($product->discount_price > 0 || $product->discountedprice != $product->price ? '<div class="product-price"> <del class="text-danger">'. number_format($product->price,2) .'</del> <ins>'. number_format($product->discountedprice != $product->price ? $product->discountedprice : $product->discount_price,2) .'</ins> </div>' : '<div class="product-price"><ins>'. number_format($product->price,2) .'</ins></div>') . '
                            <div class="product-rating text-warning">';
                }
                else{

                $bestSellersHTML .= '
                <div class="product">
                    <div class="grid-inner">
                        <div class="product-image h-translate-y all-ts">
                            <a href="'.route('product.details',$product->slug).'"><img src="'.$imageUrl.'" alt="'. $product->name .'"></a>
                            <div class="bg-overlay">
                                <div class="bg-overlay-content align-items-end justify-content-start flex-column">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Bag" data-hover-animate="fadeInRightSmall" href="#modal-register" data-lightbox="inline" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-shopping-bag"></i></a>
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" title="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'Remove from Favorites' : 'Add to favorites') . '" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="#modal-register" data-lightbox="inline" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'icon-heart3 text-danger' : 'icon-heart3') . '"></i></a>
                                    <a hidden data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Wishlist" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="#" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-star"></i></a>
                                </div>
                                <div class="bg-overlay-bg bg-transparent"></div>
                            </div>
                        </div>
                        <div class="product-desc py-0">
                            <div class="product-title"><h3><a href="'.route('product.details',$product->slug).'">'. $product->name .'</a></h3></div>'.
                            ($product->discount_price > 0 || $product->discountedprice != $product->price ? '<div class="product-price"> <del class="text-danger">'. number_format($product->price,2) .'</del> <ins>'. number_format($product->discountedprice != $product->price ? $product->discountedprice : $product->discount_price,2) .'</ins> </div>' : '<div class="product-price"><ins>'. number_format($product->price,2) .'</ins></div>') . '
                            <div class="product-rating text-warning">';

            }

                            for($star = 1; $star <= 5; $star++):
                                $iconClass = ($star <= \App\Models\Ecommerce\ProductReview::getProductRating($product->id)) ? "3" : "-empty";
                                $bestSellersHTML .= '<i class="icon-star'.$iconClass.'"></i>';
                            endfor;

            $bestSellersHTML .= '
                        </div>
                    </div>
                </div>
            </div>';
        }

    } else {
        $bestSellersHTML = '';
    }



// NEW RELEASES
    $newProducts = \App\Models\Ecommerce\Product::whereDate('updated_at', '>=', Carbon\Carbon::now()->subDays(30))->where('status', 'PUBLISHED')->orderBy('updated_at', 'desc')->whereRaw('LOWER(book_type) NOT IN (?, ?)', ['ebook', 'e-book'])->take(10)->get();
    if($newProducts->count()){

        $newProductsHTML = '';

        // echo '<div class="col-12">';
            foreach($newProducts as $key => $product) {
                $imageUrl = asset('storage/products/'.$product->photoPrimary);

                
                if(auth()->user()){
                    $newProductsHTML .= '
                    <div class="product">
                        <div class="grid-inner">
                            <div class="product-image h-translate-y all-ts">
                                <a href="'.route('product.details',$product->slug).'"><img src="'.$imageUrl.'" alt="'. $product->name .'"></a>
                                <div class="bg-overlay">
                                    <div class="bg-overlay-content align-items-end justify-content-start flex-column">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" onclick="add_to_cart('. htmlspecialchars(json_encode($product->id), ENT_QUOTES, 'UTF-8') . ',' . htmlspecialchars(json_encode($product->discount_price > 0 ? $product->discount_price : $product->price), ENT_QUOTES, 'UTF-8') .',' . htmlspecialchars(json_encode($product->inventory), ENT_QUOTES, 'UTF-8') .',' . htmlspecialchars(json_encode($product->name), ENT_QUOTES, 'UTF-8') .',' . htmlspecialchars(json_encode($product->photoPrimary), ENT_QUOTES, 'UTF-8') .');" title="Add to Bag" data-hover-animate="fadeInRightSmall" href="javascript:void(0)" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-shopping-bag"></i></a>
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" title="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'Remove from Favorites' : 'Add to favorites') . '" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="'. route('add-to-favorites', [$product->id])  .'" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'icon-heart3 text-danger' : 'icon-heart3') . '"></i></a>
                                    <a hidden data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Wishlist" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="#" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-star"></i></a>
                                    </div>
                                    <div class="bg-overlay-bg bg-transparent"></div>
                                </div>
                            </div>
                        <div class="product-desc py-0">
                            <div class="product-title"><h3><a href="'.route('product.details',$product->slug).'">'. $product->name .'</a></h3></div>'.
                            ($product->discount_price > 0 || $product->discountedprice != $product->price ? '<div class="product-price"> <del class="text-danger">'. number_format($product->price,2) .'</del> <ins>'. number_format($product->discountedprice != $product->price ? $product->discountedprice : $product->discount_price,2) .'</ins> </div>' : '<div class="product-price"><ins>'. number_format($product->price,2) .'</ins></div>') . '
                            <div class="product-rating text-warning">';
                }
                else{
                    $newProductsHTML .= '
                    <div class="product">
                        <div class="grid-inner">
                            <div class="product-image h-translate-y all-ts">
                                <a href="'.route('product.details',$product->slug).'"><img src="'.$imageUrl.'" alt="'. $product->name .'"></a>
                                <div class="bg-overlay">
                                    <div class="bg-overlay-content align-items-end justify-content-start flex-column">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Bag" data-hover-animate="fadeInRightSmall" href="#modal-register" data-lightbox="inline" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-shopping-bag"></i></a>
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" title="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'Remove from Favorites' : 'Add to favorites') . '" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="#modal-register" data-lightbox="inline" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="'. (\App\Models\Ecommerce\CustomerFavorite::isFavorite($product->id) ? 'icon-heart3 text-danger' : 'icon-heart3') . '"></i></a>
                                    <a hidden data-bs-toggle="tooltip" data-bs-placement="left" title="Add to Wishlist" data-hover-animate="fadeInRightSmall" data-hover-delay="100" href="#" class="btn btn-light h-bg-color h-text-light border-0 mb-2"><i class="icon-star"></i></a>
                                    </div>
                                    <div class="bg-overlay-bg bg-transparent"></div>
                                </div>
                            </div>
                        <div class="product-desc py-0">
                            <div class="product-title"><h3><a href="'.route('product.details',$product->slug).'">'. $product->name .'</a></h3></div>'.
                            ($product->discount_price > 0 || $product->discountedprice != $product->price ? '<div class="product-price"> <del class="text-danger">'. number_format($product->price,2) .'</del> <ins>'. number_format($product->discountedprice != $product->price ? $product->discountedprice : $product->discount_price,2) .'</ins> </div>' : '<div class="product-price"><ins>'. number_format($product->price,2) .'</ins></div>') . '
                            <div class="product-rating text-warning">';
                }


                            for($star = 1; $star <= 5; $star++):
                                $iconClass = ($star <= \App\Models\Ecommerce\ProductReview::getProductRating($product->id)) ? "3" : "-empty";
                                $newProductsHTML .= '<i class="icon-star'.$iconClass.'"></i>';
                            endfor;
                $newProductsHTML .= '
                        </div>
                    </div>
                    </div>
                </div>';
            }
        // echo '</div>';

    } else {
        $newProductsHTML = '';
    }



// LATEST NEWS
    $featuredArticles = Article::where('is_featured', 1)->where('status', 'Published')->skip(0)->take(5)->get();
    if($featuredArticles->count()) {

        $featuredArticlesHTML = '';

        $prefooter = asset('theme/images/pre-footer.jpg');

        foreach ($featuredArticles as $index => $article) {
            $imageUrl = (empty($article->thumbnail_url)) ? asset('theme/images/misc/no-image.jpg') : $article->thumbnail_url;

            
            $featuredArticlesHTML .= '
                <div class="oc-item">
                    <div class="ipost clearfix">
                        <div class="entry-image">
                            <a href="'. $article->get_url() .'" data-lightbox="image"><img class="image_fade" src="'. $imageUrl .'" alt="Standard Post with Image"></a>
                        </div>
                        <div class="entry-title" style="max-height: 70px; overflow: hidden;">
                            <h3 style="display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; overflow: hidden; text-overflow: ellipsis;">
                                <a href="'. $article->get_url() .'" style="text-decoration: none;">'. $article->name .'</a>
                            </h3>
                        </div>
                        <ul class="entry-meta clearfix">
                            <li><i class="icon-calendar3"></i> '. $article->date_posted() .'</li>
                        </ul>
                        <div class="entry-content" style="max-height: 140px; overflow: hidden;">
                            <p style="display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; overflow: hidden; text-overflow: ellipsis;">'. $article->teaser .'</p>
                        </div>
                    </div>
                </div>';

            if (Article::has_featured_limit() && $index >= env('FEATURED_NEWS_LIMIT')) {
                break;
            }
        }

    } else {
        $featuredArticlesHTML = '';
    } 
    
    $keywords   = ['{Featured Articles}', '{Best Sellers}', '{New Releases}', '{Featured Products}'];
    $variables  = [$featuredArticlesHTML, $bestSellersHTML, $newProductsHTML, $featuredProductsHTML];
    $contents = str_replace($keywords,$variables,$contents);

@endphp

@section('content')
    {!! $contents !!}
@endsection


@section('pagejs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script>

    function buynow(){
        var qty = $('#quantity').val();
        $('#buy_now_qty').val(qty);

        $('#buy-now-form').submit();
    }

    
    function add_to_cart(product, price, remaining_stock, name, image){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var qty   = 1;
        // var price = parseFloat($('#product_price').val());
        // var remaining_stock = parseFloat($('#remaining_stock').val());

        if(qty <= remaining_stock){

            $.ajax({
                data: {
                    "product_id": product, 
                    "qty": qty,
                    "price": price,
                    "_token": "{{ csrf_token() }}",
                },
                type: "post",
                url: "{{route('product.add-to-cart')}}",
                success: function(returnData) {
                    $("#loading-overlay").hide();
                    if (returnData['success']) {

                        $('.top-cart-number').html(returnData['totalItems']);


                        var cartotal = parseFloat($('#input-top-cart-total').val());
                        var productotal = price*qty;
                        var newtotal = cartotal+productotal;


                        $('#top-cart-total').html('₱'+newtotal.toFixed(2));
				        $('#input-top-cart-total').val(newtotal);

                        // $('#top-cart-items').append(
                        //     '<div class="top-cart-item">'+
                        //         '<div class="top-cart-item-image border-0">'+
                        //             '<a href="#"><img src="{{ asset('storage/products/'.$product->photoPrimary) }}" alt="Cart Image 1" /></a>'+
                        //         '</div>'+
                        //         '<div class="top-cart-item-desc">'+
                        //             '<div class="top-cart-item-desc-title">'+
                        //                 '<a href="#" class="fw-medium">{{$product->name}}</a>'+
                        //                 '<span class="top-cart-item-price d-block">'+price.toFixed(2)+'</span>'+
                        //                 '<div class="d-flex mt-2">'+
                        //                     '<a href="#" class="fw-normal text-black-50 text-smaller"><u>Edit</u></a>'+
                        //                     '<a href="#" class="fw-normal text-black-50 text-smaller ms-3" onclick="top_remove_product('+returnData['cartId']+');"><u>Remove</u></a>'+
                        //                 '</div>'+
                        //             '</div>'+
                        //             '<div class="top-cart-item-quantity">x '+qty+'</div>'+
                        //         '</div>'+
                        //    '</div>'
                        // );
                        var cartItem = $('#top-cart-items').find('[data-product-id="' + product + '"]');
                        if (cartItem.length) {
                            // If the item already exists in the cart, update its quantity and price
                            var oldQty = parseFloat(cartItem.find('.top-cart-item-quantity').text().trim().replace('x ', ''));
                            var newQty = oldQty + qty;
                            var oldPrice = parseFloat(cartItem.find('.top-cart-item-price').text().trim().replace('₱', ''));
                            var productTotal = price * qty;
                            var newTotal = oldPrice + productTotal;

                            cartItem.find('.top-cart-item-quantity').text('x ' + newQty);
                            // cartItem.find('.top-cart-item-price').text('₱' + newTotal.toFixed(2));
                        } else {

                            $('#top-cart-items').append(
                                '<div class="top-cart-item" data-product-id="' + product + '">' +
                                '<div class="top-cart-item-image border-0">' +
                                '<a href="#"><img src="{{ asset('storage/products/') }}/' + image + '" alt="Cart Image 1" /></a>' +
                                '</div>' +
                                '<div class="top-cart-item-desc">' +
                                '<div class="top-cart-item-desc-title">' +
                                '<a href="#" class="fw-medium">' + name + '</a>' +
                                '<span class="top-cart-item-price d-block">₱' + price + '</span>' +
                                '<div class="d-flex mt-2">' +
                                '<a href="javascript:void()" onclick="location.reload();" class="fw-normal text-black-50 text-smaller"><u>Reload to Edit</u></a>' +
                                '<a href="#" class="fw-normal text-black-50 text-smaller ms-3" onclick="top_remove_product(' + returnData['cartId'] + ');"><u>Remove</u></a>' +
                                '</div>' +
                                '</div>' +
                                '<div class="top-cart-item-quantity">x ' + qty + '</div>' +
                                '</div>' +
                                '</div>'
                            );

                            // $('#top-cart-items').append(
                            //     '<div class="top-cart-item" data-product-id="' + product + '">' +
                            //     '<div class="top-cart-item-image border-0">' +
                            //     '<a href="#"><img src="{{ asset('storage/products/'.$product->photoPrimary) }}" alt="Cart Image 1" /></a>' +
                            //     '</div>' +
                            //     '<div class="top-cart-item-desc">' +
                            //     '<div class="top-cart-item-desc-title">' +
                            //     '<a href="#" class="fw-medium">{{$product->name}}</a>' +
                            //     '<span class="top-cart-item-price d-block">₱' + price + '</span>' +
                            //     // '<span class="top-cart-item-price d-block">₱' + (price * qty).toFixed(2) + '</span>' +
                            //     '<div class="d-flex mt-2">' +
                            //     '<a href="javascript:void()" onclick="location.reload();" class="fw-normal text-black-50 text-smaller"><u>Reload to Edit</u></a>' +
                            //     '<a href="#" class="fw-normal text-black-50 text-smaller ms-3" onclick="top_remove_product(' + returnData['cartId'] + ');"><u>Remove</u></a>' +
                            //     '</div>' +
                            //     '</div>' +
                            //     '<div class="top-cart-item-quantity">x ' + qty + '</div>' +
                            //     '</div>' +
                            //     '</div>'
                            // );
                        }

                        $.notify("Product Added to your cart",{ 
                            position:"bottom right", 
                            className: "success" 
                        });

                    } else {
                        swal({
                            toast: true,
                            position: 'center',
                            title: "Warning!",
                            text: "We have insufficient inventory for this item.",
                            type: "warning",
                            showCancelButton: true,
                            timerProgressBar: true, 
                            closeOnCancel: false

                        });
                    }
                }
            });

            $('#quantity').val(1);
            $('#remaining_stock').val(remaining_stock - qty);
        }
        else{
            swal({
                toast: true,
                position: 'center',
                title: "Warning!",
                text: "We have insufficient inventory for this item.",
                type: "warning",
                showCancelButton: true,
                timerProgressBar: true, 
                closeOnCancel: false

            });
        }
    }

    // function add_to_cart(product, price){

    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });

    //     var qty   = 1
    //     // var qty   = parseFloat($('#quantity').val());
    //     // var price = parseFloat($('#product_price').val());

    //     $.ajax({
    //         data: {
    //             "product_id": product, 
    //             "qty": qty,
    //             "_token": "{{ csrf_token() }}",
    //         },
    //         type: "post",
    //         url: "{{route('product.add-to-cart')}}",
    //         success: function(returnData) {
    //             $("#loading-overlay").hide();
    //             if (returnData['success']) {

    //                 $('.top-cart-number').html(returnData['totalItems']);


    //                 var cartotal = parseFloat($('#input-top-cart-total').val());
    //                 var productotal = price*qty;
    //                 var newtotal = cartotal+productotal;

    //                 $('#top-cart-total').html('₱'+newtotal.toFixed(2));
    //                 var cartItem = $('#top-cart-items').find('[data-product-id="' + product + '"]');
    //                 // if (cartItem.length) {
    //                 //     // If the item already exists in the cart, update its quantity and price
    //                 //     var oldQty = parseFloat(cartItem.find('.top-cart-item-quantity').text().trim().replace('x ', ''));
    //                 //     var newQty = oldQty + qty;
    //                 //     var oldPrice = parseFloat(cartItem.find('.top-cart-item-price').text().trim().replace('₱', ''));
    //                 //     var productTotal = price * qty;
    //                 //     var newTotal = oldPrice + productTotal;

    //                 //     cartItem.find('.top-cart-item-quantity').text('x ' + newQty);
    //                 //     // cartItem.find('.top-cart-item-price').text('₱' + newTotal.toFixed(2));
    //                 // } else {

    //                 //     $('#top-cart-items').append(
    //                 //         '<div class="top-cart-item" data-product-id="' + product + '">' +
    //                 //         '<div class="top-cart-item-image border-0">' +
    //                 //         '<a href="#"><img src="{{ asset('storage/products/'.$product->photoPrimary) }}" alt="Cart Image 1" /></a>' +
    //                 //         '</div>' +
    //                 //         '<div class="top-cart-item-desc">' +
    //                 //         '<div class="top-cart-item-desc-title">' +
    //                 //         '<a href="#" class="fw-medium">{{$product->name}}</a>' +
    //                 //         '<span class="top-cart-item-price d-block">₱' + price + '</span>' +
    //                 //         // '<span class="top-cart-item-price d-block">₱' + (price * qty).toFixed(2) + '</span>' +
    //                 //         '<div class="d-flex mt-2">' +
    //                 //         '<a href="javascript:void()" onclick="location.reload();" class="fw-normal text-black-50 text-smaller"><u>Reload to Edit</u></a>' +
    //                 //         '<a href="#" class="fw-normal text-black-50 text-smaller ms-3" onclick="top_remove_product(' + returnData['cartId'] + ');"><u>Remove</u></a>' +
    //                 //         '</div>' +
    //                 //         '</div>' +
    //                 //         '<div class="top-cart-item-quantity">x ' + qty + '</div>' +
    //                 //         '</div>' +
    //                 //         '</div>'
    //                 //     );

    //                 // }
                    
    //                 $('#top-cart-items').append(
    //                     '<div class="top-cart-item" data-product-id="' + product + '">' +
    //                         '<a href="javascript:void()" onclick="location.reload();" class="fw-normal text-black-50 text-smaller"><u>New item added. Reload to Edit</u></a>' +
    //                     '</div>'
    //                 );

    //                 $.notify("Product Added to your cart",{ 
    //                     position:"bottom right", 
    //                     className: "success" 
    //                 });

    //             } else {
    //                 swal({
    //                     toast: true,
    //                     position: 'center',
    //                     title: "Warning!",
    //                     text: "We have insufficient inventory for this item.",
    //                     type: "warning",
    //                     showCancelButton: true,
    //                     timerProgressBar: true, 
    //                     closeOnCancel: false

    //                 });
    //             }
    //         }
    //     });

    //     $('#quantity').val(1);
    // }
    
</script>

<script>
    
    // for edit quantity
	function FormatAmount(number, numberOfDigits) {
		var amount = parseFloat(number).toFixed(numberOfDigits);
		var num_parts = amount.toString().split(".");
		num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

		return num_parts.join(".");
	}

	function addCommas(nStr){
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

    function plus_qty(id){
		var qty = parseFloat($('#quantity'+id).val())+1;

		if(parseInt($('#maxorder'+id).val()) < 1){
			swal({
				title: '',
				text: 'Sorry. Currently, there is no sufficient stocks for the item you wish to order.',
				icon: 'warning'
			});

			$('#quantity'+id).val($('#prevqty'+id).val()-1);
			return false;
		}

		order_qty(id,qty);
	}

	function minus_qty(id){
		var qty = parseFloat($('#quantity'+id).val())-1;
		order_qty(id,qty);
	}

	function order_qty(id,qty){

		if(qty == 0){
			$('#quantity'+id).val(1).val();
			return false;
		}
		
		var price = $('#cartItemPrice'+id).val();
		total_price  = parseFloat(price)*parseFloat(qty);

		$('#order'+id+'_total_price').html('₱'+FormatAmount(total_price,2));
		$('#input_order'+id+'_product_total_price').val(total_price);

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			data: { 
				"quantity": qty, 
				"orderID": id, 
				"_token": "{{ csrf_token() }}",
			},
			type: "post",
			url: "{{route('cart.update')}}",
			
			success: function(returnData) {

				$('#maxorder'+id).val(returnData.maxOrder);
				$('.top-cart-number').html(returnData['totalItems']);
				$('#prevqty'+id).val(qty);
				// var promo_discount = parseFloat(returnData.total_promo_discount);

				// let subtotal = 0;
				// $(".input_product_total_price").each(function() {
				//     if(!isNaN(this.value) && this.value.length!=0) {
				//         subtotal += parseFloat(this.value);
				//     }
				// });

				// $('#subtotal').val(subtotal);


				// for the sidebar cart total
				// var cartotal = parseFloat($('#input-top-cart-total').val());
				// var productotal = price*qty;
				// var newtotal = cartotal+total_price;
				
				// alert(cartotal);

				// $('#input-top-cart-total').val(newtotal);
				// $('#top-cart-total').html('₱'+newtotal.toFixed(2));
				// 
				
				// resetCoupons();
				cart_total();
			}
		});
	}

	function cart_total(){
		var couponTotalDiscount = parseFloat($('#coupon_total_discount').val());
		var promoTotalDiscount = 0;
		var subtotal = 0;

		$(".input_product_total_price").each(function() {
			if(!isNaN(this.value) && this.value.length!=0) {
				subtotal += parseFloat(this.value);
			}
		});

		if(couponTotalDiscount == 0){
			$('#couponDiscountDiv').css('display','none');
		}

		// var totalDeduction = promoTotalDiscount + couponTotalDiscount;
		// var grandtotal = subtotal - totalDeduction;
		
		// $('#subtotal').html('₱'+FormatAmount(subtotal,2));

		$('#top-cart-total').val(subtotal);
		$('#top-cart-total').html('₱'+subtotal.toFixed(2));
	}
</script>
@endsection

