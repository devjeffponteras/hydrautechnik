<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class GoogleDataDeletionController extends Controller
{
    /**
     * Handle Google account data deletion request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        // Validate the request to ensure it has the necessary information
        $request->validate([
            'email' => 'required|email',
        ]);

        // Get the user's email from the request
        $email = $request->input('email');

        // Find the user by email
        $user = User::where('email', $email)->first();

        if ($user) {
            // Perform the deletion
            $user->delete();

            return response()->json(['message' => 'User account deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'User not found.'], 404);
        }
    }
}
