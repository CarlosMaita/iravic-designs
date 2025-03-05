<?php

namespace App\Http\Controllers\admin\sales;

use App\Constants\CustomerConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\OrderRequest;
use App\Http\Requests\admin\OrderDiscountRequest;
use App\Models\Order;
use App\Repositories\Eloquent\BoxRepository;
use App\Repositories\Eloquent\CreditRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\OrderProductRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\VisitRepository;
use App\Repositories\Eloquent\ZoneRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables as DataTables;

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

    public $creditRepository;

    /**
     * Construct
     */
    public function __construct(
        BoxRepository $boxRepository, CustomerRepository $customerRepository,
        OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, 
        ProductRepository $productRepository, ScheduleRepository $scheduleRepository, 
        VisitRepository $visitRepository, ZoneRepository $zoneRepository, 
        CreditRepository $creditRepository
    )
    {
        $this->boxRepository = $boxRepository;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->productRepository = $productRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->visitRepository = $visitRepository;
        $this->zoneRepository = $zoneRepository;
        $this->creditRepository = $creditRepository;
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
            // Consulta base
            $query = $this->orderRepository->query();
            
            // Formatear la respuesta para DataTables
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('actions', function (Order $order) {
                    $buttons = '';

                    if (Auth::user()->can('view', $order)) {
                        $buttons .= '<a href="' . route('ventas.show', $order->id) . '" class="btn btn-sm btn-primary btn-action-icon" title="View" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                    }

                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->toJson();
        }

        return view('dashboard.orders.index');
    }

    public function index_old(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Order');
        
        if ($request->ajax()) {
            $orders = $this->orderRepository->query();
            return Datatables::of($orders)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('view', $row)) {
                            $btn .= '<a href="'. route('ventas.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
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
        $customers = $this->customerRepository->allOnlyName();
        $customerParam = $this->customerRepository->findOnly($request->cliente);
        $products = $this->productRepository->all();
        $zones = $this->zoneRepository->all();

        return view('dashboard.orders.create')
                ->withBoxParam($boxParam)
                ->withCustomers($customers)
                ->withCustomerParam($customerParam)
                ->withQualifications(CustomerConstants::QUALIFICATIONS)
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
                $request->only(
                    'customer_id',
                    'box_id',
                    'user_id',
                    'date',
                    'payed_bankwire',
                    'payed_card',
                    'payed_cash',
                    'payed_credit', 
                    'discount' ,
                    'subtotal',
                    'total',
                )
            );
            $order = $this->orderRepository->create($attributes); //create order

            /**
             * Se guardan los productos de la venta
             */
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

            /**
             * Si el pago es a credito se crea una instacia de cobro con la cantidad de cuotas, frecuencia y fecha de inicio
             */
            if ($request->payed_credit) {
                $quota = $request->input('quotas');
                $amount_quotas = $request->input('amount-quotas');
                $start_date = $request->input('start-quotas');
                /**
                 * Crear Credito para el cliente
                 */
                $attributes = array(
                    'start_date' => $start_date,
                    'amount_quotas' => $amount_quotas,
                    'quota' => $quota,
                    'total' => $request->total_to_collection,   
                    'order_id' => $order->id,   
                    'customer_id' => $request->customer_id,
                );
                $credit = $this->creditRepository->create($attributes);

                /**
                 * Crear Cobros para el cliente apartir de la fecha inicial de cobro 
                 * 
                 */
                $attributes = array(
                    'start_date' => $start_date,
                    'amount_quotas' => $amount_quotas,
                    'quota' => $quota,
                    'credit_id' => $credit->id,
                    'customer_id' => $request->customer_id,
                    'user_id' => Auth::user()->id,
                ); 
                $this->creditRepository->createCollectionsAndVisits($attributes);

            }

            /**
             * Cuando se realiza un pago, se puede pautar una proxima visita para el cliente
             * Si selecciona una fecha para visita, intenta crear una agenda para esa fecha si aun no existe
             */
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
     * Calculate totals, including discount.
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

                        $regular_price =  $product->regular_price; // Precio regular por defecto
                        if(auth()->user()->can('prices-per-method-payment') ) {
                            if ($request->payment_method == "card" || $request->payment_method == "credit") {
                                $regular_price = $request->payment_method == "card" ?  $product->regular_price_card_credit : $product->regular_price_credit;
                            }
                        }
                        
                        $subtotal += ($regular_price * $request->qtys[$product_id]);
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
}
