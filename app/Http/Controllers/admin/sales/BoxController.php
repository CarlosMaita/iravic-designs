<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\BoxRequest;
use App\Models\Box;
use App\Repositories\Eloquent\BoxRepository;
use App\Repositories\Eloquent\CustomerRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoxController extends Controller
{
    public $boxRepository;

    public $customerRepository;

    public function __construct(BoxRepository $boxRepository, CustomerRepository $customerRepository)
    {
        $this->boxRepository = $boxRepository;
        $this->customerRepository = $customerRepository;
        $this->middleware('box.create')->only('create');
        $this->middleware('box.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Box');

        if ($request->ajax()) {
            $boxes = $this->boxRepository->allQuery();

            return datatables()->eloquent($boxes)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        
                        $btn = '<div style="display:flex">';
                        
                        if (Auth::user()->can('update', $row) && $row->closed == 0) {
                            $btn .= '<button data-id="' . $row->id . '" class="btn btn-sm btn-warning btn-action-icon close-box mb-2" title="Cerrar Caja" data-toggle="tooltip"><i class="fas fa-lock"></i></button>';
                        }

                        if (Auth::user()->can('view', $row)) {
                            $btn .= '<a href="'. route('cajas.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon mb-2" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        }

                        if (Auth::user()->can('update', $row) && !$row->isClosed()) {
                            $btn .= '<a href="'. route('cajas.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon mb-2" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

                        if (Auth::user()->can('delete', $row) && !$row->isClosed()) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger btn-action-icon delete-box mb-2" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

                        $btn .= '</div">';


                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.boxes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', 'App\Models\Box');
        return view('dashboard.boxes.create')
                ->withBox(new Box());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BoxRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Box');
            $this->boxRepository->create($request->only('date', 'date_start', 'cash_initial', 'user_id'));
            flash("La caja <b>$request->name</b> ha sido creada con éxito")->success();

            return response()->json([
                    'success' => true,
                    'data' => [
                        'redirect' => route('cajas.index')
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
    public function show(Request $request, Box $caja)
    {
        $this->authorize('view', $caja);
        $customers = $this->customerRepository->all();
        $orders = $caja->orders()->orderBy('date', 'desc')->get();
        $showOrdersTab = isset($request->ventas) ? true : false; // Para determinar si hay que abrir el tab de ordenes en el collapse
        return view('dashboard.boxes.show')
                ->withCustomers($customers)
                ->withBox($caja)
                ->withOrders($orders)
                ->withShowOrdersTab($showOrdersTab);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Box $caja)
    {
        $this->authorize('update', $caja);
        #validar que la caja esta abierta
        if ($caja->isClosed()){
            flash( 'La caja no puede ser editada porque ya ha sido cerrada.' )->warning();
            return redirect()->back();
        }
        return view('dashboard.boxes.edit')
                ->withBox($caja);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BoxRequest $request, Box $caja)
    {
        try {
            $this->authorize('update', $caja);
            if ($request->header('close-box')) {
                $attributes = array(
                    'closed' => 1,
                    'date_end' => date('Y-m-d H:i:s'),
                    );
                $this->boxRepository->update($caja->id, $attributes);
            } else {
                $this->boxRepository->update($caja->id, $request->only('cash_initial'));
                flash("La caja <b>$caja->id</b> ha sido actualizada con éxito")->success();
            }

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('cajas.edit', $caja->id)
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Box $caja)
    {
        try {
            $this->authorize('delete', $caja);
            $caja->delete();
            
            return response()->json([
                'success' => true,
                'message' => "La caja ha sido eliminada con éxito"
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
