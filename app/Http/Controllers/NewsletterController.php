<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        NewsletterSubscriber::firstOrCreate(['email' => $validated['email']]);

        return back()->with('status', 'Terima kasih! Anda sudah terdaftar.');
    }
}