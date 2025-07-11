<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Menampilkan halaman rekomendasi untuk user
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user) {
            // Jika tidak login, tampilkan produk populer
            $recommendations = $this->recommendationService->getPopularProducts(12);
            $recommendationType = 'popular';
        } else {
            // Jika login, tampilkan rekomendasi personal
            $recommendations = $this->recommendationService->getPersonalizedRecommendations($user->_id, 12);
            $recommendationType = 'personalized';
            
            // Jika tidak ada rekomendasi personal, gunakan produk populer
            if ($recommendations->isEmpty()) {
                $recommendations = $this->recommendationService->getPopularProducts(12);
                $recommendationType = 'popular';
            }
        }

        return view('recommendations', compact('recommendations', 'recommendationType'));
    }

    /**
     * Track aktivitas pengguna (view, like, cart)
     */
    public function trackActivity(Request $request)
    {
        try {
            \Log::info('trackActivity payload', $request->all());
            if (!$request->has('product_id')) {
                return response()->json(['success' => false, 'message' => 'Product ID is required'], 400);
            }
            $request->validate([
                'product_id' => 'required',
                'activity_type' => 'required|in:view,like,cart,check_like'
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            if ($request->activity_type === 'check_like') {
                // Check like status
                $likedProducts = $user->liked_products ?? [];
                $isLiked = in_array($request->product_id, $likedProducts);
                
                return response()->json([
                    'success' => true,
                    'is_liked' => $isLiked,
                    'product_id' => $request->product_id
                ]);
            }

            $result = $this->recommendationService->trackActivity(
                $user->_id,
                $request->product_id,
                $request->activity_type
            );

            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Error tracking activity: ' . $e->getMessage());
            return response()->json(['message' => 'Error tracking activity: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mendapatkan rekomendasi berdasarkan kategori
     */
    public function getByCategory($category)
    {
        $products = $this->recommendationService->getProductsByCategory($category, 12);
        
        return view('katalog', compact('products'))->with('category', $category);
    }

    /**
     * API untuk mendapatkan rekomendasi (untuk AJAX)
     */
    public function getRecommendations(Request $request)
    {
        $user = auth()->user();
        $limit = $request->get('limit', 8);
        
        if (!$user) {
            $recommendations = $this->recommendationService->getPopularProducts($limit);
        } else {
            $recommendations = $this->recommendationService->getPersonalizedRecommendations($user->_id, $limit);
            
            if ($recommendations->isEmpty()) {
                $recommendations = $this->recommendationService->getPopularProducts($limit);
            }
        }

        return response()->json($recommendations);
    }
} 