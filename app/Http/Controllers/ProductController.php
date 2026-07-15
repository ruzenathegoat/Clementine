<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['primaryImage', 'collection'])
            ->whereIn('status', ['active', 'limited_edition'])
            ->where(function ($q) {
                $q->where('stock', '>', 0)
                  ->orWhere('updated_at', '>=', now()->subDays(7));
            });

        if ($request->filled('collection')) {
            $query->whereHas('collection', fn ($q) => $q->where('slug', $request->string('collection')));
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->string('gender'));
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->price_max);
        }

        if ($request->filled('material')) {
            $query->whereIn('material', (array) $request->material);
        }

        if ($request->filled('movement')) {
            $query->whereIn('movement', (array) $request->movement);
        }

        if ($request->filled('diameter')) {
            $query->whereIn('diameter_mm', (array) $request->diameter);
        }

        $products = $query
            ->orderByRaw("CASE WHEN stock <= 0 OR status = 'sold_out' THEN 1 ELSE 0 END ASC")
            ->orderByDesc('created_at')
            ->get();

        // Filter option sources — pulled live from DB, never hardcoded, so they never drift from real catalog data.
        $collections = Collection::orderBy('name')->get();
        $materials = Product::whereNotNull('material')->distinct()->orderBy('material')->pluck('material');
        $movements = Product::whereNotNull('movement')->distinct()->orderBy('movement')->pluck('movement');
        $diameters = Product::whereNotNull('diameter_mm')->distinct()->orderBy('diameter_mm')->pluck('diameter_mm');
        $priceBounds = Product::selectRaw('min(price) as min_price, max(price) as max_price')->first();

        // Precompute hrefs for single-select pill filters (gender, collection) so the view stays logic-free.
        $buildHref = function (array $overrides) use ($request) {
            $params = array_filter(
                array_merge($request->query(), $overrides),
                fn ($v) => $v !== null && $v !== ''
            );
            $qs = http_build_query($params);
            return route('products.index') . ($qs ? "?{$qs}" : '');
        };

        $genderOptions = [
            ['label' => 'ALL', 'active' => !$request->filled('gender'), 'href' => $buildHref(['gender' => null])],
            ['label' => 'MEN', 'active' => $request->gender === 'men', 'href' => $buildHref(['gender' => 'men'])],
            ['label' => 'WOMEN', 'active' => $request->gender === 'women', 'href' => $buildHref(['gender' => 'women'])],
            ['label' => 'UNISEX', 'active' => $request->gender === 'unisex', 'href' => $buildHref(['gender' => 'unisex'])],
        ];

        $collectionOptions = collect([
            (object) ['label' => 'ALL', 'slug' => null],
        ])->merge($collections->map(fn ($c) => (object) ['label' => strtoupper($c->name), 'slug' => $c->slug]))
          ->map(fn ($c) => [
              'label' => $c->label,
              'active' => $request->collection === $c->slug || (!$request->filled('collection') && $c->slug === null),
              'href' => $buildHref(['collection' => $c->slug]),
          ]);

        return view('products.index', [
            'products' => $products,
            'materials' => $materials,
            'movements' => $movements,
            'diameters' => $diameters,
            'priceBounds' => $priceBounds,
            'genderOptions' => $genderOptions,
            'collectionOptions' => $collectionOptions,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::with(['media', 'straps', 'collection'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}