<?php

namespace App\Http\Controllers\admin\catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\Catalog\CategoryRequest;
use App\Models\BaseCategory;
use App\Models\Category;
use App\Repositories\Eloquent\CategoryRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Category');

        if ($request->ajax()) {
            $categories = $this->categoryRepository->allQuery();

            return datatables()->eloquent($categories)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<a href="'. route('categorias.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-category" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('dashboard.catalog.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', 'App\Models\Category');
        return view('dashboard.catalog.categories.create')
                ->withCategory(new Category())
                ->with( 'base_categories', BaseCategory::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Category');
            $this->categoryRepository->create($request->only(['name', 'base_category_id']));
            flash("La categoría <b>$request->name</b> ha sido creada con éxito")->success();

            return response()->json([
                    'success' => true,
                    'data' => [
                        'redirect' => route('categorias.index')
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
    public function edit(Category $categoria)
    {
        $this->authorize('update', $categoria);
        return view('dashboard.catalog.categories.edit')
                ->withCategory($categoria)
                ->with( 'base_categories', BaseCategory::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $categoria)
    {
        try {
            $this->authorize('update', $categoria);
            $this->categoryRepository->update($categoria->id, $request->only(['name', 'base_category_id']));
            flash("La categoría <b>$request->name</b> ha sido actualizada con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('categorias.edit', $categoria->id)
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
    public function destroy(Category $categoria)
    {
        try {
            $this->authorize('delete', $categoria);
            $categoria->delete();
            
            return response()->json([
                'success' => true,
                'message' => "La categoría ha sido eliminada con éxito"
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
