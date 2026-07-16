<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered;
use Exception;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists by google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // Log them in if found
                Auth::login($user);
                $request = request();
                $request->session()->regenerate();
                
                if ($user->role !== 'customer') {
                    return redirect()->intended(route('admin.dashboard'))->with('success', 'SUCCESSFULLY LOGGED IN AS ADMIN.');
                }
                return redirect()->intended(route('home'))->with('success', 'SUCCESSFULLY LOGGED IN WITH GOOGLE.');
            }

            // If not found by google_id, check by email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Link the google_id to the existing account
                $user->update([
                    'google_id' => $googleUser->id,
                ]);
                Auth::login($user);
                $request = request();
                $request->session()->regenerate();
                
                if ($user->role !== 'customer') {
                    return redirect()->intended(route('admin.dashboard'))->with('success', 'SUCCESSFULLY LOGGED IN AS ADMIN.');
                }
                return redirect()->intended(route('home'))->with('success', 'SUCCESSFULLY LOGGED IN WITH GOOGLE.');
            }

            // If completely new, register them
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => null, // Password is null for OAuth users
                'role' => 'customer',
            ]);

            Auth::login($newUser);
            $request = request();
            $request->session()->regenerate();
            
            event(new Registered($newUser));

            return redirect()->route('verification.notice')->with('success', 'REGISTRATION SUCCESSFUL. PLEASE VERIFY YOUR EMAIL.');

        } catch (Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google Login failed. Please try again.']);
        }
    }
}
