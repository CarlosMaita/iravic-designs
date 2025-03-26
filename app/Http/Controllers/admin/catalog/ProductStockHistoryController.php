<?php

namespace App\Http\Controllers\admin\catalog;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\ProductStockHistoryRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductStockHistoryController extends Controller
{
    public $productStockHistoryRepository;

    public function __construct(ProductStockHistoryRepository $productStockHistoryRepository)
    {
        $this->productStockHistoryRepository = $productStockHistoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Product');
        if ($request->ajax()) {
            $history = isset($request->product) 
                        ? $this->productStockHistoryRepository->all($request->only('product', 'store_id')) 
                        : array();

            return DataTables::of($history)
            ->toJson();
        }

        abort(404);
    }
}
