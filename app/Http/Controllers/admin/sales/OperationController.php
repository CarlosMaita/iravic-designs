<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\OperationRepository;
use DataTables;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    public $operationRepository;

    public function __construct(OperationRepository $operationRepository)
    {
        $this->operationRepository = $operationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $operations = $this->operationRepository->allByCustomer(array('customer' => $request->customer));
            
            return Datatables::of($operations)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';
                        
                        // if (Auth::user()->can('view', $row)) {
                            // '. route('ventas.show', $row->id) . '
                            $btn .= '<a href="" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        // }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        abort(404);
    }
}
