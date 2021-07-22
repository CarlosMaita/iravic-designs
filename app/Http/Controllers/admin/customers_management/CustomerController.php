<?php

namespace App\Http\Controllers\admin\customers_management;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\CustomerRequest;
use App\Models\Customer;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\ZoneRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public $customerRepository;

    public $zoneRepository;

    public function __construct(CustomerRepository $customerRepository, ZoneRepository $zoneRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->zoneRepository = $zoneRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Zone');

        if ($request->ajax()) {
            $customers = $this->customerRepository->all();
            return Datatables::of($customers)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<a href="'. route('clientes.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-customer" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.customers-management.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', 'App\Models\Customer');
        $zones = $this->zoneRepository->all();
        return view('dashboard.customers-management.customers.create')
                ->withCustomer(new Customer())
                ->withZones($zones);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Customer');
            DB::beginTransaction();
            $this->customerRepository->create($request->only('address', 'contact_name', 'contact_telephone', 'contact_dni', 'dni', 'dni_picture', 'latitude', 'longitude', 'max_credit', 'name', 'receipt_picture', 'qualification', 'telephone', 'zone_id'));
            DB::commit();
            flash("El cliente <b>$request->name</b> ha sido creado con éxito")->success();

            return response()->json([
                    'success' => true,
                    'data' => [
                        'redirect' => route('clientes.index')
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $cliente)
    {
        $this->authorize('update', $cliente);
        $zones = $this->zoneRepository->all();
        return view('dashboard.customers-management.customers.edit')
                ->withCustomer($cliente)
                ->withZones($zones);
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
        try {
            $this->authorize('update', $cliente);
            DB::beginTransaction();
            $this->customerRepository->update($cliente->id, $request->only('address', 'contact_name', 'contact_telephone', 'contact_dni', 'dni', 'dni_picture', 'latitude', 'longitude', 'max_credit', 'name', 'receipt_picture', 'qualification', 'telephone', 'zone_id'));
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
        try {
            $this->authorize('delete', $cliente);
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
