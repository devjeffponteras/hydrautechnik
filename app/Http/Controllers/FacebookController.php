<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacebookController extends Controller
{
    public function setupChatPlugin()
    {
        $pageId = env('FACEBOOK_PAGE_ID');
        $welcomeText = env('WELCOME_TEXT');
        $themeColor = env('THEME_COLOR');
        $entryPointIcon = env('MESSENGER_ICON');
        $entryPointLabel = env('ENTRY_POINT_LABEL');
        $pageAccessToken = env('PAGE_ACCESS_TOKEN');

        $response = Http::post("https://graph.facebook.com/v20.0/$pageId/chat_plugin", [
            'welcome_screen_greeting' => $welcomeText,
            'theme_color' => $themeColor,
            'entry_point_icon' => $entryPointIcon,
            'entry_point_label' => $entryPointLabel,
            'access_token' => $pageAccessToken,
        ]);

        if ($response->successful()) {
            // Handle success
            return response()->json(['message' => 'Chat plugin setup successful'], 200);
        } else {
            // Handle error
            return response()->json(['message' => 'Chat plugin setup failed', 'error' => $response->body()], $response->status());
        }
    }
}
