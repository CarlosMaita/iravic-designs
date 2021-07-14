<?php

namespace App\Http\Controllers\admin\config;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\PermissionRepository;
use DataTables;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->middleware('can:viewany,App\Models\Permission');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = $this->permissionRepository->all();
            return Datatables::of($permissions)
                    ->make(true);
        }

        return view('dashboard.config.permissions.index');
    }
}
