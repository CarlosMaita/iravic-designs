<?php

namespace App\Http\Controllers\admin\schedules;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\VisitRequest;
use App\Models\Visit;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\VisitRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public $scheduleRepository;

    public $visitRepository;

    public function __construct(ScheduleRepository $scheduleRepository, VisitRepository $visitRepository)
    {
        $this->scheduleRepository = $scheduleRepository;

        $this->visitRepository = $visitRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Visit');

        if ($request->ajax()) {
            $visits = isset($request->customer) ? $this->visitRepository->all(array('customer' => $request->customer)) : array();
            return Datatables::of($visits)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $btn = '';

                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<a href="'. route('visitas.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger btn-action-icon delete-visit" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
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
    public function store(VisitRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Visit');
            $schedule = $this->scheduleRepository->firstOrCreate(array('date' => $request->date));
            $attributes = array_merge(
                array('schedule_id' => $schedule->id),
                $request->only('customer_id', 'user_creator_id', 'date', 'comment')
            );
            $this->visitRepository->create($attributes);

            return response()->json([
                    'message' => 'La visita ha sido creada con éxito',
                    'success' => true
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Visit $visita)
    {
        $this->authorize('update', $visita);

        if ($request->ajax()) {
            return response()->json($visita);
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
    public function update(VisitRequest $request, Visit $visita)
    {
        try {
            $this->authorize('update', $visita);
            $this->visitaRepository->update($visita->id, $request->only('date'));
            
            return response()->json([
                'message' => "La visita ha sido actualizada con éxito",
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
    public function destroy(Visit $visita)
    {
        try {
            $this->authorize('delete', $visita);
            $visita->delete();
            
            return response()->json([
                'success' => true,
                'message' => "La visita ha sido eliminada con éxito"
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
