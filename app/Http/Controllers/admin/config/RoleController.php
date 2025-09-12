<?php

namespace App\Http\Controllers\admin\config;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\RoleRequest;
use App\Models\Role;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\RoleRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public $permissionsRepository;

    public $roleRepository;

    public function __construct(PermissionRepository $permissionsRepository, RoleRepository $roleRepository)
    {
        $this->permissionsRepository = $permissionsRepository;
        $this->roleRepository = $roleRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        if ($request->ajax()) {
            $roles = $this->roleRepository->all('superadmin');
            return Datatables::of($roles)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<a href="'. route('roles.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-role" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.config.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        $permissions = $this->permissionsRepository->all();

        return view('dashboard.config.roles.create')
                ->withPermissions($permissions)
                ->withRole(new Role());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        try {            $role = $this->roleRepository->create($request->only('name', 'description', 'is_employee'));
            $role->permissions()->sync($request->permissions);
            flash("El rol <b>$request->name</b> ha sido creado con éxito")->success();

            return response()->json([
                    'success' => true,
                    'data' => [
                        'redirect' => route('roles.index')
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
    public function edit(Role $role)
    {        $permissions = $this->permissionsRepository->all();
        return view('dashboard.config.roles.edit')
                ->withPermissions($permissions)
                ->withRole($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        try {            $this->roleRepository->update($role->id, $request->only('name', 'description', 'is_employee'));
            $role->permissions()->sync($request->permissions);
            flash("El rol <b>$request->name</b> ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('roles.edit', $role->id)
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
    public function destroy(Role $role)
    {
        try {            $role->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Se ha eliminado el rol correctamente"
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
