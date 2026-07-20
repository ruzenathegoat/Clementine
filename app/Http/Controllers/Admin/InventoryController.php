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
        $query = \App\Models\Product::with(['primaryImage', 'collection']);
        
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', '%' . $search . '%')
                  ->orWhere('slug', 'ilike', '%' . $search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $collections = \App\Models\Collection::orderBy('name')->get();

        // ---------------------------------------------------------
        // Business Intelligence (BI) Data for Inventory
        // ---------------------------------------------------------
        $validStatuses = ['processing', 'shipped', 'completed'];

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
