<?php

namespace App\Http\Controllers\admin\customers;

use App\Constants\CustomerConstants;
use App\Helpers\FormatHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\CustomerRequest;
use App\Models\Customer;
use App\Repositories\Eloquent\CustomerRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $customers = $this->customerRepository->allQuery();
            return DataTables::of($customers)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        // if (Auth::user()->can('view', $row)) {
                            $btn .= '<a href="'. route('clientes.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon mb-2" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        // }
                        
                        // if (Auth::user()->can('update', $row)) {
                            $btn .= '<a href="'. route('clientes.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon mb-2" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        // }

                        // if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger btn-action-icon delete-customer mb-2" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        // }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
        }

        return view('dashboard.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //        return view('dashboard.customers.create')
                ->withCustomer(new Customer())
                ->withQualifications(CustomerConstants::QUALIFICATIONS);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        try {            DB::beginTransaction();
            
            $attributes = array_merge(
                array('username'        => FormatHelper::formatDniNumber($request->dni)),
                array('password'        => bcrypt('12345')),
                $request->only( 
                    'name',
                    'email',
                    'dni',
                    'cellphone',
                    'qualification',
                    'shipping_agency',
                    'shipping_agency_address'
                )
            );

            $customer = $this->customerRepository->create($attributes);
            
            DB::commit();

            if (isset($request->from_orders)) {
                flash("El cliente <b>$request->name</b> ha sido creado con éxito")->success();
                return response()->json([
                        'success' => true,
                        'data' => [
                            'customer' => $customer,
                            'redirect' => route('ventas.create'), 
                            'from_orders' => true 
                        ]
                ]);
            }
            
            flash("El cliente <b>$request->name</b> ha sido creado con éxito")->success();
            return response()->json([
                    'success' => true,
                    'data' => [
                        'customer' => $customer,
                        'redirect' => route('clientes.index')
                    ]
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::debug('Error ocurred after trying to create a customer');
            Log::debug($e->getMessage());
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
    public function show(Request $request, Customer $cliente)
    {
        $customer = $cliente;
        if($request->ajax()){
            return response()->json($customer);
        }
        //        $orders = $customer->orders()->orderBy('date', 'desc')->get();
        $refunds = collect(); // Empty collection since refunds functionality was removed
        $showOrdersTab = isset($request->pedidos) ? true : false;
        $showRefundsTab = isset($request->devoluciones) ? true : false;
        $planningCollection = $customer->getPlanningCollection();

        return view('dashboard.customers.show')
                ->withCustomer($customer)
                ->withOrders($orders)
                ->withRefunds($refunds)
                ->withShowOrdersTab($showOrdersTab)
                ->withShowRefundsTab($showRefundsTab)
                ->withPlanningCollection($planningCollection);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $cliente)
    {        return view('dashboard.customers.edit')
                ->withCustomer($cliente)
                ->withQualifications(CustomerConstants::QUALIFICATIONS);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, Customer $cliente)
    {
        try {            DB::beginTransaction();
            
            $attributes = $request->only(
                'name',
                'email',
                'dni',
                'cellphone',
                'qualification',
                'shipping_agency',
                'shipping_agency_address'
            );
            
            $this->customerRepository->update($cliente->id, $attributes);
            DB::commit();
            flash("El cliente <b>$request->name</b> ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('clientes.edit', $cliente->id)
                ]
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::debug('Error ocurred after trying to update a customer: ' . $cliente->id);
            Log::debug($e->getMessage());
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
    public function destroy(Customer $cliente)
    {
        try {              #validar existencia de ordenes antes de eliminar
              if ($cliente->existsOrders()){
                  return response()->json([
                      'success' => false,
                      'message' => "El cliente no ha podido ser eliminada existe ordenes asociadas"
                  ]); 
              }
            DB::beginTransaction();
            $cliente->delete();
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "El cliente ha sido eliminado con éxito"
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
}
