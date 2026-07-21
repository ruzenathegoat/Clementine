<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validStatuses = ['processing', 'shipped', 'completed'];

        // Subquery for velocity
        $velocityQuery = \App\Models\OrderItem::select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as velocity'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->whereIn('orders.status', $validStatuses)
            ->groupBy('product_id');

        $query = \App\Models\Product::with(['primaryImage', 'collection'])
            ->leftJoinSub($velocityQuery, 'v', function ($join) {
                $join->on('products.id', '=', 'v.product_id');
            })
            ->select('products.*', \Illuminate\Support\Facades\DB::raw('COALESCE(v.velocity, 0) as velocity'))
            ->selectRaw('((products.price - products.cogs) / NULLIF(products.price, 0) * 100) as profit_margin');
        
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('products.name', 'ilike', '%' . $search . '%')
                  ->orWhere('products.slug', 'ilike', '%' . $search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('products.status', $request->status);
        }
        
        // Get global min/max for SMART calculation
        $statsQuery = clone $query;
        // override order, limits, columns and eager loads for aggregation
        $statsQuery->getQuery()->orders = null; 
        $statsQuery->getQuery()->columns = null;
        $statsQuery->setEagerLoads([]);
        
        $stats = $statsQuery->selectRaw('
            MIN(products.stock) as min_stock, MAX(products.stock) as max_stock,
            MIN(COALESCE(v.velocity, 0)) as min_velocity, MAX(COALESCE(v.velocity, 0)) as max_velocity,
            MIN(((products.price - products.cogs) / NULLIF(products.price, 0) * 100)) as min_margin,
            MAX(((products.price - products.cogs) / NULLIF(products.price, 0) * 100)) as max_margin
        ')->first();

        $products = $query->orderBy('products.created_at', 'desc')->paginate(15)->withQueryString();

        // Calculate SMART score for each paginated product
        foreach ($products as $product) {
            $c1_max = $stats->max_stock ?? 0;
            $c1_min = $stats->min_stock ?? 0;
            $c1_out = $product->stock;
            // Cost criteria: lower stock -> higher score
            $u1 = ($c1_max - $c1_min != 0) ? ($c1_max - $c1_out) / ($c1_max - $c1_min) : 0;

            $c2_max = $stats->max_velocity ?? 0;
            $c2_min = $stats->min_velocity ?? 0;
            $c2_out = $product->velocity;
            // Benefit criteria: higher velocity -> higher score
            $u2 = ($c2_max - $c2_min != 0) ? ($c2_out - $c2_min) / ($c2_max - $c2_min) : 0;

            $c3_max = $stats->max_margin ?? 0;
            $c3_min = $stats->min_margin ?? 0;
            $c3_out = $product->profit_margin;
            // Benefit criteria: higher margin -> higher score
            $u3 = ($c3_max - $c3_min != 0) ? ($c3_out - $c3_min) / ($c3_max - $c3_min) : 0;

            $score = ($u1 * 45) + ($u2 * 40) + ($u3 * 15);
            $product->smart_score = round($score, 2);

            if ($score > 75) {
                $product->restock_priority = 'High';
            } elseif ($score >= 40) {
                $product->restock_priority = 'Medium';
            } else {
                $product->restock_priority = 'Low';
            }
        }

        $collections = \App\Models\Collection::orderBy('name')->get();

        // ---------------------------------------------------------
        // Business Intelligence (BI) Data for Inventory
        // ---------------------------------------------------------

        // 1. Top Products
        $topProducts = \App\Models\OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $validStatuses)
            ->select('products.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // 2. Simple Stock Prediction
        $stockPrediction = \App\Models\Product::select('products.name', 'products.stock')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->whereIn('orders.status', $validStatuses)
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as predicted_demand')
            ->groupBy('products.id', 'products.name', 'products.stock')
            ->orderByDesc('predicted_demand')
            ->limit(10)
            ->get();

        $biData = [
            'top_products' => [
                'categories' => $topProducts->pluck('name')->toArray(),
                'data' => $topProducts->pluck('total_sold')->map(fn($v) => (int) $v)->toArray()
            ],
            'stock_prediction' => [
                'categories' => $stockPrediction->pluck('name')->toArray(),
                'stock' => $stockPrediction->pluck('stock')->map(fn($v) => (int) $v)->toArray(),
                'predicted' => $stockPrediction->pluck('predicted_demand')->map(fn($v) => (int) $v)->toArray()
            ]
        ];
        
        return view('admin.inventory.index', compact('products', 'collections', 'biData'));
    }

    public function create()
    {
        abort(404); // Creation logic outside scope of quick-edit PRD
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'collection_id' => 'nullable|exists:collections,id',
            'price' => 'required|numeric|min:0',
            'cogs' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,new,limited_edition,sold_out',
            'gender' => 'required|string|in:men,women,unisex',
            'scheduled_publish_at' => 'nullable|date',
            'diameter_mm' => 'nullable|numeric',
            'movement' => 'nullable|string',
            'case_material' => 'nullable|string',
            'material' => 'nullable|string',
            'water_resistance' => 'nullable|string',
            'crystal' => 'nullable|string',
            'warranty_years' => 'nullable|integer',
            'primary_image' => 'nullable|image|max:2048',
        ]);

        $slug = \Illuminate\Support\Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 1;
        while (\App\Models\Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $validated['slug'] = $slug;

        $product = \App\Models\Product::create($validated);

        if ($request->hasFile('primary_image')) {
            $disk = config('filesystems.default');
            $path = $request->file('primary_image')->store('products', $disk);
            \App\Models\ProductMedia::create([
                'product_id' => $product->id,
                'url' => \Illuminate\Support\Facades\Storage::disk($disk)->url($path),
                'type' => 'image',
                'sort_order' => 1
            ]);
        }

        return redirect()->route('admin.inventory.index')->with('success', 'Product created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('admin.inventory.edit', $id);
    }

    public function edit(string $id)
    {
        $product = \App\Models\Product::with(['primaryImage', 'collection'])->findOrFail($id);
        $collections = \App\Models\Collection::orderBy('name')->get();
        return view('admin.inventory.edit', compact('product', 'collections'));
    }

    public function update(Request $request, string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        $validated = $request->validate([
            'collection_id' => 'nullable|exists:collections,id',
            'price' => 'required|numeric|min:0',
            'cogs' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,new,limited_edition,sold_out',
            'scheduled_publish_at' => 'nullable|date',
            'gender' => 'required|string|in:men,women,unisex',
            'diameter_mm' => 'nullable|numeric',
            'movement' => 'nullable|string',
            'case_material' => 'nullable|string',
            'material' => 'nullable|string',
            'water_resistance' => 'nullable|string',
            'crystal' => 'nullable|string',
            'warranty_years' => 'nullable|integer',
            'primary_image' => 'nullable|image|max:2048',
        ]);

        $product->update($validated);

        if ($request->hasFile('primary_image')) {
            $disk = config('filesystems.default');
            $path = $request->file('primary_image')->store('products', $disk);
            $url = \Illuminate\Support\Facades\Storage::disk($disk)->url($path);
            
            // Delete old primary image if exists (optional cleanup)
            // if ($product->primaryImage) { ... }
            
            \App\Models\ProductMedia::updateOrCreate(
                ['product_id' => $product->id, 'sort_order' => 1],
                ['url' => $url, 'type' => 'image']
            );
        }

        return redirect()->route('admin.inventory.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->update(['status' => 'sold_out']);
        
        return redirect()->route('admin.inventory.index')->with('success', 'Product marked as sold out.');
    }
}
