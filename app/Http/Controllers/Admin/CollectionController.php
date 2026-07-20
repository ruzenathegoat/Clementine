<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::withCount('products')->orderBy('name')->paginate(15);
        
        // ---------------------------------------------------------
        // Business Intelligence (BI) Data for Collections
        // ---------------------------------------------------------
        $validStatuses = ['processing', 'shipped', 'completed'];

        // Top Collections
        $topCollections = \App\Models\OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('collections', 'products.collection_id', '=', 'collections.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $validStatuses)
            ->select('collections.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('collections.id', 'collections.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $biData = [
            'top_collections' => [
                'data' => $topCollections->map(fn($c) => ['name' => $c->name, 'y' => (int)$c->total_sold])->toArray()
            ]
        ];

        return view('admin.collections.index', compact('collections', 'biData'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 1;
        while (Collection::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('image_url')) {
            $disk = config('filesystems.default');
            $path = $request->file('image_url')->store('collections', $disk);
            $validated['image_url'] = Storage::disk($disk)->url($path);
        } else {
            unset($validated['image_url']);
        }

        Collection::create($validated);

        return redirect()->route('admin.collections.index')->with('success', 'Collection created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('admin.collections.edit', $id);
    }

    public function edit(string $id)
    {
        $collection = Collection::findOrFail($id);
        return view('admin.collections.edit', compact('collection'));
    }

    public function update(Request $request, string $id)
    {
        $collection = Collection::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image_url')) {
            $disk = config('filesystems.default');
            $path = $request->file('image_url')->store('collections', $disk);
            $validated['image_url'] = Storage::disk($disk)->url($path);
        } else {
            unset($validated['image_url']);
        }

        $collection->update($validated);

        return redirect()->route('admin.collections.index')->with('success', 'Collection updated successfully.');
    }

    public function destroy(string $id)
    {
        $collection = Collection::findOrFail($id);
        // Products with this collection_id will have it set to null (due to nullOnDelete constraint)
        $collection->delete();
        
        return redirect()->route('admin.collections.index')->with('success', 'Collection deleted successfully.');
    }
}
