<?php

namespace App\Http\Controllers\admin\catalog;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Repositories\Eloquent\ProductImageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Exception;

class ProductImageController extends Controller
{
    public $productoImageRepository;

    public function __construct(ProductImageRepository $productoImageRepository)
    {
        $this->productoImageRepository = $productoImageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\ProductImage');

        if ($request->ajax()) {
            $images = isset($request->producto) ? $this->productoImageRepository->all($request->producto) : array();
            return Datatables::of($images)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger btn-action-icon delete-image" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductImage $producto_imagen)
    {
        try {
            $this->authorize('delete', $producto_imagen);
            $producto_imagen->delete();
            
            return response()->json([
                'success' => true,
                'message' => "La imagen ha sido eliminada con éxito"
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
