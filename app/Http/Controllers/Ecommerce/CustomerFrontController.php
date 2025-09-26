<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Helpers\Setting;

use App\Models\Ecommerce\{
    Cart, Product
};

use App\Models\{
    User, Page
};

use Session;
use DB;

class CustomerFrontController extends Controller
{
    public function sign_up(Request $request) {

        $page = new Page();
        $page->name = 'Sign Up';

        return view('theme.pages.sign-up',compact('page'));

    }

    public function customer_sign_up(Request $request) {

        Validator::make($request->all(), [
            'email' => 'required|email|max:191',
            'lastname' => 'required',
            'firstname' => 'required',
            'mobile' => 'required|numeric',
            // 'company' => 'nullable',
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8',
        ])->validate();

        $usr = User::withTrashed()->where('email', $request->email);
        if($usr->count() > 0){
            $u = $usr->first();
            if($u->trashed()){
                return back()->with('error', 'Your account has been deactivated. Please contact administrator to activate your account.');
            } else {
                return back()->with('error', 'Account is already in the list.');
            }
        }

        $requestData = $request->all();
        $requestData['name'] = $request->firstname.' '.$request->lastname;
        $requestData['password'] = str_random(32);
        $requestData['remember_token'] = str_random(10);
        $requestData['email_verified_at'] =  date('Y-m-d H:i:s');
        $requestData['is_active'] = 1;
        $requestData['role_id'] = 6;

        $user = User::create($requestData);
        $this->sendNewUserResetLinkEmail($request);

        return redirect()->back()->with('success', 'Pending for activation. Please check your email to activate password.');
    }

    // public function customer_sign_up(Request $request) {

    //     Validator::make($request->all(), [
    //         'email' => 'required|email|max:191',
    //         'lastname' => 'required',
    //         'firstname' => 'required',
    //         'mobile' => 'required|numeric',
    //         // 'company' => 'nullable',
    //         'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
    //         'password_confirmation' => 'min:8',
    //     ])->validate();

    //     $usr = User::withTrashed()->where('email', $request->email);
    //     if($usr->count() > 0){
    //         $u = $usr->first();
    //         if($u->trashed()){
    //             return back()->with('error', 'Your account has been deactivated. Please contact administrator to activate your account.');
    //         } else {
    //             return back()->with('error', 'Account is already in the list.');
    //         }
    //     }

    //     $requestData = $request->all();
    //     $requestData['name'] = $request->firstname.' '.$request->lastname;
    //     $requestData['password'] = \Hash::make($request->password);
    //     $requestData['remember_token'] = str_random(10);
    //     $requestData['email_verified_at'] =  date('Y-m-d H:i:s');
    //     $requestData['is_active'] = 1;
    //     $requestData['role_id'] = 6;

    //     $user = User::create($requestData);
    //     Auth::login($user);

    //     return redirect(env('APP_URL') . '/books')->with('success', 'Registration Successful!');
    //     // return redirect(route('product.brands'))->with('success','Registration Successful!');
    // }

    public function login(Request $request) {

        $page = new Page();
        $page->name = 'Login';

        return view('theme.pages.login',compact('page'));

    }

    public function customer_login(Request $request)
    {
        $userCredentials = [
            'email'    => $request->email,
            'password' => $request->password
        ];

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            unset($userCredentials['username']);
            $userCredentials['email'] = $request->email;
        }

        $cart = session('cart', []);
        
        if (Auth::attempt($userCredentials)) {

       
            if(Auth::user()->role_id <> '6'){ // block cms users from using this login form
                Auth::logout();
                return back()->with('error', 'Administrative accounts are not allowed to login as customer.'); 
            }

            if(Auth::user()->is_active <> 1){ // block inactive users from using this login form
                Auth::logout();
                return back()->with('error', 'Account is not active.'); 
            }


            foreach ($cart as $order) {
                $product = Product::find($order['product_id']);
                $cart = Cart::where('product_id', $order['product_id'])
                    ->where('user_id', Auth::id())
                    ->first();

                if (!empty($cart)) {
                    $newQty = $cart->qty + $order['qty'];
                    $cart->update([
                        'qty' => $newQty,
                        'price' => $product->price,
                        'paella_price' => $order['paella_price']
                    ]);
                } else {
                    Cart::create([
                        'product_id' => $order['product_id'],
                        'user_id' => Auth::id(),
                        'qty' => $order['qty'],
                        'price' => $product->price,
                        'paella_price' => $order['paella_price']
                    ]);
                }
            }

            session()->forget('cart');
            $cnt = Cart::where('user_id',Auth::id())->count();
            if($cnt > 0)
                return redirect(route('cart.front.show'));
            else
                return redirect(route('product.front.list'));
        } else {
            Auth::logout();
            return back()->with('error', __('auth.login.incorrect_input'));    
        }

    }

    public function forgot_password(Request $request) {

        $page = new Page();
        $page->name = 'Forgot Password';

        return view('theme.pages.forgot-password', compact('page'));

    }

    public function sendNewUserResetLinkEmail(Request $request)
    {
        $request->validate(
            ['email' => 'required|email|exists:users,email'],
            ['email.exists' => trans('passwords.user')]
        );

        $user = User::where('email', $request->email)->first();

        $user->send_reset_temporary_password_email();

        if (\Mail::failures()) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans('passwords.user')]);
        }

        return back()->with('status', trans('passwords.sent'));
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(
            ['email' => 'required|email|exists:users,email'],
            ['email.exists' => trans('passwords.user')]
        );

        $user = User::where('email', $request->email)->withTrashed()->first();

        if ($user->trashed()) {
            return back()
            ->with('error', 'Your account has been disabled, please contact an administrator.');
        } else {
            $user->send_reset_password_email();

            if (\Mail::failures()) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans('passwords.user')]);
            }

            // return back()->with('status', trans('passwords.sent'))->with('success', 'Registration Successful! You are now logged in');
            return back()
            ->with('status', trans('passwords.sent'))
            ->with('success', 'Please check your email for password reset link');
        }

    }

    public function showResetForm(Request $request, $token = null)
    {
        $credentials =  $request->only('email');

        $page = new Page();
        $page->name = 'Reset Password';
 
        if (!$token){
            return redirect()->route('customer-front.forgot_password')->with('error','Your link is expired. Please reset your password again.');
        } 

        return view('theme.pages.reset-password',)->with(
            ['token' => $token, 'email' => $request->email, 'page' => $page]
        );
    }

    public function reset(Request $request)
    {
        $credentials = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            "password" => [
                'required',
                'confirmed',
                'min:8',
                'max:150',
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[0-9]/', // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'password_confirmation' => 'required|same:password',
        ]);

        User::where('email', $request->email)->update(['password' => bcrypt($request->password)]);

        $user = User::where('email', $request->email)->first();
        Auth::login($user);

        return redirect(env('APP_URL') . '/books')->with('success', 'Registration Successful! You are now logged in');

        // return redirect()->route('customer-front.login')->with('success', 'Password has been reset.');
    }

    public function logout()
    {
        Auth::logout();

        return redirect(route('home'));
    }
}
