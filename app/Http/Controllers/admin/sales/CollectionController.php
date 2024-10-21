<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\CollectionRequest;
use App\Models\Collection;
use App\Repositories\Eloquent\CollectionRepository;
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

                        $btn .= '<a href="'. route('cobros.edit', $row).'" class="btn btn-sm btn-warning btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.collections.index');
    }

    public function show( Collection $collection ){

        return view('dashboard.collections.show', compact('collection'));
    }

    public function edit( $collection_id ){
        $collection = $this->collectionRepository->find($collection_id);
        $collection->load('order.customer');
        return view('dashboard.collections.edit', compact('collection'));
    }

    public function update( CollectionRequest $request, $collection_id ){
        $collection = $this->collectionRepository->find($collection_id);
        $collection->update($request->all());
        return redirect()
            ->route('cobros.index');
    }
    
}
