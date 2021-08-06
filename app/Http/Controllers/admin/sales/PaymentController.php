<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\PaymentRequest;
use App\Models\Payment;
use App\Repositories\Eloquent\PaymentRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
        // $this->middleware('box.create')->only('create');
        // $this->middleware('box.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Payment');

        if ($request->ajax()) {
            $payments = $request->customer ? $this->paymentRepository->all($request->customer) : array();
            return Datatables::of($payments)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('view', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></button>';
                        }
                        
                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-success btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></button>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-payment" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

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
    public function store(PaymentRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Payment');
            $attributes = $request->only('box_id', 'customer_id', 'user_id', 'amount', 'comment', 'date', 'payed_bankwire', 'payed_card', 'payed_cash');
            $this->paymentRepository->create($attributes);

            return response()->json([
                'success' => true,
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
    public function show(Request $request, Payment $pago)
    {
        $this->authorize('view', $pago);

        if ($request->isAjax()) {
            return response()->json($pago);
        }
        
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Payment $pago)
    {
        $this->authorize('update', $pago);

        if ($request->isAjax()) {
            return response()->json($pago);
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
    public function update(PaymentRequest $request, Payment $pago)
    {
        try {
            $this->authorize('update', $pago);
            $attributes = $request->only('amount', 'comment', 'payed_bankwire', 'payed_card', 'payed_cash');
            $this->paymentRepository->update($pago->id, $attributes);
            flash("El Pago <b>$request->name</b> ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
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
    public function destroy(Payment $pago)
    {
        try {
            $this->authorize('delete', $pago);
            $pago->delete();
            
            return response()->json([
                'success' => true,
                'message' => "El Pago ha sido eliminado con éxito"
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
