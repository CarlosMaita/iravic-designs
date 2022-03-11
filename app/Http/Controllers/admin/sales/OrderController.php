<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\OrderRequest;
use App\Http\Requests\admin\OrderDiscountRequest;
use App\Models\Order;
use App\Repositories\Eloquent\BoxRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\OrderProductRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\VisitRepository;
use App\Repositories\Eloquent\ZoneRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public $boxRepository;

    public $customerRepository;
    
    public $orderRepository;

    public $orderProductRepository;

    public $productRepository;

    public $scheduleRepository;

    public $visitRepository;

    public $zoneRepository;

    /**
     * Construct
     */
    public function __construct(BoxRepository $boxRepository, CustomerRepository $customerRepository, OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, ProductRepository $productRepository, ScheduleRepository $scheduleRepository, VisitRepository $visitRepository, ZoneRepository $zoneRepository)
    {
        $this->boxRepository = $boxRepository;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->productRepository = $productRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->visitRepository = $visitRepository;
        $this->zoneRepository = $zoneRepository;
        $this->middleware('box.open')->only('create');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Order');
        
        if ($request->ajax()) {
            $orders = $this->orderRepository->all();
            return Datatables::of($orders)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('view', $row)) {
                            $btn .= '<a href="'. route('ventas.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        }

                        // if (Auth::user()->can('update', $row)) {
                        //     $btn .= '<a href="'. route('ventas.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        // }

                        // if (Auth::user()->can('delete', $row)) {
                        //     $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger btn-action-icon delete-box" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        // }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', 'App\Models\Order');
        $boxParam = $this->boxRepository->findOnly($request->box);
        $customers = $this->customerRepository->all();
        $customerParam = $this->customerRepository->findOnly($request->cliente);
        $products = $this->productRepository->all();
        $zones = $this->zoneRepository->all();

        return view('dashboard.orders.create')
                ->withBoxParam($boxParam)
                ->withCustomers($customers)
                ->withCustomerParam($customerParam)
                ->withOrder(new Order())
                ->withProducts($products)
                ->withZones($zones);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Order');
            DB::beginTransaction();
            $attributes = array_merge(
                array('total_real' => $request->total),
                $request->only('box_id', 'customer_id', 'user_id', 'date', 'payed_bankwire', 'payed_card', 'payed_cash', 'payed_credit', 'discount' ,'subtotal', 'total')
            );
            $order = $this->orderRepository->create($attributes);

            foreach ($request->products as $product_id) {
                if ($product = $this->productRepository->find($product_id)) {
                    if (isset($request->qtys[$product_id]) && $request->qtys[$product_id] > 0) {
                        $attributes = array(
                            'color_id' => $product->color_id,
                            'order_id'  => $order->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'product_price' => $product->regular_price,
                            'qty' => $request->qtys[$product_id],
                            'size_id' => $product->size_id,
                            'stock_type' => $request->stock_type,
                            'total' => ($product->regular_price * $request->qtys[$product_id])
                        );
                        $this->orderProductRepository->create($attributes);
                    }
                }
            }

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
            DB::commit();

            if (isset($request->customer_param)) {
                $redirect = route('clientes.show', [$request->customer_param]) . '?ventas=true';
            } else if (isset($request->box_param)) {
                $redirect = route('cajas.show', [$request->box_param]) . '?ventas=true';
            } else {
                $redirect = route('ventas.index');
            }

            flash("La venta ha sido creado con éxito")->success();
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
                    'trace' => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Calculate totals.
     *
     * @return \Illuminate\Http\Response
     */
    public function calculateDiscount(OrderDiscountRequest $request)
    {
        try {
            $discount = $request->discount;
            $subtotal = 0;

            foreach ($request->products as $product_id) {
                if ($product = $this->productRepository->find($product_id)) {
                    if (isset($request->qtys[$product_id]) && $request->qtys[$product_id] > 0) {
                        $subtotal += ($product->regular_price * $request->qtys[$product_id]);
                    }
                }
            }
            
            $total = $subtotal - $discount;

            return response()->json([
                'success' => true,
                'data' => [
                    'discount' => $discount,
                    'discount_format' => '$ ' . number_format($discount, 2, '.', ','),
                    'subtotal' =>  '$ ' . number_format($subtotal, 2, '.', ','),
                    'total' => '$ ' . number_format($total, 2, '.', ',')
                ]
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
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $venta)
    {
        $this->authorize('view', $venta);
        return view('dashboard.orders.show')
                ->withOrder($venta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $venta)
    {
        $this->authorize('update', $venta);
        return view('dashboard.orders.edit')
                ->withOrder($venta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, Order $venta)
    {
        try {
            $this->authorize('update', $venta);
            // $this->orderRepository->update($venta->id, $request->only('cash_initial'));
            flash("La venta <b>$venta->id</b> ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('ventas.edit', $venta->id)
                ]
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
