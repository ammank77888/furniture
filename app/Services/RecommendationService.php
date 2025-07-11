<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    /**
     * Mendapatkan rekomendasi produk berdasarkan preferensi user
     */
    public function getPersonalizedRecommendations($userId, $limit = 8)
    {
        $user = User::find($userId);
        if (!$user) return collect();

        // 1. Berdasarkan produk yang disukai
        $likedProducts = $this->getRecommendationsFromLikedProducts($user, $limit);
        
        // 2. Berdasarkan riwayat pembelian
        $purchaseHistory = $this->getRecommendationsFromPurchaseHistory($user, $limit);
        
        // 3. Berdasarkan produk yang sering dilihat
        $viewedProducts = $this->getRecommendationsFromViewedProducts($user, $limit);

        // Gabungkan dan urutkan berdasarkan relevansi
        $recommendations = $likedProducts->merge($purchaseHistory)->merge($viewedProducts);
        
        return $recommendations->unique('_id')->take($limit);
    }

    /**
     * Rekomendasi berdasarkan produk yang disukai
     */
    private function getRecommendationsFromLikedProducts($user, $limit)
    {
        if (empty($user->liked_products)) return collect();

        return Product::whereIn('_id', $user->liked_products)
            ->orWhere(function($query) use ($user) {
                foreach ($user->liked_products as $productId) {
                    $product = Product::find($productId);
                    if ($product && $product->category) {
                        $query->orWhere('category', $product->category);
                    }
                }
            })
            ->whereNotIn('_id', $user->liked_products)
            ->limit($limit)
            ->get();
    }

    /**
     * Rekomendasi berdasarkan riwayat pembelian
     */
    private function getRecommendationsFromPurchaseHistory($user, $limit)
    {
        $purchasedProducts = Transaction::where('user_id', $user->_id)
            ->where('status', '!=', 'cancelled')
            ->pluck('items')
            ->flatten()
            ->pluck('product_id')
            ->unique();

        if ($purchasedProducts->isEmpty()) return collect();

        // Cari produk yang sering dibeli bersamaan
        $frequentlyBoughtTogether = $this->getFrequentlyBoughtTogether($purchasedProducts, $limit);

        return $frequentlyBoughtTogether;
    }

    /**
     * Rekomendasi berdasarkan produk yang dilihat
     */
    private function getRecommendationsFromViewedProducts($user, $limit)
    {
        if (empty($user->viewed_products)) return collect();

        return Product::whereIn('_id', $user->viewed_products)
            ->orWhere(function($query) use ($user) {
                foreach ($user->viewed_products as $productId) {
                    $product = Product::find($productId);
                    if ($product && $product->category) {
                        $query->orWhere('category', $product->category);
                    }
                }
            })
            ->whereNotIn('_id', $user->viewed_products)
            ->limit($limit)
            ->get();
    }

    /**
     * Mendapatkan produk yang sering dibeli bersamaan
     */
    private function getFrequentlyBoughtTogether($purchasedProducts, $limit)
    {
        // Cari transaksi yang mengandung produk yang sudah dibeli
        $relatedTransactions = Transaction::where('status', '!=', 'cancelled')
            ->where(function($query) use ($purchasedProducts) {
                foreach ($purchasedProducts as $productId) {
                    $query->orWhere('items.product_id', $productId);
                }
            })
            ->get();

        // Hitung frekuensi produk yang dibeli bersamaan
        $productFrequency = [];
        foreach ($relatedTransactions as $transaction) {
            foreach ($transaction->items as $item) {
                if (!isset($item['product_id'])) continue;
                $productId = $item['product_id'];
                if (!in_array($productId, $purchasedProducts->toArray())) {
                    $productFrequency[$productId] = ($productFrequency[$productId] ?? 0) + 1;
                }
            }
        }

        // Urutkan berdasarkan frekuensi
        arsort($productFrequency);
        $topProductIds = array_slice(array_keys($productFrequency), 0, $limit);

        return Product::whereIn('_id', $topProductIds)->get();
    }

    /**
     * Mendapatkan produk populer
     */
    public function getPopularProducts($limit = 8)
    {
        return Product::orderBy('view_count', 'desc')
            ->orderBy('like_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mendapatkan produk berdasarkan kategori
     */
    public function getProductsByCategory($category, $limit = 8)
    {
        return Product::where('category', $category)
            ->orderBy('view_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Track aktivitas pengguna
     */
    public function trackActivity($userId, $productId, $activityType)
    {
        try {
            if (!$productId) {
                return [
                    'success' => false,
                    'message' => 'Product ID is required'
                ];
            }
            // Simpan aktivitas
            UserActivity::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'activity_type' => $activityType,
                'created_at' => now()
            ]);

            // Update statistik produk
            $product = Product::find($productId);
            if ($product) {
                if ($activityType === 'view') {
                    $product->increment('view_count');
                } elseif ($activityType === 'like') {
                    $product->increment('like_count');
                }
            }

            // Update data user
            $user = User::find($userId);
            if ($user) {
                $this->updateUserData($user, $productId, $activityType);
            }

            return [
                'success' => true,
                'liked' => $activityType === 'like',
                'product_stats' => [
                    'view_count' => $product->view_count ?? 0,
                    'like_count' => $product->like_count ?? 0
                ]
            ];

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update data user berdasarkan aktivitas
     */
    private function updateUserData($user, $productId, $activityType)
    {
        if ($activityType === 'view') {
            $viewedProducts = $user->viewed_products ?? [];
            if (!in_array($productId, $viewedProducts)) {
                $viewedProducts[] = $productId;
                $user->viewed_products = array_slice($viewedProducts, -50); // Simpan 50 terakhir
                $user->save();
            }
        } elseif ($activityType === 'like') {
            $likedProducts = $user->liked_products ?? [];
            if (!in_array($productId, $likedProducts)) {
                $likedProducts[] = $productId;
                $user->liked_products = $likedProducts;
                $user->save();
            }
        }
    }
} 