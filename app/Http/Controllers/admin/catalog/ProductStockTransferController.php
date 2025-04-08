<?php

namespace App\Http\Controllers\admin\catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\Catalog\ProductStockTransferRequest;
use App\Models\ProductStockTransfer;
use App\Repositories\Eloquent\ProductStockTransferRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductStockTransferController extends Controller
{
    public $productStockTransferRepository;

    public function __construct(ProductStockTransferRepository $productStockTransferRepository)
    {
        $this->productStockTransferRepository = $productStockTransferRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\ProductStockTransfer');

        if ($request->ajax()) {
            $transfers = $this->productStockTransferRepository->allQuery();

            return datatables()->eloquent($transfers)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('update', $row) && !$row->is_accepted) {
                            $btn .= '<button data-id="' . $row->id . '" class="btn btn-sm btn-success btn-action-icon btn-accept-transfer" title="Aceptar transferencia" data-toggle="tooltip"><i class="fas fa-check"></i></button>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-transfer" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.catalog.transfers.index');
    }

    public function store(ProductStockTransferRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\ProductStockTransfer');
            DB::beginTransaction();
            $product_stock_transfer = $this->productStockTransferRepository->create($request->only('product_id', 'user_creator_id', 'qty', 'stock_origin', 'stock_destination'));
            DB::commit();

            flash("La transferencia ha sido creada con éxito")->success();
            
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductStockTransfer $stock_transferencia)
    {
        try {
            $this->authorize('update', $stock_transferencia);
            DB::beginTransaction();
            $attributes = array(
                'is_accepted' => 1,
                'user_responsable_id' => Auth::user()->id
            );
            #validar la existencia del producto
            if (empty($stock_transferencia->product)) {
                return response()->json([
                    'success' => false,
                    'message' => "El producto no existe, por favor verifique."
                ]);
            }
            #actualizar la transferencia
            $this->productStockTransferRepository->update($stock_transferencia->id, $attributes);
            DB::commit();

            return response()->json([
                'success' => 'true',
                'message' => "La transferencia ha sido aceptada con éxito"
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('dashboard.general.operation_error'),
                'error' => [
                    'e' => $e->getMessage(),
                    'trace' => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Elimina una solicitud de transferencia de Stock
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductStockTransfer $stock_transferencia)
    {
        try {
            $this->authorize('delete', $stock_transferencia);
            $stock_transferencia->delete();
            
            return response()->json([
                'success' => true,
                'message' => "La solicitud de transferencia ha sido eliminada con éxito"
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => __('dashboard.general.operation_error'),
                'error' => [
                    'e' => $e->getMessage(),
                    'trace' => $e->getMessage()
                ]
            ]);
        }
    }
}
