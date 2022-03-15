<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\OperationRepository;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationController extends Controller
{
    public $customerRepository;

    public $operationRepository;

    public function __construct(CustomerRepository $customerRepository, OperationRepository $operationRepository)
    {
        $this->customerRepository = $customerRepository;
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
                        
                        $resource = $row->getResourceForPolicy();
                        $route = $row->getResourceRoute();

                        if ($route && Auth::user()->can('view', $resource)) {
                            $btn .= '<a href="' . $route . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        abort(404);
    }

    /**
     * 
     */
    public function download(Request $request)
    {
        try {
            $customer = $this->customerRepository->find($request->customer);
            $now = now()->format('d-m-Y');
            $operations = $this->operationRepository->allByCustomer(array('customer' => $request->customer));

            $pdf = \PDF::loadView('pdf.account_status', [
                'customer' => $customer,
                'operations' => $operations,
                'date' => $now
            ]);

            return $pdf->download($customer->name . '-estado-de-cuenta-' . $now . '.pdf');
        } catch (\Throwable $th) {
            flash("Ha ocurrido un error al tratar de descargar el estado de cuenta.")->error();
            return back();
        }
    }
}
