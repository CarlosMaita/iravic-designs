<?php

namespace App\Http\Controllers\admin\schedules;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\VisitRequest;
use App\Http\Requests\admin\VisitResponsableRequest;
use App\Models\Customer;
use App\Models\Visit;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\VisitRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

                        if (Auth::user()->can('update', $row) &&  !$row->is_completed ) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-success btn-action-icon edit-visit" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></button>';
                        }

                        if (Auth::user()->can('delete', $row) && !$row->existsAssignedResponsible() && !$row->is_completed ) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger btn-action-icon delete-visit" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }
                        // return $row->existsAssignedResponsible();
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
            DB::beginTransaction();
            $schedule = $this->scheduleRepository->firstOrCreate(array('date' => $request->date));
            $attributes = array_merge(
                array('schedule_id' => $schedule->id),
                $request->only('customer_id', 'user_creator_id', 'date', 'comment')
            );
            $this->visitRepository->create($attributes);
            $customer = Customer::find($request->customer_id);
            #bajar la bandera de pendiente por agendar 
            $customer->setPendingToSchedule(false);
            DB::commit();
            return response()->json([
                    'message' => 'La visita ha sido creada con éxito',
                    'success' => true
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
            DB::beginTransaction();
            $prev_schedule = $visita->schedule;
            $schedule = $this->scheduleRepository->firstOrCreate(array('date' => $request->date));
            $attributes = array_merge(
                array('schedule_id' => $schedule->id),
                $request->only('date', 'comment')
            );
            $this->visitRepository->update($visita->id, $attributes);
            
            /* 
             * Si se cambia la fecha de una visita y la agenda queda sin visitas, esta es eliminada
             */
            if ($schedule->id != $prev_schedule->id && !$prev_schedule->visits()->count()) {
                $prev_schedule->delete();
            }
            DB::commit();

            return response()->json([
                'message' => "La visita ha sido actualizada con éxito",
                'success' => 'true',
                'visita' => $visita->refresh()->load('customer'),
                'prev_schedule' => $prev_schedule->fresh()
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
    public function destroy(Visit $visita)
    {
        try {
            $this->authorize('delete', $visita);
            #validar si cliente tiene deudas 
            if($visita->customer->haveDebtsCustomer() && $visita->is_collection)
            {
                #No se puede eliminar
                return response()->json([
                    'success' => false,
                    'message' => "No se pudo eliminar la visita debido a una deuda pendiente del cliente"
                ]);   
            }
            #client sin deudas
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

    /**
     *  Se actualiza el responsable de una visita
     */
    public function updateResponsable(VisitResponsableRequest $request, Visit $visita)
    {
        try {
            $attributes = $request->only('user_responsable_id');
            $this->visitRepository->update($visita->id, $attributes);
            $visita->refresh()->load('responsable');

            return response()->json([
                'success' => true,
                'visita' => $visita,
                'message' => 'El responsable de la visita ha sido actualizado'
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
     * Se marca una visita como completada
     */
    public function complete(Request $request, Visit $visita)
    {
        try {
            $attributes = $request->only('is_completed');
            if($request->is_completed)
            {
                #Marcar visita como COMPLETADA
                if($visita->existsAssignedResponsible())
                {
                    $this->visitRepository->update($visita->id, $attributes);
                    #vefificar si todas las visitas de la agenda han sido completadas 
                    if ($this->scheduleRepository->checkAllVisitsCompleted($visita->schedule_id)){
                        #completar repositorio 
                        $this->scheduleRepository->setCompleted($visita->schedule_id, true);
                    }
                    $message = 'La visita ha sido marcada como completa con éxito';
                    return response()->json([
                        'success' => true,
                        'message' => $message
                    ]);
                }else{
                    $message = 'Falta asignar responsable a la visita';
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
            } else{
                #Marcar visita como NO COMPLETADA
                $this->visitRepository->update($visita->id, $attributes);
                #Marcar agenda como NO COMPLETADA 
                $this->scheduleRepository->setCompleted($visita->schedule_id, false);
                 $message = 'La visita ha sido marcada como NO completa con éxito';
                 return response()->json([
                     'success' => true,
                     'message' => $message
                 ]);
            }

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
     * Se ordenan las posiciones de las visitas
     */
    public function sort(Request $request)
    {
        try {
            if (!empty($request->visits)) {
                foreach ($request->visits as $key => $id) {
                    if ($visit = $this->visitRepository->find($id)) {
                        $visit->position = ($key + 1);
                        $visit->save();
                    }
                }

                flash("La agenda ha sido ordenada")->success();

                return response()->json([
                    'success' => true
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se ha podido actualizar las posiciones de las visitas.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'No se ha podido actualizar las posiciones de las visitas.',
                'error' => [
                    'e' => $e->getMessage(),
                    'trace' => $e->getMessage()
                ]
            ]);
        }

        
    }

    /**
     * Posponer una visita
     */
    public function postpone( Request $request, Visit $visita)  
    {
        try{
            #subir bandera de pending to schedule 
            $customer =  $visita->customer;
            $customer->setPendingToSchedule(true);
            #eliminar visita
            $visita->delete();
            return response()->json([
                'success' => true,
                'message' => 'Se ha postergado la visita.'
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'No se ha podido postergar la visita.',
                'error' => [
                    'e' => $e->getMessage(),
                    'trace' => $e->getMessage()
                ]
            ]);
        }
    }

    
}
