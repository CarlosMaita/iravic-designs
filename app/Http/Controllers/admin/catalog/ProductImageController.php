<?php

namespace App\Http\Controllers\admin\catalog;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Repositories\Eloquent\ProductImageRepository;
use App\Services\Images\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Exception;

class ProductImageController extends Controller
{
    public $productoImageRepository;
    private $filedisk = 'products';

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

    
    public function store ( Request $request){

        $this->authorize('create', 'App\Models\ProductImage');
        
        if(!isset( $request->file )){
            return response()->json([
                'success' => false,
                'message' => 'No hay imagen'
            ]);
        }
        $filenames = $this->saveImages(null, $request);

        return response()->json([
            'success' => true,
            'data' => $filenames
        ]);
    }

    private function saveImages($product = null, $request): array
    {
        $files = $request->file;
        $filesname = array();

        foreach ($files as $file) {
            $url = ImageService::save($this->filedisk, $file);
            if ($url) {
                ProductImage::create([
                    'url' => $url,
                    'temp_code' => $request->input('temp_code'),
                    'combination_index' => intval( $request->input('combination_index')),
                    'url_original' => $file->getClientOriginalName(),
                ]);
                array_push($filesname, [
                    'url' => $url,
                    'temp_code' => $request->input('temp_code'),
                    'combination_index' =>  intval($request->input('combination_index')),
                    'url_original' =>  $file->getClientOriginalName()
                ]);
            }
        }
        
        return $filesname;
    }

    public function update ( Request $request, ProductImage $producto_imagen){
        
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


    public function destroyWithRequest(Request $request){
        try{
            // if exists image_id then delete
            if ($request->has('image_id')) 
            {
                $image_id = $request->input('image_id');
                $productImage = ProductImage::where('id', $image_id)
                ->latest()
                ->first();
            }
            elseif($request->has('fileName') && $request->has('combinationIndex')) 
            {
                $fileName = $request->input('fileName');
                $combinationIndex = $request->input('combinationIndex');
                
                $productImage = ProductImage::where('url_original', $fileName)
                    ->where( 'combination_index' , $combinationIndex)
                    ->latest()
                    ->first();
            }
            else
            {
                return response()->json([
                    'success' => false,
                    'message' => __('dashboard.product_image.image_not_found'),
                ]); 
            }

            $this->authorize('delete', $productImage);

            $productImage->delete();

        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        
    }

    
    
}
