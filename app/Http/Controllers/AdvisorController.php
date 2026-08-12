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
            'budget' => 'required|numeric|min:1',
            'gender' => 'nullable|string|in:men,women,unisex',
            'material' => 'nullable|string',
            'movement' => 'nullable|string',
            'strap' => 'nullable|string',
        ]);

        $budget = $request->input('budget');
        $gender = $request->input('gender');
        $material = $request->input('material');
        $movement = $request->input('movement');
        $strap = $request->input('strap');

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
                'strap' => $strap,
            ]
        ]);

        // 1. Ambil semua produk aktif dan tersedia
        $eligibleProducts = Product::with(['straps', 'collection'])
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->where('price', '>', 0)
            ->get();

        if ($eligibleProducts->isEmpty()) {
            $recommendations = collect();
            return view('advisor.results', compact('recommendations', 'budget'));
        }

        // 2. Tentukan bobot dasar
        $baseWeights = [
            'budget' => 6/19,
            'gender' => 4/19,
            'material' => 3/19,
            'movement' => 3/19,
            'strap' => 3/19,
        ];

        // 3. Tentukan kriteria aktif dan total bobot
        $activeCriteria = ['budget'];
        if (!empty($gender)) $activeCriteria[] = 'gender';
        if (!empty($material)) $activeCriteria[] = 'material';
        if (!empty($movement)) $activeCriteria[] = 'movement';
        if (!empty($strap)) $activeCriteria[] = 'strap';

        $totalActiveWeight = 0;
        foreach ($activeCriteria as $criterion) {
            $totalActiveWeight += $baseWeights[$criterion];
        }

        // 4. Normalisasi bobot dinamis
        $dynamicWeights = [];
        foreach ($activeCriteria as $criterion) {
            $dynamicWeights[$criterion] = $baseWeights[$criterion] / $totalActiveWeight;
        }

        // Helper untuk gender utility
        $getGenderUtility = function($productGender, $userGender) {
            $productGender = strtolower($productGender);
            $userGender = strtolower($userGender);
            if ($userGender === 'men') {
                return in_array($productGender, ['men', 'unisex']) ? 1 : 0;
            } elseif ($userGender === 'women') {
                return in_array($productGender, ['women', 'unisex']) ? 1 : 0;
            } elseif ($userGender === 'unisex') {
                return $productGender === 'unisex' ? 1 : 0;
            }
            return 0;
        };

        // Fungsi perhitungan skor untuk kandidat
        $calculateScore = function($product, $isFallback = false) use (
            $budget, $gender, $material, $movement, $strap,
            $activeCriteria, $dynamicWeights, $getGenderUtility
        ) {
            $utilities = [];
            $matchedCriteria = [];
            $unmatchedCriteria = [];

            // Budget utility
            if ($isFallback) {
                $t = 0.2; // 20% tolerance
                $utilityBudget = max(0, 1 - (($product->price - $budget) / ($t * $budget)));
            } else {
                $utilityBudget = $product->price / $budget;
            }
            $utilities['budget'] = $utilityBudget;

            // Gender utility
            if (in_array('gender', $activeCriteria)) {
                $u = $getGenderUtility($product->gender, $gender);
                $utilities['gender'] = $u;
                if ($u > 0) $matchedCriteria[] = 'gender';
                else $unmatchedCriteria[] = 'gender';
            }

            // Material utility
            if (in_array('material', $activeCriteria)) {
                $u = (stripos($product->material ?? '', $material) !== false || stripos($product->case_material ?? '', $material) !== false) ? 1 : 0;
                $utilities['material'] = $u;
                if ($u > 0) $matchedCriteria[] = 'material';
                else $unmatchedCriteria[] = 'material';
            }

            // Movement utility
            if (in_array('movement', $activeCriteria)) {
                $u = (stripos($product->movement ?? '', $movement) !== false) ? 1 : 0;
                $utilities['movement'] = $u;
                if ($u > 0) $matchedCriteria[] = 'movement';
                else $unmatchedCriteria[] = 'movement';
            }

            // Strap utility
            if (in_array('strap', $activeCriteria)) {
                $hasStrap = $product->straps->contains(function($s) use ($strap) {
                    return stripos($s->strap_name ?? '', $strap) !== false;
                });
                $u = $hasStrap ? 1 : 0;
                $utilities['strap'] = $u;
                if ($u > 0) $matchedCriteria[] = 'strap';
                else $unmatchedCriteria[] = 'strap';
            }

            $score = 0;
            $weightedContributions = [];
            foreach ($activeCriteria as $criterion) {
                $contribution = $dynamicWeights[$criterion] * $utilities[$criterion];
                $weightedContributions[$criterion] = $contribution;
                $score += $contribution;
            }

            $product->smart_score = round(100 * $score, 2);
            $product->match_percentage = round(100 * $score); // for frontend display
            $product->is_fallback = $isFallback;
            $product->matched_criteria = $matchedCriteria;
            $product->unmatched_criteria = $unmatchedCriteria;
            $product->utilities = $utilities;
            $product->weighted_contributions = $weightedContributions;
            
            // Atribut pengurutan tambahan
            $product->matched_count = count($matchedCriteria);
            $product->price_gap = abs($product->price - $budget);

            return $product;
        };

        // 5. Hitung kandidat utama
        $mainCandidates = $eligibleProducts->filter(function($p) use ($budget) {
            return $p->price <= $budget;
        })->map(function($p) use ($calculateScore) {
            return $calculateScore($p, false);
        });

        // 6. Urutkan kandidat utama
        $sortedMain = $mainCandidates->sort(function($a, $b) {
            if ($a->smart_score != $b->smart_score) return $b->smart_score <=> $a->smart_score; // desc
            if ($a->matched_count != $b->matched_count) return $b->matched_count <=> $a->matched_count; // desc
            if ($a->price_gap != $b->price_gap) return $a->price_gap <=> $b->price_gap; // asc
            // skip sales count for now, use id as stable tie-breaker
            return $a->id <=> $b->id; // asc
        })->values();

        $recommendations = $sortedMain->take(3);

        // 7. Fallback jika kurang dari 3
        if ($recommendations->count() < 3) {
            $t = 0.2; // toleransi 20%
            $maxPrice = $budget * (1 + $t);

            $fallbackCandidates = $eligibleProducts->filter(function($p) use ($budget, $maxPrice) {
                return $p->price > $budget && $p->price <= $maxPrice;
            })->map(function($p) use ($calculateScore) {
                return $calculateScore($p, true);
            });

            $sortedFallback = $fallbackCandidates->sort(function($a, $b) {
                if ($a->smart_score != $b->smart_score) return $b->smart_score <=> $a->smart_score;
                if ($a->matched_count != $b->matched_count) return $b->matched_count <=> $a->matched_count;
                if ($a->price_gap != $b->price_gap) return $a->price_gap <=> $b->price_gap;
                return $a->id <=> $b->id;
            })->values();

            $needed = 3 - $recommendations->count();
            $additional = $sortedFallback->take($needed);
            $recommendations = $recommendations->concat($additional)->values();
        }

        return view('advisor.results', compact('recommendations', 'budget'));
    }
}
