<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\CollectionRequest;
use App\Models\Collection;
use App\Repositories\Eloquent\CollectionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CollectionController extends Controller
{
    private $collectionRepository;
    public function __construct( CollectionRepository $collectionRepository) 
    {
        $this->collectionRepository = $collectionRepository;
    }
    public function index( Request $request )
    {
        if ($request->ajax()) {
            $collections = $this->collectionRepository->all();
            return DataTables::of($collections)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        $btn .= '<a href="'. route('cobros.show', $row).'" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        $btn .= '<a href="'. route('cobros.edit', $row).'" class="btn btn-sm btn-warning btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger btn-action-icon delete-collection" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.collections.index');
    }

    public function show( $collection_id ){
        $collection = $this->collectionRepository->find($collection_id);
        $collection->load('order.customer');
        $visits =  $collection->order->visits;
        return view('dashboard.collections.show', compact('collection' , 'visits'));
    }

    public function edit( $collection_id ){
        $collection = $this->collectionRepository->find($collection_id);
        $collection->load('order.customer');
        return view('dashboard.collections.edit', compact('collection'));
    }

    public function update( CollectionRequest $request, $collection_id ){
        $collection = $this->collectionRepository->find($collection_id);
        $collection->update($request->all());
        return redirect()->route('cobros.index');
    }

        /**
         * Elimina un cobro de la base de datos
         * 
         * @param int $collection_id El id del cobro a eliminar
         * 
         * @return \Illuminate\Http\JsonResponse
         */
    public function destroy( $collection_id ){
        try{
            $collection = $this->collectionRepository->find($collection_id);
            $collection->delete();
            return response()->json([
                'success' => true,
                'message' => "El Cobro ha sido eliminado con éxito",
                'collection' => $collection->fresh()
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
}
