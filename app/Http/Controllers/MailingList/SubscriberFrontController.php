<?php

namespace App\Http\Controllers\MailingList;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\Setting;
use App\Mail\MailingList\UnsubscribedMail;
use App\Mail\MailingList\WelcomeMail;

use App\Models\{Group, Subscriber};


class SubscriberFrontController extends Controller
{
    public function subscribe(Request $request)
    {
        $requestData = $request->all();
        $requestData['code'] = Subscriber::generate_unique_code();

        
        // $existing_subscriber = Subscriber::where('name', $requestData['name'])->where('email', $requestData['email'])->first();
        $existing_subscriber = Subscriber::where('email', $requestData['email'])->first();
        // $existing_subscriber = Subscriber::where(function($query) use ($requestData) {
        //     $query->where('name', $requestData['name'])
        //           ->orWhere('email', $requestData['email']);
        // })->first();
        
        // dd($subscriber);
        
        if (empty($existing_subscriber)) {

            $subscriber = Subscriber::create($requestData);

            \Mail::to($request->email)->send(new WelcomeMail(Setting::info(), $subscriber));

            return response()->json(['success' => true, 'message' => 'Thank you for subscribing.']);
        } else {
            return response()->json(['failed' => true, 'message' => 'Failed to subscribe. Email already exists.']);
        }

        return back()->with('success', 'Thank you for subscribing.');

    }

    public function unsubscribe(Request $request, Subscriber $subscriber, $code)
    {
        if ($subscriber->code == $code) {

            \Mail::to($subscriber->email)->send(new UnsubscribedMail(Setting::info()));

            $subscriber->where('id', $subscriber->id)->delete();

           return "You’ve been successfully removed from our mailing list. <script>window.setTimeout(function(){window.location.href = '".url('/')."'}, 3000);";
        }

        abort(404);

    }   
}

