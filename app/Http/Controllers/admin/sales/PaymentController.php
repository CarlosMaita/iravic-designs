<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\PaymentRequest;
use App\Models\Payment;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\VisitRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public $paymentRepository;

    public $scheduleRepository;

    public $visitRepository;

    public function __construct(PaymentRepository $paymentRepository, ScheduleRepository $scheduleRepository, VisitRepository $visitRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->visitRepository = $visitRepository;
        $this->middleware('box.open')->only('create');
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
            if ($request->customer) {
                $payments = $this->paymentRepository->all(array('customer' => $request->customer));
            } else if ($request->box) {
                $payments = $this->paymentRepository->all(array('box' => $request->box));
            } else {
                $payments = array();
            }
            
            return Datatables::of($payments)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';
                        
                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-success btn-action-icon edit-payment mb-2" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></button>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-payment mb-2" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
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
            DB::beginTransaction();
            $attributes = $request->only('box_id', 'customer_id', 'user_id', 'amount', 'comment', 'date', 'payed_bankwire', 'payed_card', 'payed_cash');
            $pago = $this->paymentRepository->create($attributes);
            $this->visitRepository->completeByDateUser($pago->customer_id, $pago->getRawOriginal('date'));

            if (!empty($request->visit_date)) {
                $schedule = $this->scheduleRepository->firstOrCreate(array('date' => $request->visit_date));
                $attributes = array(
                        'customer_id' => $pago->customer_id,
                        'schedule_id' => $schedule->id,
                        'user_id' => $request->user_id,
                        'comment' => $request->visit_comment,
                        'date' => $request->visit_date
                    );
                $this->visitRepository->create($attributes);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'El pago ha sido creado con éxito',
                'customer' => $pago->customer
            ]);
        } catch (Exception $e) {
            DB::rollback();
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
        if ($request->ajax()) {
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

        if ($request->ajax()) {
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
                'message' => 'El pago ha sido actualizado con éxito',
                'customer' => $pago->refresh()->customer
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
            $customer = $pago->customer;
            $pago->delete();
            
            return response()->json([
                'success' => true,
                'message' => "El Pago ha sido eliminado con éxito",
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
