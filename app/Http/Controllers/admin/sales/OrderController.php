<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\OrderRequest;
use App\Models\Order;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\ZoneRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public $customerRepository;
    
    public $orderRepository;

    public $productRepository;

    public $zoneRepository;

    /**
     * Construct
     */
    public function __construct(CustomerRepository $customerRepository, OrderRepository $orderRepository, ProductRepository $productRepository, ZoneRepository $zoneRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->zoneRepository = $zoneRepository;
        $this->middleware('order.create')->only('create');
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
                            $btn .= '<a href="'. route('pedidos.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        }

                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<a href="'. route('pedidos.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

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
    public function create()
    {
        $this->authorize('create', 'App\Models\Order');
        $customers = $this->customerRepository->all();
        $products = $this->productRepository->onlyPrincipals();
        $stock_column = Auth::user()->getColumnStock();
        $zones = $this->zoneRepository->all();
        return view('dashboard.orders.create')
                ->withCustomers($customers)
                ->withOrder(new Order())
                ->withProducts($products)
                ->withStockColumn($stock_column)
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
            // $this->orderRepository->create($request->only('date', 'date_start', 'cash_initial', 'user_id'));
            flash("El pedido ha sido creado con éxito")->success();

            return response()->json([
                    'success' => true,
                    'data' => [
                        'redirect' => route('pedidos.index')
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
    public function show(Order $pedido)
    {
        $this->authorize('view', $pedido);
        return view('dashboard.orders.show')
                ->withOrder($pedido);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $pedido)
    {
        $this->authorize('update', $pedido);
        return view('dashboard.orders.edit')
                ->withOrder($pedido);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, Order $pedido)
    {
        try {
            $this->authorize('update', $pedido);
            // $this->orderRepository->update($pedido->id, $request->only('cash_initial'));
            flash("El pedido <b>$pedido->id</b> ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('pedidos.edit', $pedido->id)
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
