<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\User::where('role', 'customer');
        
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', '%' . $search . '%')
                  ->orWhere('email', 'ilike', '%' . $search . '%');
            });
        }
        
        if ($request->has('is_vip') && $request->is_vip !== '') {
            $query->where('is_vip', $request->is_vip == '1');
        }
        
        // Include orders count and sum for quick LTV in the table if we want, but for now just paginate
        $users = $query->withCount('orders')
                       ->orderBy('created_at', 'desc')
                       ->paginate(15)
                       ->withQueryString();
        
        return view('admin.users.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = \App\Models\User::with(['orders' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        $ltv = $user->orders->whereIn('status', ['processing', 'shipped', 'completed'])->sum('total');
        
        return view('admin.users.show', compact('user', 'ltv'));
    }

    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        $validated = $request->validate([
            'is_vip' => 'required|boolean',
        ]);

        $user->update(['is_vip' => $validated['is_vip']]);

        $status = $validated['is_vip'] ? 'VIP granted' : 'VIP revoked';
        return redirect()->route('admin.users.show', $id)->with('success', "Customer status updated: {$status}.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
