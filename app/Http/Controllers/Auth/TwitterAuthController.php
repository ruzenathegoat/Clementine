<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

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
    public function callback(Request $request)
    {
        try {
            $twitterUser = Socialite::driver('twitter-oauth-2')->user();
            
            $avatar = $twitterUser->getAvatar();
            if ($avatar) {
                $avatar = str_replace('_normal', '', $avatar);
            }

            // Check if user exists with this twitter_id
            $user = User::where('twitter_id', $twitterUser->getId())->first();

            if ($user) {
                if ($avatar && (!$user->avatar || filter_var($user->avatar, FILTER_VALIDATE_URL))) {
                    $user->update(['avatar' => $avatar]);
                }
                return AuthController::attemptRbaLogin($user, $request);
            }

            // Check if user exists with this email
            if ($twitterUser->getEmail()) {
                $user = User::where('email', $twitterUser->getEmail())->first();
                if ($user) {
                    $updates = ['twitter_id' => $twitterUser->getId()];
                    if ($avatar && (!$user->avatar || filter_var($user->avatar, FILTER_VALIDATE_URL))) {
                        $updates['avatar'] = $avatar;
                    }
                    $user->update($updates);
                    return AuthController::attemptRbaLogin($user, $request);
                }
            }

            // Create new user if neither twitter_id nor email matches
            $newUser = User::create([
                'name' => $twitterUser->getName() ?? $twitterUser->getNickname(),
                'email' => $twitterUser->getEmail(),
                'twitter_id' => $twitterUser->getId(),
                'password' => null, // OAuth users don't have a password
                'role' => 'customer',
                'avatar' => $avatar,
            ]);

            return AuthController::attemptRbaLogin($newUser, $request);

        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['twitter' => 'Gagal login menggunakan Twitter/X. Silakan coba lagi.']);
        }
    }
}
