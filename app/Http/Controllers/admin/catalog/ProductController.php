<?php

namespace App\Http\Controllers\admin\catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\Catalog\ProductRequest;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Repositories\Eloquent\BrandRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\ProductRepository;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public $brandRepository;

    public $categoryRepository;

    public $productRepository;

    public function __construct(BrandRepository $brandRepository, CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', 'App\Models\Product');

        if ($request->ajax()) {
            $criteria = $request->only('brand', 'category', 'color', 'gender', 'size');
            $products = $this->productRepository->onlyPrincipals($criteria);
            return Datatables::of($products)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '';

                        if (Auth::user()->can('view', $row)) {
                            $btn .= '<a href="'. route('productos.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        }

                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<a href="'. route('productos.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-resource" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        $brands = $this->brandRepository->all();
        $categories = $this->categoryRepository->all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('dashboard.catalog.products.index')
            ->withColors($colors)
            ->withSizes($sizes)
            ->withBrands($brands)
            ->withCategories($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', 'App\Models\Product');
        $brands = $this->brandRepository->all();
        $categories = $this->categoryRepository->all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('dashboard.catalog.products.create')
                ->withBrands($brands)
                ->withCategories($categories)
                ->withColors($colors)
                ->withProduct(new Product())
                ->withSizes($sizes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        try {
            $this->authorize('create', 'App\Models\Product');
            DB::beginTransaction();
            $this->productRepository->createByRequest($request);
            DB::commit();
            flash("El producto <b>$request->name</b> ha sido creado con éxito")->success();

            return response()->json([
                    'success' => true,
                    'data' => [
                        'redirect' => route('productos.index')
                    ]
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => __('dashboard.general.operation_error'),
                'error' => [
                    'e' => $e->getMessage(),
                    'trace' => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $producto)
    {
        $this->authorize('view', $producto);

        if ($request->ajax()) {
            $producto->load('brand', 'category', 'color', 'size');
            return response()->json($producto);
        }

        return view('dashboard.catalog.products.show')
                ->withProduct($producto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $producto)
    {
        $this->authorize('update', $producto);
        $producto->load('brand', 'category', 'color', 'images', 'product_combinations.color', 'product_combinations.size', 'size');
        $brands = $this->brandRepository->all();
        $categories = $this->categoryRepository->all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('dashboard.catalog.products.edit')
                ->withBrands($brands)
                ->withCategories($categories)
                ->withColors($colors)
                ->withProduct($producto)
                ->withSizes($sizes);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $producto)
    {
        try {
            $this->authorize('update', $producto);
            DB::beginTransaction();
            $this->productRepository->updateByRequest($producto->id, $request);
            DB::commit();
            flash("El producto <b>$request->name</b> ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('productos.edit', $producto->id)
                ]
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('dashboard.general.operation_error'),
                'error' => [
                    'e' => $e->getMessage(),
                    'trace' => $e->getTrace()
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
    public function destroy(Product $producto)
    {
        try {
            $this->authorize('delete', $producto);
            DB::beginTransaction();
            $producto->delete();
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "El producto ha sido eliminado con éxito"
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
