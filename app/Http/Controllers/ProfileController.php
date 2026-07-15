<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Fetch orders and group them
        $orders = $user->orders()->with(['items.product.primaryImage', 'items.product.collection'])->orderBy('created_at', 'desc')->get();
        
        $activeOrders = $orders->filter(function ($order) {
            return in_array($order->status, ['pending', 'processing', 'shipped']);
        });
        
        $pastOrders = $orders->filter(function ($order) {
            return in_array($order->status, ['completed', 'cancelled']);
        });

        return view('profile.index', compact('user', 'orders', 'activeOrders', 'pastOrders'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // Max 2MB, strict mimes
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $disk = config('filesystems.default');
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk($disk)->delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', $disk);
            $user->avatar = $path;
        }

        $user->save();

        return back()->with('success', 'PROFILE UPDATED SUCCESSFULLY.');
    }
}
