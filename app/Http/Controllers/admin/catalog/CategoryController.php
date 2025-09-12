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
    {        if ($request->ajax()) {
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
    {        return view('dashboard.catalog.categories.create')
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
        try {            $categoryData = $request->only(['name', 'base_category_id', 'bg_banner', 'slug']);

            // Si no se envía slug, se genera automáticamente (el modelo también lo hace)
            if (empty($categoryData['slug'])) {
                $categoryData['slug'] = \Illuminate\Support\Str::slug($categoryData['name']);
            }
    
            // Handle image upload for image_banner
            if ($request->hasFile('image_banner')) {
                $imagePath = $request->file('image_banner')->store('categories', 'public');
                $categoryData['image_banner'] = $imagePath;
            }
    
            $this->categoryRepository->create($categoryData);
    
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
                'error' => $e->getMessage()
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
    {        return view('dashboard.catalog.categories.edit', [
            'category' => $categoria,
            'base_categories' => BaseCategory::all()
        ]);
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
        try {            $categoryData = $request->only(['name', 'base_category_id', 'bg_banner', 'slug']);
    
            // Handle image upload for image_banner
            if ($request->hasFile('image_banner')) {
                $imagePath = $request->file('image_banner')->store('categories', 'public');
                $categoryData['image_banner'] = $imagePath;
            }
    
            $this->categoryRepository->update($categoria->id, $categoryData);
    
            flash("La categoría <b>$request->name</b> ha sido actualizada con éxito")->success();

            return response()->json([
                'success' => true,
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
        try {            $categoria->delete();
            
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
