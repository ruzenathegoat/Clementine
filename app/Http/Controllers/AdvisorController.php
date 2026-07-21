<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\AnalyticsEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdvisorController extends Controller
{
    /**
     * Tampilkan form kuesioner Smart Watch Advisor
     */
    public function index()
    {
        return view('advisor.index');
    }

    /**
     * Proses algoritma SMART dan kembalikan rekomendasi
     */
    public function process(Request $request)
    {
        $request->validate([
            'budget' => 'nullable|numeric|min:0',
            'gender' => 'nullable|string|in:men,women,unisex',
            'material' => 'nullable|string',
            'movement' => 'nullable|string',
        ]);

        $budget = $request->input('budget', 999999999);
        $gender = $request->input('gender');
        $material = $request->input('material');
        $movement = $request->input('movement');

        // Filter awal: Produk aktif dan stok ada
        // Optimasi: Membuang produk yang harganya jauh di atas budget (misal > 2x lipat)
        $products = Product::where('status', 'active')
            ->where('stock', '>', 0)
            ->where('price', '<=', $budget * 2)
            ->get();

        // Track Advisor Search
        AnalyticsEvent::create([
            'user_id' => Auth::id(),
            'session_id' => Session::getId(),
            'event_type' => 'advisor_search',
            'payload' => [
                'budget' => $budget,
                'gender' => $gender,
                'material' => $material,
                'movement' => $movement,
            ]
        ]);

        if ($products->isEmpty()) {
            // Jika benar-benar kosong, ambil top 3 best seller (dummy fallback)
            $recommendations = Product::where('status', 'active')
                ->where('stock', '>', 0)
                ->orderBy('price', 'desc')
                ->take(3)
                ->get();
            return view('advisor.results', compact('recommendations'));
        }

        // Tentukan Max dan Min untuk kriteria Cost dan Benefit kuantitatif
        $maxPrice = $products->max('price') ?: 1;
        $minPrice = $products->min('price') ?: 0;
        $maxStock = $products->max('stock') ?: 1;
        $minStock = $products->min('stock') ?: 0;

        // Bobot (Weights) dalam bentuk desimal (total 1.0)
        $w1_price = 0.35;
        $w2_gender = 0.25;
        $w3_material = 0.20;
        $w4_movement = 0.15;
        $w5_stock = 0.05;

        // Kalkulasi Utility & Final Score menggunakan SMART
        $scoredProducts = $products->map(function ($product) use (
            $budget, $gender, $material, $movement,
            $maxPrice, $minPrice, $maxStock, $minStock,
            $w1_price, $w2_gender, $w3_material, $w4_movement, $w5_stock
        ) {
            // 1. Cost: Price Match (Semakin mendekati budget dari bawah, semakin baik)
            // Normalisasi harga: jika harga > budget, penalti berat.
            if ($product->price > $budget) {
                // Utility turun drastis jika melebihi budget
                $u1 = max(0, ($budget - ($product->price - $budget)) / $maxPrice);
            } else {
                // Normalisasi terbalik: (Max - Harga) / (Max - Min)
                $denominator = ($maxPrice - $minPrice) ?: 1;
                $u1 = ($maxPrice - $product->price) / $denominator;
            }

            // 2. Benefit: Gender Match
            $u2 = 0;
            if ($gender) {
                if (strtolower($product->gender) === strtolower($gender) || strtolower($product->gender) === 'unisex') {
                    $u2 = 1;
                }
            } else {
                $u2 = 1; // Jika tidak pilih, default 1
            }

            // 3. Benefit: Material Match
            $u3 = 0;
            if ($material) {
                if (stripos($product->material, $material) !== false || stripos($product->case_material, $material) !== false) {
                    $u3 = 1;
                }
            } else {
                $u3 = 1;
            }

            // 4. Benefit: Movement Match
            $u4 = 0;
            if ($movement) {
                if (stripos($product->movement, $movement) !== false) {
                    $u4 = 1;
                }
            } else {
                $u4 = 1;
            }

            // 5. Benefit: Stock
            $stockDenominator = ($maxStock - $minStock) ?: 1;
            $u5 = ($product->stock - $minStock) / $stockDenominator;

            // Final Score
            $finalScore = ($u1 * $w1_price) + ($u2 * $w2_gender) + ($u3 * $w3_material) + ($u4 * $w4_movement) + ($u5 * $w5_stock);

            // Menyimpan skor di objek produk sementara
            $product->smart_score = $finalScore;
            $product->match_percentage = round($finalScore * 100);

            return $product;
        });

        // Urutkan berdasarkan score tertinggi
        $scoredProducts = $scoredProducts->sortByDesc('smart_score')->values();

        // Ambil Top 3
        $recommendations = $scoredProducts->take(3);

        // Jika top score terlalu rendah (< 30%), fallback ke best seller termahal sesuai budget
        if ($recommendations->first()->smart_score < 0.3) {
             $recommendations = Product::where('status', 'active')
                ->where('stock', '>', 0)
                ->where('price', '<=', $budget)
                ->orderBy('price', 'desc')
                ->take(3)
                ->get();
             foreach ($recommendations as $rec) {
                 $rec->match_percentage = 99; // Dummy fallback percentage
             }
        }

        return view('advisor.results', compact('recommendations', 'budget'));
    }
}
