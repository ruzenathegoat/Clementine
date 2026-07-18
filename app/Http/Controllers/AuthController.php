<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\LoginHistory;
use App\Mail\SuspiciousLoginAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            abort(429, 'Too many login attempts. Please try again in 5 minutes.');
        }

        if (Auth::validate($credentials)) {
            $user = User::where('email', $request->email)->first();
            
            RateLimiter::clear($throttleKey);
            return self::attemptRbaLogin($user, $request, $request->boolean('remember'));
        }

        RateLimiter::hit($throttleKey, 300);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public static function attemptRbaLogin(User $user, Request $request, $remember = false)
    {
        // Risk Scoring
        $agent = new Agent();
        $ip = $request->ip();
        // For local testing, use a public IP if it's localhost
        if ($ip === '127.0.0.1' || $ip === '::1') {
            $ip = '8.8.8.8'; 
        }
        $location = Location::get($ip);
        
        $browser = $agent->browser();
        $platform = $agent->platform();
        $device = $agent->device();
        $country = $location ? $location->countryName : null;
        $city = $location ? $location->cityName : null;

        $lastLogin = LoginHistory::where('user_id', $user->id)
            ->where('is_verified', true)
            ->latest()
            ->first();

        $score = 0;
        if ($lastLogin) {
            if ($lastLogin->device !== $device) $score += 50;
            if ($lastLogin->country !== $country) $score += 50;
            if ($lastLogin->platform !== $platform) $score += 20;
            if ($lastLogin->city !== $city) $score += 20;
            if ($lastLogin->browser !== $browser) $score += 15;
            if ($lastLogin->ip_address !== $ip) $score += 10;
        }

        // Based on scenarios, threshold > 50 triggers verification
        $isSuspicious = $lastLogin ? ($score > 50) : false;

        $history = LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $ip,
            'country' => $country,
            'city' => $city,
            'region' => $location ? $location->regionName : null,
            'latitude' => $location ? $location->latitude : null,
            'longitude' => $location ? $location->longitude : null,
            'user_agent' => $request->userAgent(),
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device,
            'is_verified' => !$isSuspicious,
        ]);

        if ($isSuspicious) {
            $verificationUrl = URL::temporarySignedRoute(
                'login.verify', now()->addMinutes(15), ['history' => $history->id]
            );
            
            Mail::to($user->email)->send(new SuspiciousLoginAlert($history, $user, $verificationUrl));
            
            return redirect()->route('login.verify.notice')->with('error', 'LOGIN BLOCKED: NEW DEVICE OR LOCATION DETECTED. PLEASE CHECK YOUR EMAIL TO VERIFY.');
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();
        
        if ($user->role !== 'customer') {
            return redirect()->intended(route('admin.dashboard'))->with('success', 'SUCCESSFULLY LOGGED IN AS ADMIN.');
        }
        
        return redirect()->intended(route('home'))->with('success', 'SUCCESSFULLY LOGGED IN.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required', 
                'confirmed', 
                'min:8', 
                'max:12', 
                'regex:/^[A-Z]/', // First letter must be capital
                'regex:/[0-9]/', // Must contain a number
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // Must contain special symbol
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);
        
        event(new Registered($user));

        // Create the first LoginHistory by running RBA logic
        // This ensures the device they registered on is trusted.
        self::attemptRbaLogin($user, $request);

        return redirect()->route('verification.notice')->with('success', 'REGISTRATION SUCCESSFUL. PLEASE VERIFY YOUR EMAIL.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'SUCCESSFULLY LOGGED OUT.');
    }

    public function verifyNotice()
    {
        return view('auth.verify-login');
    }

    public function verifySuspiciousLogin(Request $request, LoginHistory $history)
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('login')->with('error', 'VERIFICATION LINK IS INVALID OR EXPIRED. PLEASE LOGIN AGAIN.');
        }

        if ($history->is_verified) {
            return redirect()->route('login')->with('error', 'THIS LOGIN ATTEMPT HAS ALREADY BEEN VERIFIED.');
        }

        $history->update(['is_verified' => true]);
        
        $user = $history->user;
        Auth::login($user);

        $request->session()->regenerate();

        if ($user->role !== 'customer') {
            return redirect()->intended(route('admin.dashboard'))->with('success', 'LOGIN VERIFIED AND SUCCESSFUL.');
        }

        return redirect()->intended(route('home'))->with('success', 'LOGIN VERIFIED AND SUCCESSFUL.');
    }
}
