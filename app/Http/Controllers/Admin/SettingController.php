<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $currentCurrency = session('admin_currency', 'USD');
        return view('admin.settings.index', compact('currentCurrency'));
    }

    public function updateCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|in:USD,IDR',
        ]);

        session(['admin_currency' => $request->currency]);

        return redirect()->back()->with('success', 'Currency preference updated successfully.');
    }
}
