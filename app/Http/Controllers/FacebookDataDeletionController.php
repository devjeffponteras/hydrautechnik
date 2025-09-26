<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FacebookDataDeletionController extends Controller
{
    public function handle(Request $request)
    {
        // Validate that signed_request exists
        $signedRequest = $request->input('signed_request');
        if (!$signedRequest) {
            return response()->json(['error' => 'Missing signed request'], 400);
        }

        // Split the signed request into two parts (signature and payload)
        list($encodedSig, $payload) = explode('.', $signedRequest, 2);

        // Decode signature and payload
        $sig = base64_decode(strtr($encodedSig, '-_', '+/'));
        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        // Validate the signature
        $expectedSig = hash_hmac('sha256', $payload, env('FACEBOOK_CLIENT_SECRET'), $raw = true);
        if ($sig !== $expectedSig) {
            Log::warning('Invalid signature for Facebook data deletion request', ['signed_request' => $signedRequest]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Extract user ID from payload
        $userId = $data['user_id'] ?? null;
        if (!$userId) {
            return response()->json(['error' => 'User ID not found in request'], 400);
        }

        // Find the user by Facebook ID
        $user = User::where('facebook_id', $userId)->first();

        // If user is found, soft delete the account
        if ($user) {
            $user->delete();  // Use soft delete if the model is configured for it
            Log::info('User soft deleted', ['user_id' => $userId]);
        } else {
            Log::warning('User not found for deletion', ['facebook_id' => $userId]);
            return response()->json(['error' => 'User not found'], 404);
        }

        // Return response required by Facebook API
        return response()->json([
            'url' => route('facebook.data-deletion'),  // Confirmation URL
            'confirmation_code' => $userId,  // Send user ID back as confirmation code
        ]);
    }
}
