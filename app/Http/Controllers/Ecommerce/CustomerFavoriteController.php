<?php

namespace App\Http\Controllers\Ecommerce;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Ecommerce\CustomerFavorite;

class CustomerFavoriteController extends Controller
{
    public function add_to_favorites(Request $request, $prd_id){

        $favorite_exists = CustomerFavorite::where('product_id', $prd_id)->where('customer_id', auth()->id())->first();
        
        if($favorite_exists){
            CustomerFavorite::where('product_id', $prd_id)->where('customer_id', auth()->id())->delete();
        }
        else{
            $newData['product_id'] = $prd_id;
            $newData['customer_id'] = auth()->id();

            CustomerFavorite::create($newData);
        }

        return redirect()->back();

    }
}
