<?php

namespace App\Http\Controllers\admin\schedules;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\VisitRepository;
use App\Repositories\Eloquent\ZoneRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ScheduleController extends Controller
{
    public $scheduleRepository;

    public $userRepository;

    public $visitRepository;

    public $zoneRepository;

    public function __construct(ScheduleRepository $scheduleRepository, UserRepository $userRepository, VisitRepository $visitRepository, ZoneRepository $zoneRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->userRepository = $userRepository;
        $this->visitRepository = $visitRepository;
        $this->zoneRepository = $zoneRepository;
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
            $schedules = $this->scheduleRepository->allQuery($request->draw == 1 ? true : false);
            return DataTables::of($schedules)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $btn = '';

                        if (Auth::user()->can('view', $row)) {
                            $btn .= '<a href="'. route('agendas.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        }
                        #Nadie podra eliminar agenda - code commented
                        // if (Auth::user()->can('delete', $row) && !$row->completed ) {
                        //     $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger btn-action-icon delete-schedule" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        // }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
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

        if ($request->ajax() || isset($request->axios)) {
            $visits = $this->visitRepository->allBySchedule($agenda->id, $request->zones, $request->roles);
            return response()->json([
                'schedule' => $agenda,
                'visits' => $visits
            ]);
        }

        $agenda->load('visits.customer.zone');
        $employees = $this->userRepository->allEmployees();
        $sortBy = empty($request->sort) || $request->sort != 'desc' ? 'asc' : 'desc';
        $visits = $this->visitRepository->allBySchedule($agenda->id);
        $zones = $agenda->getZones($sortBy);
        $openZone = $request->get('open-zone') ?: null; // Se usa para abrir automaticamente uno los collapses de zona

        return view('dashboard.schedules.show')
                ->withEmployees($employees)
                ->withSchedule($agenda)
                ->withSortBy($sortBy)
                ->withVisits($visits)
                ->withZones($zones)
                ->withOpenZone($openZone);
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
