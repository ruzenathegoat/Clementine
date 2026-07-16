<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of all collections.
     */
    public function index()
    {
        $collections = Collection::orderBy('name')->get();
        return view('collections.index', compact('collections'));
    }

    /**
     * Display a specific collection and its products.
     */
    public function show($slug)
    {
        $collection = Collection::with(['products' => function($query) {
            // Include relevant statuses
            $query->whereIn('status', ['active', 'new', 'limited_edition', 'sold_out'])
                  ->with('primaryImage')
                  ->orderBy('created_at', 'desc');
        }])->where('slug', $slug)->firstOrFail();

        return view('collections.show', compact('collection'));
    }
}
