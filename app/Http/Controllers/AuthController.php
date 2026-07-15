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

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->role !== 'customer') {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'SUCCESSFULLY LOGGED IN AS ADMIN.');
            }
            
            return redirect()->intended(route('home'))->with('success', 'SUCCESSFULLY LOGGED IN.');
        }

        RateLimiter::hit($throttleKey, 300);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
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

        Auth::login($user);
        
        event(new Registered($user));

        return redirect()->route('verification.notice')->with('success', 'REGISTRATION SUCCESSFUL. PLEASE VERIFY YOUR EMAIL.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'SUCCESSFULLY LOGGED OUT.');
    }
}
