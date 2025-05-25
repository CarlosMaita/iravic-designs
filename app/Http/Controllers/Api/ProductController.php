<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $paginationLimit = 18;

    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search');
        $categoryId = $request->input('category');
        $brandId = $request->input('brand');
        $gender = $request->input('gender');
        $colorId = $request->input('color');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortingOption = $request->input('sorting');

        $query = Product::query()->where('product_id', null); //solo products principales

        if ($searchTerm) {
            $query->search($searchTerm);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($minPrice && $maxPrice) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($colorId) {
            $query->whereHas('product_combinations', function ($q) use ($colorId) {
                $q->where('color_id', $colorId);
            });
        }

        switch ($sortingOption) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
        }

        $totalProductCount = $query->count();
        $totalPages = ceil($totalProductCount / $this->paginationLimit);
        $products = ProductResource::collection($query->paginate($this->paginationLimit));

        return response()->json([
            'products' => $products,
            'totalProductCount' => $totalProductCount,
            'totalPages' => $totalPages
        ], 200);
    }
}

