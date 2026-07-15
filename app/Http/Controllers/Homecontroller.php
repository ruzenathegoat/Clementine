<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $collections = Collection::orderBy('name')->take(4)->get();

        // The Drop Section: Only 'new' status products (Not cached to maintain realtime freshness)
        $theDropRaw = Product::with(['primaryImage', 'collection'])
            ->where('status', 'new')
            ->orderByDesc('scheduled_publish_at')
            ->get();
            
        // Hide from THE DROP if it has been out of stock for more than 5 minutes
        $theDrop = $theDropRaw->filter(function ($product) {
            if ($product->stock <= 0 && $product->updated_at && $product->updated_at->addMinutes(5)->isPast()) {
                return false;
            }
            return true;
        });

        // New Arrivals (Standard Catalog): Active & Limited Edition
        $newArrivals = Product::with(['primaryImage', 'collection'])
            ->whereIn('status', ['active', 'limited_edition'])
            ->where(function ($query) {
                $query->where('stock', '>', 0)
                      ->orWhere('updated_at', '>=', now()->subDays(7));
            })
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('home', compact('collections', 'theDrop', 'newArrivals'));
    }
}