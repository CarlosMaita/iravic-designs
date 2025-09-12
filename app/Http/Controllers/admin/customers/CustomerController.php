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
        if ($request->ajax()) {
            return DataTables::of($this->customerRepository->allQuery())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return $this->buildActionButtons($row->id);
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
        return view('dashboard.customers.create')
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
        DB::beginTransaction();
        try {
            $attributes = array_merge([
                'username' => FormatHelper::formatDniNumber($request->dni),
                'password' => bcrypt('12345'),
            ], $request->only(
                'name',
                'email',
                'dni',
                'cellphone',
                'qualification',
                'shipping_agency',
                'shipping_agency_address'
            ));

            $customer = $this->customerRepository->create($attributes);
            DB::commit();

            flash("El cliente <b>{$request->name}</b> ha sido creado con éxito")->success();
            $redirect = isset($request->from_orders) ? route('ventas.create') : route('clientes.index');
            return response()->json([
                'success' => true,
                'data' => [
                    'customer' => $customer,
                    'redirect' => $redirect,
                    'from_orders' => isset($request->from_orders)
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Customer create failed', ['error' => $e->getMessage()]);
            return $this->jsonError($e);
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
        if ($request->ajax()) {
            return response()->json($cliente);
        }
        $orders = collect(); // Feature removed / placeholder
        $refunds = collect();
        $planningCollection = $cliente->getPlanningCollection();
        return view('dashboard.customers.show')
            ->withCustomer($cliente)
            ->withOrders($orders)
            ->withRefunds($refunds)
            ->withShowOrdersTab(isset($request->pedidos))
            ->withShowRefundsTab(isset($request->devoluciones))
            ->withPlanningCollection($planningCollection);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $cliente)
    {
        return view('dashboard.customers.edit')
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
        DB::beginTransaction();
        try {
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
            flash("El cliente <b>{$request->name}</b> ha sido actualizado con éxito")->success();
            return response()->json([
                'success' => true,
                'data' => [
                    'redirect' => route('clientes.edit', $cliente->id)
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Customer update failed', ['id' => $cliente->id, 'error' => $e->getMessage()]);
            return $this->jsonError($e);
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
        if ($cliente->existsOrders()) {
            return response()->json([
                'success' => false,
                'message' => 'El cliente no ha podido ser eliminado: existen órdenes asociadas'
            ]);
        }
        DB::beginTransaction();
        try {
            $cliente->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'El cliente ha sido eliminado con éxito'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Customer delete failed', ['id' => $cliente->id, 'error' => $e->getMessage()]);
            return $this->jsonError($e);
        }
    }

    // ----------------- Helpers internos -----------------
    private function buildActionButtons(int $id): string
    {
        $actions = [
            [
                'url' => route('clientes.show', $id),
                'class' => 'btn-primary',
                'icon' => 'fas fa-eye',
                'title' => 'Ver'
            ],
            [
                'url' => route('clientes.edit', $id),
                'class' => 'btn-success',
                'icon' => 'fas fa-edit',
                'title' => 'Editar'
            ],
        ];
        $html = '';
        foreach ($actions as $action) {
            $html .= '<a href="' . $action['url'] . '" class="btn btn-sm ' . $action['class'] . ' btn-action-icon mb-2" title="' . $action['title'] . '" data-toggle="tooltip"><i class="' . $action['icon'] . '"></i></a>';
        }
        $html .= '<button data-id="' . $id . '" class="btn btn-sm btn-danger btn-action-icon delete-customer mb-2" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
        return $html;
    }

    private function jsonError(Exception $e)
    {
        return response()->json([
            'success' => false,
            'message' => __('dashboard.general.operation_error'),
            'error' => [
                'e' => $e->getMessage(),
            ]
        ], 500);
    }
}
