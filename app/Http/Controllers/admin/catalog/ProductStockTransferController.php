<?php

namespace App\Http\Controllers\admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\Catalog\ProductStockTransferRequest;
use App\Models\ProductStockTransfer;
use App\Repositories\Eloquent\ProductStockTransferRepository;
use DataTables;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductStockTransferController extends Controller
{
    public $productStockTransferRepository;

    public function __construct(ProductStockTransferRepository $productStockTransferRepository)
    {
        $this->productStockTransferRepository = $productStockTransferRepository;
    }

    public function store(ProductStockTransferRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\ProductStockTransfer');
            DB::beginTransaction();
            $product_stock_transfer = $this->productStockTransferRepository->create($request->only('product_id', 'user_creator_id', 'qty', 'stock_origin', 'stock_destination'));
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'product_stock_transfer' => $product_stock_transfer->load('product')
                ]
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => __('dashboard.general.operation_error'),
                'error' => [
                    'e' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ]
            ]);
        }
    }
}
