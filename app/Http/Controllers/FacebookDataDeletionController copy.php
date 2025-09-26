<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FacebookDataDeletionController extends Controller
{
    public function handle(Request $request)
    {
        $signedRequest = $request->input('signed_request');
        list($encodedSig, $payload) = explode('.', $signedRequest, 2); 

        $sig = base64_decode(strtr($encodedSig, '-_', '+/'));
        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        // Validate the signature
        $expectedSig = hash_hmac('sha256', $payload, env('FACEBOOK_CLIENT_SECRET'), $raw = true);
        if ($sig !== $expectedSig) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle user data deletion
        $userId = $data['user_id'];
        $user = User::where('facebook_id', $userId)->first();

        if ($user) {
            // Use soft delete to mark the user for deletion
            $user->delete();
        }

        return response()->json([
            'url' => route('facebook.data-deletion'),
            'confirmation_code' => $userId,
        ]);
    }
}

