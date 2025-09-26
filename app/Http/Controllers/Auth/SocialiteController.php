<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['msg' => 'Unable to login using ' . ucfirst($provider) . '. Please try again.']);
        }

        $authUser = $this->findOrCreateUser($socialUser, $provider);
        Auth::login($authUser, true);

        return redirect()->intended('/home');
    }

    public function findOrCreateUser($socialUser, $provider)
    {
        $authUser = User::where('provider_id', $socialUser->id)->first();
        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'name' => $socialUser->name,
            'firstname' => $socialUser->name,
            'email' => $socialUser->email,
            'provider' => $provider,
            'provider_id' => $socialUser->id,
            'password' => Hash::make('password'),
            'role_id' => 1,
            'is_active' => 1,
        ]);
    }
}

