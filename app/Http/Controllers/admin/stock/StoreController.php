<?php

namespace App\Http\Controllers\admin\stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\stock\StoreRequest;
use App\Models\StoreType;
use App\Repositories\Eloquent\StoreRepository;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $storeRepository;
    public function __construct( StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $stores = $this->storeRepository->allQuery();
            return datatables()->eloquent($stores)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (auth()->user()->can('update', $row)) {
                            $btn .= '<a href="'. route('depositos.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

                        if (auth()->user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-store" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('dashboard.stock.stores.index');
    }

    public function create()
    {   
        $this->authorize('create', 'App\Models\Store');
        $storeTypeList = StoreType::all();
        return view('dashboard.stock.stores.create', compact('storeTypeList'));
    }

    public function store(StoreRequest $request)
    {
        try{
            
            $this->storeRepository->create($request->only('name', 'store_type_id'));
            flash("La depósito  <b>$request->name</b> ha sido creada con éxito")
                ->success();
            return response()->json([
                'success' => 'true',
                'redirect' => route('depositos.index')
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'message' => __('dashboard.general.operation_error'),
                'error' => [
                    'e' => $e->getMessage(),
                    'trace' => $e->getMessage()
                ]
            ]);
        }
    }

    public function edit($id){
        $store = $this->storeRepository->find($id);
        $this->authorize('update', $store);
        $storeTypeList = StoreType::all();
        return view('dashboard.stock.stores.edit', compact('store', 'storeTypeList'));
    }

    public function update(StoreRequest $request, $id){
        try{
            $this->storeRepository->update($id, $request->only('name', 'store_type_id'));
            flash("La depósito <b>$request->name</b> ha sido actualizada con éxito")
                ->success();
            return response()->json([
                'success' => 'true',
                'redirect' => route('depositos.index')
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'message' => __('dashboard.general.operation_error'),
                'error' => [
                    'e' => $e->getMessage(),
                    'trace' => $e->getMessage()
                ]
            ]);
        }
    }


    public function destroy($id){
        $store = $this->storeRepository->find($id);
        $this->authorize('delete', $store);
        $this->storeRepository->delete($id);
        return response()
        ->json([
            'success' => true, 
            'message' => 'Depósito eliminado correctamente'
        ]);
    }   
    
}
