<?php

namespace App\Http\Controllers\admin\customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\ZoneRequest;
use App\Models\Zone;
use App\Repositories\Eloquent\ZoneRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ZoneController extends Controller
{
    public $zoneRepository;

    public function __construct(ZoneRepository $zoneRepository)
    {
        $this->zoneRepository = $zoneRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewany', 'App\Models\Zone');
        $zones = $this->zoneRepository->all();
        return view('dashboard.zones.index')
                ->withZones($zones);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', 'App\Models\Zone');
        return view('dashboard.zones.create')
                ->withZone(new Zone());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ZoneRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Zone');
            $this->zoneRepository->create($request->only('name', 'position', 'address_destination', 'latitude_destination', 'longitude_destination'));
            flash("La zona <b>$request->name</b> ha sido creada con éxito")->success();

            return response()->json([
                    'success' => true,
                    'data' => [
                        'redirect' => route('zonas.index')
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Zone $zona)
    {
        $this->authorize('view', $zona);
        $zona->load('customers');
        return view('dashboard.zones.show')
                ->withZone($zona);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Zone $zona)
    {
        $this->authorize('update', $zona);
        return view('dashboard.zones.edit')
                ->withZone($zona);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ZoneRequest $request, Zone $zona)
    {
        try {
            $this->authorize('update', $zona);
            $this->zoneRepository->update($zona->id, $request->only('name', 'position', 'address_destination', 'latitude_destination', 'longitude_destination'));
            flash("La zona <b>$request->name</b> ha sido actualizada con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('zonas.edit', $zona->id)
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
    public function destroy(Zone $zona)
    {
        try {
            $this->authorize('delete', $zona);
            $zona->delete();
            flash("La zona <b>$zona->name</b> ha sido eliminada con éxito")->success();
            
            return response()->json([
                'success' => true,
                'message' => "La zona ha sido eliminada con éxito"
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
     * Update all zones position.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        try {
            Gate::check('sort-zones');
            
            foreach ($request->zones as $key => $zone_id) {
                $params = array('position' => ($key + 1));
                $this->zoneRepository->update($zone_id, $params);
            }
            
            flash("Las zonas han sido ordenadas con éxito")->success();
            
            return response()->json([
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
}
