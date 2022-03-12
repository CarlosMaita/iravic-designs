<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\RefundRequest;
use App\Models\Refund;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\DebtRepository;
use App\Repositories\Eloquent\DebtOrderProductRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\OrderProductRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\RefundRepository;
use App\Repositories\Eloquent\RefundProductRepository;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\VisitRepository;
use App\Services\Orders\OrderService;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public $customerRepository;

    public $debtRepository;

    public $debtOrderProductRepository;

    public $orderRepository;

    public $orderProductRepository;

    public $productRepository;
    
    public $refundRepository;
    
    public $refundProductRepository;

    public $scheduleRepository;

    public $visitRepository;

    /**
     * Construct
     */
    public function __construct(CustomerRepository $customerRepository, DebtRepository $debtRepository, DebtOrderProductRepository $debtOrderProductRepository, OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, ProductRepository $productRepository, RefundRepository $refundRepository, RefundProductRepository $refundProductRepository, ScheduleRepository $scheduleRepository, VisitRepository $visitRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->debtRepository = $debtRepository;
        $this->debtOrderProductRepository = $debtOrderProductRepository;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->productRepository = $productRepository;
        $this->refundRepository = $refundRepository;
        $this->refundProductRepository = $refundProductRepository;
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
        $this->authorize('viewany', 'App\Models\Refund');
        
        if ($request->ajax()) {
            $results = $this->refundRepository->all();
            return Datatables::of($results)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('view', $row)) {
                            $btn .= '<a href="'. route('devoluciones.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.refunds.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', 'App\Models\Refund');
        $productsForRefund = $this->orderProductRepository->availableForRefund($request->cliente);

        if ($request->ajax()) {
            return response()->json($productsForRefund);
        }

        $customers = $this->customerRepository->all();
        $customerParam = $this->customerRepository->findOnly($request->cliente);
        $products = $this->productRepository->all();

        return view('dashboard.refunds.create')
                ->withCustomers($customers)
                ->withCustomerParam($customerParam)
                ->withProducts($products)
                ->withProductsForRefund($productsForRefund)
                ->withRefund(new Refund());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RefundRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Refund');
            DB::beginTransaction();
            $is_credit_shared = isset($request->is_credit_shared) ? 1 : 0;
            $productsRefund = array();
            $productsOrder = array();
            $totals = OrderService::getOrderTotalsByRefund($request->only('discount', 'products', 'qtys', 'products_refund', 'qtys_refund', 'payment_method', 'is_credit_shared'), $this->productRepository, $this->orderProductRepository);

            /** Refund **/
            $attributes = array_merge(
                array(
                    'total' => $totals['total_refund'],
                    'total_refund_credit' => $totals['total_refund_credit'],
                    'total_refund_debit' => $totals['total_refund_debit']
                ),
                $request->only('box_id', 'customer_id', 'user_id', 'date')
            );
            $refund = $this->refundRepository->create($attributes);
            foreach ($request->products_refund as $product_id) {
                if ($product = $this->orderProductRepository->find($product_id)) {
                    if (isset($request->qtys_refund[$product_id]) && $request->qtys_refund[$product_id] > 0) {
                        $attributes = array(
                            'color_id'          => $product->color_id,
                            'order_product_id'  => $refund->id,
                            'order_product_id'  => $product->id,
                            'product_id'        => $product->product_id,
                            'product_name'      => $product->product_name,
                            'product_price'     => $product->product_price,
                            'qty'               => $request->qtys_refund[$product_id],
                            'refund_id'         => $refund->id,
                            'size_id'           => $product->size_id,
                            'stock_type'        => $request->stock_type,
                            'total'             => ($product->product_price * $request->qtys_refund[$product_id])
                        );
                        $refundProduct = $this->refundProductRepository->create($attributes);
                        array_push($productsRefund, $refundProduct);
                    }
                }
            }

            /** Order && Previous Debt**/
            if (!empty($request->products)) {
                $attributesOrder = array_merge(
                    array(
                        'customer_id' => $request->customer_id_new_credit,
                        'refund_id' => $refund->id,
                        'discount' => $totals['discount'],
                        'subtotal' => $totals['subtotal'],
                        'total_real' => $totals['total_order'],
                        'total' => $totals['total_cancel'],
                        'total_refund_credit' => $totals['total_refund_credit'],
                        'total_refund_debit' => $totals['total_refund_debit'],
                        'is_credit_shared' => $is_credit_shared
                    ),
                    $request->only('box_id', 'user_id', 'date', 'payed_bankwire', 'payed_card', 'payed_cash', 'payed_credit')
                );

                $order = $this->orderRepository->create($attributesOrder);

                foreach ($request->products as $product_id) {
                    if ($product = $this->productRepository->find($product_id)) {
                        if (isset($request->qtys[$product_id]) && $request->qtys[$product_id] > 0) {
                            $attributes = array(
                                'color_id'          => $product->color_id,
                                'order_id'          => $order->id,
                                'product_id'        => $product->id,
                                'product_name'      => $product->name,
                                'product_price'     => $product->regular_price,
                                'qty'               => $request->qtys[$product_id],
                                'size_id'           => $product->size_id,
                                'stock_type'        => $request->stock_type,
                                'total'             => ($product->regular_price * $request->qtys[$product_id])
                            );
                            $orderProduct = $this->orderProductRepository->create($attributes);
                            array_push($productsOrder, $orderProduct);
                        }
                    }
                }

                if ($is_credit_shared) {
                    $attributes = array_merge(
                        array(
                            'amount' => $totals['total_refund'],
                            'comment' => 'Devolución #' . $refund->id . ' y Venta #' . $order->id
                        ),
                        $request->only('box_id', 'customer_id', 'user_id', 'date')
                    );
                    $debt = $this->debtRepository->create($attributes);

                    foreach ($productsRefund as $productRefund) {
                        $attributes = array(
                            'debt_id' => $debt->id,
                            'type' => 'refund',
                            'refund_product_id' => $productRefund->id,
                            'order_product_id' => $productRefund->order_product_id,
                            'product_name' => $productRefund->product_name,
                            'product_price' => $productRefund->product_price,
                            'qty' => $productRefund->qty,
                            'total' =>  $productRefund->total
                        ); 
                        $this->debtOrderProductRepository->create($attributes);
                    }

                    foreach ($productsOrder as $productOrder) {
                        $attributes = array(
                            'debt_id' => $debt->id,
                            'type' => 'order',
                            'order_product_id' => $productOrder->id,
                            'product_name' => $productOrder->product_name,
                            'product_price' => $productOrder->product_price,
                            'qty' => $productOrder->qty,
                            'total' =>  $productOrder->total
                        ); 
                        $this->debtOrderProductRepository->create($attributes);
                    }
                }

                // Solo deberia haber visita si hay una compra nueva de productos
                if (isset($request->enable_new_visit) && !empty($request->visit_date)) {
                    $schedule = $this->scheduleRepository->firstOrCreate(array('date' => $request->visit_date));
                    $attributes = array(
                            'customer_id' => $order->customer_id,
                            'order_id' => $order->id,
                            'schedule_id' => $schedule->id,
                            'user_id' => $request->user_id,
                            'comment' => $request->visit_comment,
                            'date' => $request->visit_date
                        );
                    $this->visitRepository->create($attributes);
                }
            }
            DB::commit();
            flash("La devolución ha sido creada con éxito")->success();

            $redirect = !isset($request->customer_param) 
                        ? route('devoluciones.index') 
                        : route('clientes.show', [$request->customer_param]) . '?devoluciones=true';

            return response()->json([
                    'success' => true,
                    'data' => [
                        'redirect' => $redirect
                    ]
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('dashboard.general.operation_error'),
                'error' => [
                    'e' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ]
            ]);
        }
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Refund $devolucion)
    {
        $this->authorize('view', $devolucion);
        return view('dashboard.refunds.show')
                ->withRefund($devolucion);
    }
}
