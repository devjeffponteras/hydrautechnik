<?php

namespace App\Http\Controllers\Ecommerce;


use App\Mail\SalesCompleted;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Helpers\{Setting, PaynamicsHelper, XDEHelper, LBCHelper};

// Models
use App\Models\Ecommerce\{
    Cart, Product, Deliverablecities, SalesPayment, SalesHeader, SalesDetail, CouponCartDiscount, CustomerCoupon, CouponCart, CouponSale, Coupon
};

use App\Models\{
    Page, User, PaynamicsLog
};


use Carbon\Carbon;
use Redirect;
use DateTime;
use Session;
use Auth;
use DB;


class CartController extends Controller
{
    public function add_to_cart(Request $request)
    {       
        $product = Product::whereId((int) $request->product_id)->first();

        $promo = DB::table('promos')
            ->join('promo_products','promos.id','=','promo_products.promo_id')
            ->where('promos.status','ACTIVE')
            ->where('promos.is_expire',0)
            ->where('promo_products.product_id',$request->product_id);

        $discountedAmount = 0;
        if($promo->count() > 0){
            $discount = $promo->max('promos.discount');
            $percentage = ($discount/100);
            $discountedAmount = ($request->price * $percentage);
            $price = number_format(($request->price - $discountedAmount), 2, '.', '');
        } else {
            $price = number_format($request->price, 2, '.', '');
        }

        if (auth()->check()) {
            $cart = Cart::where('product_id', $request->product_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!empty($cart)) {
                $newQty = $cart->qty + $request->qty;
                
                $save = $cart->update([
                    'qty' => $newQty,
                    'price' => $price
                ]);
                $cartId = $cart->id; // Define $cartId here
            } else {
                $cart = Cart::create([
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'qty' => $request->qty,
                    'price' => $price,
                    'discount_amount' => $discountedAmount
                ]);
                $cartId = $cart->id; // Define $cartId here
            }
        } else {
            $cart = session('cart', []);
            $not_exist = true;

            foreach ($cart as $key => $order) {
                if ($order->product_id == $request->product_id) {
                    $cart[$key]->qty += $request->qty;
                    $cart[$key]->price = $price;
                    $not_exist = false;
                    $cartId = $order->id; // Define $cartId here
                    break;
                }
            }

            if ($not_exist) {
                $order = new \stdClass();
                $order->product_id = $request->product_id;
                $order->qty = $request->qty;
                $order->price = $price;
                array_push($cart, $order);
                $cartId = $order->id; // Define $cartId here
            }

            session(['cart' => $cart]);
        }

        $inventory_remark = true;

        //FOR AUTO APPLY COUPONS CONTINUE HERE

        if ($inventory_remark) {
            return response()->json([
                'success' => true,
                'cartId' => $cartId,
                'totalItems' => Setting::EcommerceCartTotalItems()                
            ]);
        } else {
            return response()->json([
                'success' => false,
                'totalItems' => Setting::EcommerceCartTotalItems()                
            ]);
        }
    }


    public function ebook_add_to_cart(Request $request)
    {       
        $product = Product::whereId((int) $request->product_id)->first();

        $promo = DB::table('promos')
            ->join('promo_products','promos.id','=','promo_products.promo_id')
            ->where('promos.status','ACTIVE')
            ->where('promos.is_expire',0)
            ->where('promo_products.product_id',$request->product_id);

        $discountedAmount = 0;
        if($promo->count() > 0){
            $discount = $promo->max('promos.discount');
            $percentage = ($discount/100);
            $discountedAmount = ($request->price * $percentage);
            $price = number_format(($request->price - $discountedAmount), 2, '.', '');
        } else {
            $price = number_format($request->price, 2, '.', '');
        }

        if (auth()->check()) {
            $cart = Cart::where('product_id', $request->product_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!empty($cart)) {
                $newQty = $cart->qty + $request->qty;
                
                $save = $cart->update([
                    'qty' => $newQty,
                    'price' => $price
                ]);
                $cartId = $cart->id; // Define $cartId here
            } else {
                $cart = Cart::create([
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'qty' => $request->qty,
                    'price' => $price,
                    'discount_amount' => $discountedAmount
                ]);
                $cartId = $cart->id; // Define $cartId here
            }
        } else {
            $cart = session('cart', []);
            $not_exist = true;

            foreach ($cart as $key => $order) {
                if ($order->product_id == $request->product_id) {
                    $cart[$key]->qty += $request->qty;
                    $cart[$key]->ebook_price = $price;
                    $not_exist = false;
                    $cartId = $order->id; // Define $cartId here
                    break;
                }
            }

            if ($not_exist) {
                $order = new \stdClass();
                $order->product_id = $request->product_id;
                $order->qty = $request->qty;
                $order->ebook_price = $price;
                array_push($cart, $order);
                $cartId = $order->id; // Define $cartId here
            }

            session(['cart' => $cart]);
        }

        $inventory_remark = true;

        if ($inventory_remark) {
            return response()->json([
                'success' => true,
                'cartId' => $cartId,
                'totalItems' => Setting::EcommerceCartTotalItems()                
            ]);
        } else {
            return response()->json([
                'success' => false,
                'totalItems' => Setting::EcommerceCartTotalItems()                
            ]);
        }
    }


    // public function add_to_cart(Request $request)
    // {       
    //     $product = Product::whereId((int) $request->product_id)->first();

    //     $promo = DB::table('promos')->join('promo_products','promos.id','=','promo_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('promo_products.product_id',$request->product_id);

    //     $discountedAmount = 0;
    //     if($promo->count() > 0){
    //         $discount = $promo->max('promos.discount');

    //         $percentage = ($discount/100);
    //         $discountedAmount = ($product->price * $percentage);

    //         $price = number_format(($product->price - $discountedAmount),2,'.','');
    //     } else {
    //         $price = number_format($product->price,2,'.','');
    //     }

    //     if (auth()->check()) {
            
    //         $cart = Cart::where('product_id', $request->product_id)
    //             ->where('user_id', Auth::id())
    //             ->first();

    //         if (!empty($cart)) {
    //             // $newQty = $request->qty;
    //             $newQty = $cart->qty + $qty;
                
    //             $save = $cart->update([
    //                 'qty' => $newQty,
    //                 'price' => $price
    //             ]);
    //         } else {
    //             $save = Cart::create([
    //                 'product_id' => $request->product_id,
    //                 'user_id' => Auth::id(),
    //                 'qty' => $request->qty,
    //                 'price' => $price,
    //                 'discount_amount' => $discountedAmount
    //             ]);
    //         }

    //         $cartId = $save->id;

    //     } else {
    //         $cart = session('cart', []);
    //         $not_exist = true;

    //         foreach ($cart as $key => $order) {
    //             if ($order->product_id == $request->product_id) {
    //                 $cart[$key]->qty = $request->qty;
    //                 $cart[$key]->price = $price;
    //                 $not_exist = false;
    //                 break;
    //             }
    //         }

    //         if ($not_exist) {
    //             $order = new Cart();
    //             $order->product_id = $request->product_id;
    //             $order->qty = $request->qty;
    //             $order->price = $price;

    //             array_push($cart, $order);
    //         }

    //         $cartId = $order->id;
    //         session(['cart' => $cart]);
    //     }
       
    //     $inventory_remark = true;

    //     if($inventory_remark){
    //         return response()->json([
    //             'success' => true,
    //             'cartId' => $cartId,
    //             'totalItems' => Setting::EcommerceCartTotalItems()                
    //         ]);
            
    //     }else{
    //         return response()->json([
    //             'success' => false,
    //             'totalItems' => Setting::EcommerceCartTotalItems()                
    //         ]);
    //     }
    // }

    public function buy_now(Request $request)
    {
        $requestData = $request->all();
        $requestData['user_id'] = Auth::id();

        Cart::create($requestData);

        return redirect(route('cart.front.show'));
    } 

    public function cart()
    {   
        //to check update coupon availability
        Coupon::checkCouponAvailability();

        if (auth()->check()) {

            // reset coupon carts of customer
            CouponCartDiscount::where('customer_id',Auth::id())->delete();
            CouponCart::where('customer_id',Auth::id())->delete();

            $cart = Cart::where('user_id',Auth::id())->where('qty', '>', 0)->get();
            
            $totalProducts = $cart->count();
        } else {
            $cart = session('cart', []);
            $totalProducts = count(session('cart', []));
        }

        $coupons = Coupon::where('status','ACTIVE')->where('activation_type','<>','manual')->get();

        $page = new Page();
        $page->name = 'Cart';

        return view('theme.pages.ecommerce.cart', compact('cart', 'coupons', 'totalProducts','page'));
    }

    public function remove_product(Request $request)
    {
        if (auth()->check()) {
            Cart::whereId((int) $request->order_id)->delete();
        } else {
            $cart = session('cart', []);
            $index = (int) $request->order_id;
            if (isset($cart[$index])) {
                unset($cart[$index]);
            }
            session(['cart' => $cart]);
        }

        return back()->with('cart_success', 'Product has been removed.');
    }

    public function cart_update(Request $request)
    {
        $productCart = Cart::find($request->orderID);
        if (auth()->check()) {            
            if (Cart::where('user_id', auth()->id())->count() == 0) {
                return;
            }

            $qry = Cart::find($request->orderID);

            $qry->update([
                'qty' => $request->quantity
            ]);

            // $cart_qty = $qry->first();

            // $price_before = $cart_qty->product->price*$cart_qty->qty;


            // $carts = Cart::where('user_id', auth()->id())->get();
            // $total_promo_discount = 0;
            // $subtotal = 0;

            // foreach($carts as $cart){

            //     $promo_discount = $cart->product->price-$cart->product->discountedprice;
            //     $total_promo_discount += $promo_discount*$cart->qty;
            //     $subtotal += $cart->product->price*$cart->qty;
            // }
        } else {
            $cart = session('cart', []);
                foreach ($cart as $key => $order) {
                    if ($order->product_id == $request->orderID) {
                        $cart[$key]->qty = $request->quantity;
                        break;
                    }
                }
            session(['cart' => $cart]);
        }

        return response()->json([
            'success' => true,
            'maxOrder' => $productCart->product->inventory,
            'totalItems' => Setting::EcommerceCartTotalItems()
            // 'total_promo_discount' => $total_promo_discount,
            // 'subtotal' => $subtotal,
            // 'recordid' => $request->orderID,
            // 'price_before' => $price_before        
        ]);
    }

    public function proceed_checkout(Request $request)
    {
        if(auth()->check()){   
                  
            if (Cart::where('user_id', auth()->id())->count() == 0) {
                return redirect()->route('product.brands');
            }

            if(Auth::user()->role_id <> 6){
                abort(403, 'Administrator accounts are not authorized to create sales transactions.');
            }

            $use_ecredit = $request->ecredit_toggle ? 1 : 0;

            $data   = $request->all();
            $cartId = $data['cart_id'];
            $qty    = $data['quantity'];
            $price  = $data['product_price'];

            foreach($cartId as $key => $cart){
                Cart::find($cart)->update([
                    'qty' => $qty[$key],
                    'price' => $price[$key]
                ]);
            }


            if($request->coupon_counter > 0){
                $data     = $request->all();
                $coupons  = $data['couponid'];
                $product  = $data['coupon_productid'];
                $usage    = $data['couponUsage'];
                $discount = $data['discount'];

                foreach($coupons as $key => $c){
                    $coupon = Coupon::find($c);

                    if($coupon->status == 'ACTIVE'){
                        CouponCart::create([
                            'customer_id' => Auth::id(),
                            'product_id' => $product[$key] == 0 ? 0 : $product[$key],
                            'coupon_id' => $coupon->id,
                            'total_usage' => $usage[$key],
                            'discount' => $discount[$key]
                        ]);
                    }
                }
            }

            CouponCartDiscount::create([
                'customer_id' => Auth::id(),
                'coupon_discount' => $request->coupon_total_discount
            ]);

            session(['use_ecredit' => $use_ecredit]);

            return redirect()->route('cart.front.checkout');

        } else {
            return redirect()->route('customer-front.login');
        }
    }

    public function checkout()
    {
        $page = new Page();
        $page->name = 'Checkout';

        $customer  = User::find(Auth::id());
        $orders    = Cart::where('user_id',Auth::id())->where('qty', '>', 0)->get();      
        $cart      = CouponCartDiscount::where('customer_id',Auth::id())->first();

        $coupons = CouponCart::where('customer_id', Auth::id())->get();

        $use_ecredit = session('use_ecredit');

        // $lbc_provinces = LBCHelper::provinces();

        return view('theme.pages.ecommerce.checkout', compact('orders', 'cart', 'coupons', 'customer', 'page', 'use_ecredit'));
        // return view('theme.pages.ecommerce.checkout', compact('orders', 'cart', 'coupons', 'customer', 'page', 'lbc_provinces'));
    }

    public function lbc_cities(Request $request)
    {
        $cities = LBCHelper::cities($request->provinceId);

        return response()->json([
            'cities' => $cities, 
        ]);
    }

    public function lbc_barangays(Request $request)
    {
        $barangays = LBCHelper::barangay($request->cityId);
        
        return response()->json([
            'barangays' => $barangays, 
        ]);
    }

    public function next_order_number(){
        $last_order = SalesHeader::whereDate('created_at', Carbon::today())->orderBy('created_at','desc')->first();
        if(empty($last_order)){
            $next_number = date('Ymd')."-0001";
        }
        else{
            $order_number = explode("-",$last_order->order_number);
            if(!isset($order_number[1])){
                $next_number = date('Ymd')."-0001";
            }
            else{

                $next_number = date('Ymd')."-".str_pad(($order_number[1] + 1), 4, '0', STR_PAD_LEFT);
            }
        }
        return $next_number;
    }

    public function save_sales(Request $request) 
    {
        $coupon_total_discount = number_format($request->coupon_total_discount,2,'.','');

        $totalPrice  = number_format($request->payment_method == 'ecredit' ? 0 : ($request->total_amount < 0 ? 0 : $request->total_amount), 2,'.','');
        $realTotalPrice  = number_format($request->total_amount < 0 ? $request->total_amount + $request->ecredit_amount : $request->total_amount, 2,'.','');
        $orderNumber = $this->next_order_number(); 

        $customerAddress = $request->customer_delivery_barangay.', '.$request->customer_delivery_city.', '.$request->customer_delivery_province.', '.$request->customer_delivery_zip;

        $use_ecredit = $request->payment_method == 'ecredit' ? 1 : 0;
        
        session(['use_ecredit' => $use_ecredit]);

        //FOR THE ECREDIT
        
        $current_ecredit = number_format($request->ecredit_amount,2,'.','');
        $new_ecredit = ($current_ecredit - $realTotalPrice) > 0 ? $current_ecredit - $realTotalPrice : 0;

        User::where('id', Auth::user()->id)
        ->update([
            'ecredits' => $new_ecredit
        ]);

        //
        
        $ecredit_amount = ($current_ecredit - $realTotalPrice) < 0 ? $current_ecredit : $realTotalPrice;

        $requestData = $request->all();
        $requestData['user_id'] = Auth::id();
        $requestData['order_number'] = $orderNumber;
        $requestData['customer_name'] = $request->customer_fname.' '.$request->customer_lname;
        $requestData['customer_delivery_adress'] = $customerAddress;
        $requestData['customer_address'] = $customerAddress;
        $requestData['delivery_type'] = $request->shippingOption;
        $requestData['delivery_fee_amount'] = $request->shippingRate;
        $requestData['delivery_fee_discount'] = $request->shippingFeeDiscount;
        $requestData['delivery_status'] = $request->payment_method == 'cod' || 'ecredit' ? 'Pending' : 'Pending';
        $requestData['gross_amount'] = number_format($totalPrice,2,'.','');
        $requestData['net_amount'] = number_format($totalPrice,2,'.','');
        $requestData['discount_amount'] = $coupon_total_discount;
        $requestData['ecredit_amount'] = $ecredit_amount;
        $requestData['payment_method'] = $request->payment_method;
        $salesHeader = SalesHeader::create($requestData);
        session::put('shid', $salesHeader->id);

        $this->store_items($salesHeader->id);

        $this->update_coupon_status($request, $salesHeader->id);

        Cart::where('user_id', Auth::id())->delete();
        Mail::to(Auth::user())->send(new SalesCompleted($salesHeader, Setting::info()));  

        //to check update coupon availability
        Coupon::checkCouponAvailability();

        return redirect(route('cart.success'));
    }
    
    public function success(){
        
        $page = new Page();
        $page->name = 'Success';

        return view('theme.pages.ecommerce.success', compact('page'));

    }

    public function store_items($headerId)
    {
        $carts = Cart::where('user_id',Auth::id())->get();

        $coupon_code = 0; $coupon_amount = 0;

        foreach ($carts as $cart) {
            $product = $cart->product;
            $gross_amount = (number_format($cart->price,2,'.','') * $cart->qty);
            $tax_amount = $gross_amount - ($gross_amount/1.12);


            $data['price'] = number_format($cart->price,2,'.','');
            $data['tax'] = $data['price'] - ($data['price']/1.12);
            $data['other_cost'] = 0;
            $data['net_price'] = $data['price'] - ($data['tax'] + $data['other_cost']);

            SalesDetail::create([
                'sales_header_id' => $headerId,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_category' => $product->category_id,
                'price' => number_format($cart->price,2,'.',''),              
                'tax_amount' => number_format($tax_amount,2,'.',''),
                'promo_id' => 0,
                'promo_description' => '',
                'discount_amount' => $cart->discount_amount,
                'gross_amount' => number_format($gross_amount,2,'.',''),
                'net_amount' => number_format($gross_amount,2,'.',''),
                'qty' => $cart->qty,             
                'uom' => $product->uom,               
                'created_by' => Auth::id()
            ]);  
        }
    }


    public function receive_data_from_payment_gateway(Request $request)
    {
        logger($request);
        
        $paymentResponse = (isset($_POST['paymentresponse'])) ? $_POST['paymentresponse'] : null;

        if (empty($paymentResponse)) {
            return false;
        }

        
        $body = str_replace(" ", "+", $paymentResponse);

        try {
            $Decodebody = base64_decode($body);
            $ServiceResponseWPF = simplexml_load_string($Decodebody, 'SimpleXMLElement'); // new \SimpleXMLElement($Decodebody);
            $application = $ServiceResponseWPF->application;
            $responseStatus = $ServiceResponseWPF->responseStatus;

            $log = [
                'result_return' => $paymentResponse,
                'request_id' => $application->request_id,
                'response_id' => $application->response_id,
                'response_code' => $responseStatus->response_code,
                'response_message' => $responseStatus->response_message,
                'response_advise' => $responseStatus->response_advise,
                'timestamp' => $application->timestamp,
                'ptype' => $application->ptype,
                'rebill_id' => $application->rebill_id,
                'token_id' => (isset($application->token_id)) ? $application->token_id : '',
                'token_info' => (isset($application->token_info)) ? $application->token_info : '',
                'processor_response_id' => $responseStatus->processor_response_id,
                'processor_response_authcode' => $responseStatus->processor_response_authcode,
                'signature'  => $application->signature,
            ];
            $merchant = Setting::paynamics_merchant();
            $cert = $merchant['key']; //merchantkey

            $forSign = $application->merchantid . $application->request_id . $application->response_id . $responseStatus->response_code . $responseStatus->response_message . $responseStatus->
                response_advise . $application->timestamp . $application->rebill_id . $cert;

            $_sign = hash("sha512", $forSign);
           
            if ($_sign == $ServiceResponseWPF->application->signature) {

                $sales = SalesHeader::where('order_number', $application->request_id)->first();

                if (empty($sales)) {
                    $log['response_title'] = 'Sales Header not found';
                    PaynamicsLog::create($log);

                    return false;
                }

                if ($responseStatus->response_code == "GR001" || $responseStatus->response_code == "GR002") {
                    //SUCCESS TRANSACTION

                    $log['response_title'] = 'Success';
                    PaynamicsLog::create($log);

                    $sales->update([
                        'payment_status' => 'PAID',
                        'delivery_status' => 'Pending',
                        'payment_method' => 'Paynamics-'.$application->ptype
                    ]);

                    $update_payment = SalesPayment::create([
                        'sales_header_id' => $sales->id,
                        'amount' => $sales->net_amount,
                        'payment_type' => 'Paynamics-'.$application->ptype,
                        'status' => 'PAID',
                        'payment_date' => date('Y-m-d',strtotime($application->timestamp)),
                        'receipt_number' => $application->response_id,
                        'created_by' => Auth::id() ?? '1',
                        'response_body'=> $body,
                        'response_id' => $application->response_id,
                        'response_code' => $responseStatus->response_code
                    ]);

                    $c = CouponSale::where('sales_header_id',$sales->id);
                    $c->update(['order_status' => 'PAID']);

                    $coupons = $c->get();
                    foreach($coupons as $coupon){
                        $totalCustomer = CouponSale::where('coupon_id',$coupon->coupon_id)->count();
                        $cpn = Coupon::find($coupon->coupon_id);
                        
                        if($totalCustomer == $cpn->customer_limit){
                            $cpn->update(['status' => 'INACTIVE']);
                        }
                    }

                    $this->remove_cart_coupon();
                    
                } else if ($responseStatus->response_code == "GR053") {
                    $log['response_title'] = 'Cancelled';
                    PaynamicsLog::create($log);

                    $sales->update([
                        'payment_status' => 'CANCELLED'                        
                    ]);

                    CouponSale::where('sales_header_id',$sales->id)->update(['order_status' => 'PAID']);
                } else {

                    $log['response_title'] = 'Failed';
                    PaynamicsLog::create($log);

                    $sales->update([
                        'payment_status' => 'FAILED'
                    ]);

                    CouponSale::where('sales_header_id',$sales->id)->update(['order_status' => 'PAID']);
                }
            } else {
                $log['response_title'] = 'Invalid Signature';
                PaynamicsLog::create($log);
            }
        } catch(Exception $ex) {
            PaynamicsLog::create([
                'result_return' => $ex->getMessage(),
                'response_title' => 'Try catch Error'
            ]);
        }
    }

    public function generate_payment(Request $request){
        $salesHeader = SalesHeader::where('order_number',$request->order)->first();        
        $sign = $this->generateSignature('2amqVf04H9','PH00125',$request->order,str_replace(".", "", number_format($request->amount,2,'.','')),'PHP');
        $payment = $this->ipay($salesHeader,$request->amount,$sign);
        return response()->json([
                'success' => true,
                'order_number' => $request->order,
                'customer_contact_number' => Auth::user()->contact_mobile, 
                'amount' => number_format($request->amount,2,'.',''),
                'signature' => $sign
            ]);
    }

    public function remove_cart_coupon()
    {
        CouponCart::where('customer_id',Auth::id())->delete();
    }

    public function update_coupon_status($request,$salesid)
    {
        $data = $request->all();

        if(isset($request->freeproductid)){
            $freeproducts = $data['freeproductid'];
            // if has free products
            foreach($freeproducts as $productid){
                $product = Product::find($productid);

                SalesDetail::create([
                    'sales_header_id' => $salesid,
                    'product_id' => $productid,
                    'product_name' => $product->name,
                    'product_category' => $product->category_id,
                    'price' => 0,              
                    'tax_amount' => 0,
                    'promo_id' => 0,
                    'promo_description' => '',
                    'discount_amount' => 0,
                    'gross_amount' => 0,
                    'net_amount' => 0,
                    'qty' => 1,             
                    'uom' => $product->uom,               
                    'created_by' => Auth::id()
                ]);
            }
        }

        if(isset($data['couponid'])){
            $coupons = $data['couponid'];
            foreach($coupons as $c){
                $coupon = Coupon::find($c);
    
                $cart = CouponCart::where('customer_id',Auth::id())->where('coupon_id',$coupon->id);
    
                if($cart->exists()){
                    $ct = $cart->first();
    
                    if(isset($ct->product_id)){
                        $productid = $ct->product_id;
                    } else {
                        $productid = NULL;
                    }            
                } else {
                    $productid = NULL;
                }
    
                CouponSale::create([
                    'customer_id' => Auth::id(),
                    'coupon_id' => $c,
                    'coupon_code' => $coupon->coupon_code,
                    'sales_header_id' => $salesid,
                    'product_id' => $productid
                ]);   
            }
        }
    }
}
