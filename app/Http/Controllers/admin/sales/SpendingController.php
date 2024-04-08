<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\SpendingRequest;
use App\Models\Spending;
use App\Repositories\Eloquent\SpendingRepository;
use App\Services\Images\ImageService;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpendingController extends Controller
{
    public $spendingRepository;

    public function __construct(SpendingRepository $spendingRepository)
    {
        $this->spendingRepository = $spendingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Spending');

        if ($request->ajax()) {
            if ($request->box) {
                $results = $this->spendingRepository->all(array('box' => $request->box));
            } else {
                $results = array();
            }
            
            return Datatables::of($results)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<div style="display:flex">';
                        $boxIsMine = Auth::user()->id ==  $row->user_id ?  true : false;

                        if (Auth::user()->can('update', $row) && !$row->box->isClosed() && $boxIsMine ) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-success btn-action-icon edit-spending mb-2" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></button>';
                        }

                        if (Auth::user()->can('delete', $row) && !$row->box->isClosed() && $boxIsMine) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-spending mb-2" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $btn .= '</div>';

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
    public function store(SpendingRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Spending');
            $attributes = array_merge(
                array('picture' => ImageService::save(Spending::DISK, $request->file('picture'))),
                $request->only('box_id', 'user_id', 'amount', 'comment', 'date')
            );
            $this->spendingRepository->create($attributes);

            return response()->json([
                'success' => true,
                'message' => 'El Gasto ha sido creado con éxito'
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
    public function show(Request $request, Spending $spending)
    {
        $this->authorize('view', $spending);
        if ($request->ajax()) {
            return response()->json($spending);
        }
        
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Spending $gasto)
    {
        $this->authorize('update', $gasto);

        if ($request->ajax()) {
            return response()->json($gasto);
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
    public function update(SpendingRequest $request, Spending $gasto)
    {
        try {
            $this->authorize('update', $gasto);
            $attributes = array_merge(
                array('picture' => ImageService::updateImage(Spending::DISK, $gasto->picture, $request->picture, $request->delete_picture)),
                $attributes = $request->only('amount', 'comment')
            );
            $this->spendingRepository->update($gasto->id, $attributes);
            flash("El Gasto ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
                'message' => 'El Gasto ha sido actualizado con éxito'
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
    public function destroy(Spending $gasto)
    {
        try {
            $this->authorize('delete', $gasto);
            $gasto->delete();
            
            return response()->json([
                'success' => true,
                'message' => "El Gasto ha sido eliminado con éxito"
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
