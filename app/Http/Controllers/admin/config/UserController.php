<?php

namespace App\Http\Controllers\admin\config;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\UserRequest;
use App\Http\Requests\admin\UserDestroyRequest;
use App\Repositories\Eloquent\UserRepository;
use App\User;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        if ($request->ajax()) {
            $users = $this->userRepository->allUsersQuery( Auth::user()->isSuperAdmin() ) ;
            return datatables()->eloquent($users)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<a href="'. route('usuarios.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon edit_user" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-user" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.config.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.config.users.create')
                ->withUser(new User);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $user = $this->userRepository->updateOrCreateByEmail($request->only('name', 'email', 'password', 'deleted_at'));
            flash("El usuario <b>$request->name</b> ha sido creado con éxito")->success();

            return response()->json([
                    'success' => true,
                    'data' => [
                        'redirect' => route('usuarios.index')
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
        return view('dashboard.config.users.edit')
                ->withUser($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $usuario)
    {
        try {
            $this->userRepository->update($usuario->id, $request->only('name', 'email', 'password'));
            flash("El usuario <b>$request->name</b> ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('usuarios.edit', $usuario->id)
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
    public function destroy(UserDestroyRequest $request, User $usuario)
    {
        try {            $usuario->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Se ha eliminado el usuario correctamente"
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
