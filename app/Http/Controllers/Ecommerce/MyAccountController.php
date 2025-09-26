<?php

namespace App\Http\Controllers\Ecommerce;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Mail\DeliveryStatusMail;
use Illuminate\Support\Facades\Mail;

use App\Helpers\{ListingHelper, Setting, PaynamicsHelper, XDEHelper, LBCHelper};

use App\Models\Ecommerce\{
    Cart, SalesHeader, SalesDetail, CustomerAddress, CustomerFavorite, CustomerWishlist, Product
};

use App\Models\{
    Page, User
};

use Auth;

class MyAccountController extends Controller
{
    public function dashboard(Request $request)
    {
        $page = new Page;
        $page->name = 'Dashboard';
        $sales = SalesHeader::where('user_id',Auth::id())->orderBy('id','desc')->paginate(10);

        $member = auth()->user();
        $user = auth()->user();

        $additional_addresses = CustomerAddress::where('user_id', $user->id)->get();

        return view('theme.pages.customer.dashboard', compact('member', 'user', 'page', 'additional_addresses', 'sales'));
    }

    public function manage_account(Request $request)
    {
        $page = new Page;
        $page->name = 'My Account';

        $member = auth()->user();
        $user = auth()->user();

        $additional_addresses = CustomerAddress::where('user_id', $user->id)->get();

        return view('theme.pages.customer.manage-account', compact('member', 'user', 'page', 'additional_addresses'));
    }

    public function deactivate_social_login(Request $request)
    {
        $page = new Page;
        $page->name = 'Social Login Deactivated';

        User::where('id', $request->user_id)
        ->update([
            'social_login' => 0,
            'is_active' => 0
        ]);

        Auth::logout();

        return view('theme.pages.customer.social-login-deactivation', compact('page'));
    }

    public function library(Request $request)
    {
        $page = new Page;
        $page->name = 'My Library';

        $member = auth()->user();
        $user = auth()->user();

        $additional_addresses = CustomerAddress::where('user_id', $user->id)->get();

        return view('theme.pages.customer.library', compact('member', 'user', 'page', 'additional_addresses'));
    }

    public function wishlist(Request $request)
    {
        $page = new Page;
        $page->name = 'My Wishlist';

        $member = auth()->user();
        $user = auth()->user();
        $pageLimit = 12;

        $customer_wishlists = Product::select('products.*')
        ->leftJoin('product_additional_infos', 'products.id', '=', 'product_additional_infos.product_id')
        ->where('products.status', 'PUBLISHED')
        ->join('customer_wishlists', 'customer_wishlists.product_id', '=', 'products.id')
        ->where('customer_wishlists.customer_id', auth()->id());

        $searchtxt = $request->get('keyword', false);
        $sortBy = $request->get('sort_by', false);

        if(!empty($searchtxt)){  
            $keyword = Str::lower($request->keyword); 

            $customer_wishlists = $customer_wishlists->where(function($query) use ($keyword){
                $query->orWhereRaw('LOWER(products.name) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(products.author) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(products.description) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(product_additional_infos.value) like LOWER(?)', ["%{$keyword}%"]);
            });
        }

        if($sortBy == "name_asc"){
            $customer_wishlists = $customer_wishlists->orderBy('name','asc')->paginate($pageLimit);
        }
        elseif($sortBy == "name_desc"){
            $customer_wishlists = $customer_wishlists->orderBy('name','desc')->paginate($pageLimit);
        }
        elseif($sortBy == "price_asc"){
            $customer_wishlists = $customer_wishlists->orderBy('price','asc')->paginate($pageLimit);
        }
        elseif($sortBy == "price_desc"){
            $customer_wishlists = $customer_wishlists->orderBy('price','desc')->paginate($pageLimit);
        }
        elseif($sortBy == "date_asc"){
            $customer_wishlists = $customer_wishlists->orderBy('created_at','asc')->paginate($pageLimit);
        }
        elseif($sortBy == "date_desc"){
            $customer_wishlists = $customer_wishlists->orderBy('created_at','desc')->paginate($pageLimit);
        }
        else{
            $customer_wishlists = $customer_wishlists->orderBy('name','asc')->paginate($pageLimit);
        }


        return view('theme.pages.customer.wishlist', compact('member', 'user', 'page', 'customer_wishlists'));
    }

    public function favorites(Request $request)
    {
        $page = new Page;
        $page->name = 'My Favorites';

        $member = auth()->user();
        $user = auth()->user();
        $pageLimit = 12;

        $customer_favorites = Product::select('products.*')
        ->leftJoin('product_additional_infos', 'products.id', '=', 'product_additional_infos.product_id')
        ->where('products.status', 'PUBLISHED')
        ->join('customer_favorites', 'customer_favorites.product_id', '=', 'products.id')
        ->where('customer_favorites.customer_id', auth()->id());

        $searchtxt = $request->get('keyword', false);
        $sortBy = $request->get('sort_by', false);

        if(!empty($searchtxt)){  
            $keyword = Str::lower($request->keyword); 

            $customer_favorites = $customer_favorites->where(function($query) use ($keyword){
                $query->orWhereRaw('LOWER(products.name) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(products.author) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(products.description) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(product_additional_infos.value) like LOWER(?)', ["%{$keyword}%"]);
            });
        }

        if($sortBy == "name_asc"){
            $customer_favorites = $customer_favorites->orderBy('name','asc')->paginate($pageLimit);
        }
        elseif($sortBy == "name_desc"){
            $customer_favorites = $customer_favorites->orderBy('name','desc')->paginate($pageLimit);
        }
        elseif($sortBy == "price_asc"){
            $customer_favorites = $customer_favorites->orderBy('price','asc')->paginate($pageLimit);
        }
        elseif($sortBy == "price_desc"){
            $customer_favorites = $customer_favorites->orderBy('price','desc')->paginate($pageLimit);
        }
        elseif($sortBy == "date_asc"){
            $customer_favorites = $customer_favorites->orderBy('created_at','asc')->paginate($pageLimit);
        }
        elseif($sortBy == "date_desc"){
            $customer_favorites = $customer_favorites->orderBy('created_at','desc')->paginate($pageLimit);
        }
        else{
            $customer_favorites = $customer_favorites->orderBy('name','asc')->paginate($pageLimit);
        }


        return view('theme.pages.customer.favorites', compact('member', 'user', 'page', 'customer_favorites'));
    }

    // public function favorites(Request $request)
    // {
    //     $page = new Page;
    //     $page->name = 'My Favorites';

    //     $member = auth()->user();
    //     $user = auth()->user();

    //     $customer_favorites = CustomerFavorite::where('customer_id', auth()->user()->id ?? -1)
    //     ->with('product') // Eager load the related product
    //     ->get();

    //     return view('theme.pages.customer.favorites', compact('member', 'user', 'page', 'customer_favorites'));
    // }

    public function free_ebooks(Request $request)
    {
        $page = new Page;
        $page->name = 'Free E-books';

        $member = auth()->user();
        $user = auth()->user();

        $additional_addresses = CustomerAddress::where('user_id', $user->id)->get();

        return view('theme.pages.customer.free-ebooks', compact('member', 'user', 'page', 'additional_addresses'));
    }

    public function ecredits(Request $request)
    {
        $page = new Page;
        $page->name = 'E-Credits';

        $member = auth()->user();
        $user = auth()->user();

        $additional_addresses = CustomerAddress::where('user_id', $user->id)->get();

        return view('theme.pages.customer.ecredits', compact('member', 'user', 'page', 'additional_addresses'));
    }

    public function update_personal_info(Request $request)
    {
        $requestData = $request->except(['_token', 'additional_address']);
        $requestData['name'] = $request->firstname.' '.$request->lastname;

        User::whereId((int) Auth::id())->update($requestData);

        
        $user_exists = CustomerAddress::where('user_id', Auth::id())->first();

        if($user_exists){
            CustomerAddress::where('user_id', Auth::id())->delete();
        }
        
        if($request->additional_address){
            foreach($request->additional_address as $additional_add){

                $additionalInfo['user_id'] = auth()->user()->id;
                $additionalInfo['additional_address'] = $additional_add;

                CustomerAddress::create($additionalInfo);
            }
        }

        return redirect()->back()->with('success', 'Account details has been updated');
    }

    public function change_password()
    {
        $page = new Page();
        $page->name = 'Change Password';

        return view('theme.pages.customer.change-password',compact('page'));
    }

    public function update_password(Request $request)
    {
        $personalInfo = $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, auth()->user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            'password' => [
                'required',
                'min:8',
                'max:150', 
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[0-9]/', // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character              
            ],
            'confirm_password' => 'required|same:password',
        ]);

        auth()->user()->update(['password' => bcrypt($personalInfo['password'])]);

        return back()->with('success', 'Password has been updated');
    }


    public function orders(){

        $sales = SalesHeader::where('user_id',Auth::id())->orderBy('id','desc')->paginate(10);

        $page = new Page();
        $page->name = 'Sales Transaction';

        return view('theme.pages.customer.orders',compact('sales','page'));
    }

    public function cancel_order(Request $request){

        $sales = SalesHeader::find($request->orderid);
        $sales->update([
            'cancellation_request' => 1,
            'cancellation_reason' => $request->reason,
            'cancellation_remarks' => $request->remarks,
            'delivery_status' => 'CANCELLED',
            'status' => 'CANCELLED'
        ]);

        Mail::to(Auth::user())->send(new DeliveryStatusMail($sales, Setting::info()));  

        return back()->with('success','Order #:'.$sales->order_number.' has been cancelled.');
    }

    // public function cancel_order(Request $request){

    //     $sales = SalesHeader::find($request->orderid);
    //     $sales->update(['status' => 'CANCELLED', 'delivery_status' => 'CANCELLED']);

    //     return back()->with('success','Order #:'.$sales->order_number.' has been cancelled.');
    // }

    public function pay_again($id){
        $r = SalesHeader::findOrFail($id);

        $urls = [
            'notification' => route('cart.payment-notification'),
            'result' => route('profile.sales'),
            'cancel' => route('profile.sales'),
        ];

        $totalDiscountedPrice = $r->gross_amount-$r->discount_amount;

        $base64Code = PaynamicsHelper::payNow($r->id, Auth::user(), number_format($totalDiscountedPrice, 2, '.', ''), $urls, false, number_format($r->delivery_fee_amount, 2, '.', ''), number_format($r->discount_amount, 2, '.', ''), number_format($r->delivery_fee_discount, 2, '.', ''));

        return view('theme.paynamics.sender', compact('base64Code'));
    }

}
