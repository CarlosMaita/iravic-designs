<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\CreditRequest;
use App\Models\Credit;
use App\Repositories\Eloquent\CreditRepository;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CreditController extends Controller
{
    private $creditRepository;
    public function __construct( CreditRepository $creditRepository) 
    {
        $this->creditRepository = $creditRepository;
    }
    public function index( Request $request )
    {
        if ($request->ajax()) {
            $credits = $this->creditRepository->queryWithCustomer();
            return DataTables::of($credits)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';
                        if( auth()->user()->can('view', $row) )
                            $btn .= '<a href="'. route('creditos.show', $row).'" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        if( auth()->user()->can('update', $row) )
                            $btn .= '<a href="'. route('creditos.edit', $row).'" class="btn btn-sm btn-warning btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        if( auth()->user()->can('delete', $row) )
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger btn-action-icon delete-collection" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
        }

        return view('dashboard.credits.index');
    }

    public function show( $credit_id ){
        $credit = $this->creditRepository->find($credit_id);
        $credit->load('order.customer');
        $collections =  $credit->collections;
        return view('dashboard.credits.show', compact('credit' , 'collections'));
    }

    public function edit( $credit_id ){
        $credit = $this->creditRepository->find($credit_id);
        $credit->load('order.customer');
        return view('dashboard.credits.edit', compact('credit'));
    }

    public function update( CreditRequest $request, $credit_id ){
       
        $credit = $this->creditRepository->find($credit_id);
        $credit->update($request->all());
        $collection_frequency = $credit->customer->collection_frequency;
        $collections = $credit->collections;
        $quotas_completed = $collections->where('is_completed', 1)->count();

        // Delete all visits that are not completed 
        foreach ($collections as $collection) {
            $collection->where('is_completed', 0)
                ->delete();
        }
        // dd($credit);
        // create new visits from collection
        $this->creditRepository
            ->createCollections(
                $request->input('start_date'),
                $request->input('amount_quotas'),
                $collection_frequency,
                $request->input('quota'),
                $credit->id,
                $quotas_completed
            );

        return redirect()->route('creditos.index');
    }

        /**
         * Elimina un cobro de la base de datos
         * 
         * @param int $credit_id El id del cobro a eliminar
         * 
         * @return \Illuminate\Http\JsonResponse
         */
    public function destroy( $credit_id ){
        try{
            $collection = $this->creditRepository->find($credit_id);
            $collection->delete();
    
            return response()->json([
                'success' => true,
                'message' => "El Credito ha sido eliminado con éxito, junto con todo los cobros asociados",
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
