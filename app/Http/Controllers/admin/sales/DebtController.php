<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\DebtRequest;
use App\Models\Debt;
use App\Repositories\Eloquent\DebtRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    public $debtRepository;

    public function __construct(DebtRepository $debtRepository)
    {
        $this->debtRepository = $debtRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Debt');

        if ($request->ajax()) {
            if ($request->customer) {
                $debts = $this->debtRepository->all(array('customer' => $request->customer));
            } else if ($request->box) {
                $debts = $this->debtRepository->all(array('box' => $request->box));
            } else {
                $debts = array();
            }
            
            return Datatables::of($debts)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<div style="display:flex">';
                        
                        if (Auth::user()->can('update', $row)) {
                            if(!$row->box->isClosed())
                            {
                                $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-success btn-action-icon edit-debt mb-2" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></button>';
                            }
                        }

                        if (Auth::user()->can('delete', $row)) {
                            if(!$row->box->isClosed())
                            {
                                $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-debt mb-2" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                            }
                        }

                        $btn .= '</div>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DebtRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Debt');
            $attributes = $request->only('box_id', 'customer_id', 'user_id', 'amount', 'comment', 'date');
            $deuda = $this->debtRepository->create($attributes);

            return response()->json([
                'success' => true,
                'message' => 'La deuda ha sido creada con éxito',
                'customer' => $deuda->customer
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Debt $deuda)
    {
        $this->authorize('view', $deuda);

        if ($request->ajax()) {
            return response()->json($deuda);
        }
        
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Debt $deuda)
    {
        $this->authorize('update', $deuda);

        if ($request->ajax()) {
            return response()->json($deuda);
        }
        
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DebtRequest $request, Debt $deuda)
    {
        try {
            $this->authorize('update', $deuda);
            $attributes = $request->only('amount', 'comment');
            $this->debtRepository->update($deuda->id, $attributes);
            flash("La deuda ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
                'message' => 'El pago ha sido actualizado con éxito',
                'customer' => $deuda->refresh()->customer
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Debt $deuda)
    {
        try {
            $this->authorize('delete', $deuda);
            $customer = $deuda->customer;
            $deuda->delete();
            
            return response()->json([
                'success' => true,
                'message' => "La deuda ha sido eliminada con éxito",
                'customer' => $customer->fresh()
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
