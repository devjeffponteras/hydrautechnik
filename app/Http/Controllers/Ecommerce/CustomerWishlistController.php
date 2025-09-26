<?php

namespace App\Http\Controllers\Ecommerce;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ecommerce\CustomerWishlist;

class CustomerWishlistController extends Controller
{
    public function add_to_wishlist(Request $request, $prd_id){

        $wishlist_exists = CustomerWishlist::where('product_id', $prd_id)->where('customer_id', auth()->id())->first();
        
        if($wishlist_exists){
            CustomerWishlist::where('product_id', $prd_id)->where('customer_id', auth()->id())->delete();
        }
        else{
            $newData['product_id'] = $prd_id;
            $newData['customer_id'] = auth()->id();

            CustomerWishlist::create($newData);
        }

        return redirect()->back();

    }
}
