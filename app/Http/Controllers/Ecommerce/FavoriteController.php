<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\CustomerFavorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Display customer's favorites list
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $favorites = $customer->favoriteProducts()
            ->with(['category', 'brand', 'color', 'size', 'images'])
            ->paginate(12);

        return view('ecommerce.favorites.index', compact('favorites'));
    }

    /**
     * Add product to favorites
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $customer = Auth::guard('customer')->user();
        $productId = $request->product_id;

        // Check if already in favorites
        $exists = CustomerFavorite::where('customer_id', $customer->id)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'El producto ya está en tus favoritos'
            ]);
        }

        CustomerFavorite::create([
            'customer_id' => $customer->id,
            'product_id' => $productId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado a favoritos'
        ]);
    }

    /**
     * Remove product from favorites
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $customer = Auth::guard('customer')->user();
        $productId = $request->product_id;

        $deleted = CustomerFavorite::where('customer_id', $customer->id)
            ->where('product_id', $productId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Producto removido de favoritos'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'El producto no estaba en tus favoritos'
        ]);
    }

    /**
     * Toggle favorite status
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $customer = Auth::guard('customer')->user();
        $productId = $request->product_id;

        $favorite = CustomerFavorite::where('customer_id', $customer->id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'is_favorite' => false,
                'message' => 'Producto removido de favoritos'
            ]);
        } else {
            CustomerFavorite::create([
                'customer_id' => $customer->id,
                'product_id' => $productId
            ]);
            return response()->json([
                'success' => true,
                'is_favorite' => true,
                'message' => 'Producto agregado a favoritos'
            ]);
        }
    }
}
