<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class TwitterAuthController extends Controller
{
    /**
     * Redirect the user to the Twitter authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('twitter-oauth-2')->redirect();
    }

    /**
     * Obtain the user information from Twitter.
     */
    public function callback()
    {
        try {
            $twitterUser = Socialite::driver('twitter-oauth-2')->user();
            
            // Check if user exists with this twitter_id
            $user = User::where('twitter_id', $twitterUser->getId())->first();

            if ($user) {
                Auth::login($user);
                return redirect()->intended('/dashboard');
            }

            // Check if user exists with this email
            if ($twitterUser->getEmail()) {
                $user = User::where('email', $twitterUser->getEmail())->first();
                if ($user) {
                    $user->update(['twitter_id' => $twitterUser->getId()]);
                    Auth::login($user);
                    return redirect()->intended('/dashboard');
                }
            }

            // Create new user if neither twitter_id nor email matches
            $newUser = User::create([
                'name' => $twitterUser->getName() ?? $twitterUser->getNickname(),
                'email' => $twitterUser->getEmail(),
                'twitter_id' => $twitterUser->getId(),
                'password' => null, // OAuth users don't have a password
                // Set default role if you have one, or handle it via observers/defaults
            ]);

            Auth::login($newUser);
            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['twitter' => 'Gagal login menggunakan Twitter/X. Silakan coba lagi.']);
        }
    }
}
