<?php

namespace App\Http\Controllers\admin\schedules;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\ScheduleRequest;
use App\Models\Schedule;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\VisitRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
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
        $this->authorize('viewany', 'App\Models\Schedule');

        if ($request->ajax()) {
            $schedules = $this->scheduleRepository->all();
            return Datatables::of($schedules)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $btn = '';

                        if (Auth::user()->can('view', $row)) {
                            $btn .= '<a href="'. route('agendas.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        }

                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<a href="'. route('agendas.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon edit-visit" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger btn-action-icon delete-schedule" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.schedules.index');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Schedule $agenda)
    {
        $this->authorize('view', $agenda);

        if ($request->ajax()) {
            $visits = $this->visitRepository->allBySchedule($agenda->id);
            return response()->json([
                'schedule' => $agenda,
                'visits' => $visits
            ]);
        }

        return view('dashboard.schedules.show')
                ->withSchedule($agenda);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $agenda)
    {
        $this->authorize('update', $agenda);
        return view('dashboard.schedules.edit')
                ->withSchedule($agenda);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ScheduleRequest $request, Schedule $agenda)
    {
        try {
            $this->authorize('update', $agenda);
            DB::beginTransaction();
            $attributes = $request->only('');
            $this->customerRepository->update($agenda->id, $attributes);
            DB::commit();
            flash("La agenda ha sido actualizada con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('agendas.edit', $agenda->id)
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
    public function destroy(Schedule $agenda)
    {
        try {
            $this->authorize('delete', $agenda);
            DB::beginTransaction();
            $agenda->delete();
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "La agenda ha sido eliminada con éxito"
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
