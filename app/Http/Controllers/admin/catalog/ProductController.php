<?php

namespace App\Http\Controllers\admin\catalog;

use App\Constants\genderConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\Catalog\ProductRequest;
use App\Http\Requests\admin\Catalog\ProductStockRequest;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Repositories\Eloquent\BrandRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\ProductRepository;
use DataTables;
use Dompdf\Dompdf;
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
            $criteria = $request->only('brand', 'category', 'color', 'gender', 'size', 'price_from', 'price_to');
            // $products = $this->productRepository->onlyPrincipals($criteria);
            $products = $this->productRepository->onlyPrincipalsQuery($criteria);
            
            return datatables()->eloquent($products)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<div style="display:flex">';

                        if (Auth::user()->can('view', $row)) {
                            $btn .= '<button data-id="' . $row->id . '" class="btn btn-sm btn-info btn-action-icon btn-show-stock" title="Ver Stock" data-toggle="tooltip"><i class="fas fa-cubes"></i></button>';
                        }

                        if (Auth::user()->can('view', $row)) {
                            $btn .= '<a href="'. route('productos.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                        }

                        if (Auth::user()->can('update', $row)) {
                            $btn .= '<a href="'. route('productos.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                        }

                        if (Auth::user()->can('delete', $row)) {
                            $btn .= '<button data-id="'. $row->id . '" class="btn btn-sm btn-danger  btn-action-icon delete-resource" title="Eliminar" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $btn .= '</div>';

                        return $btn;
                    })
                    // ->rawColumns(['action'])
                    ->make(true);
        }

        $brands = $this->brandRepository->all();
        $categories = $this->categoryRepository->all();
        $colors = Color::all();
        $sizes = Size::all();
        $genders = genderConstants::ALL;

        return view('dashboard.catalog.products.index')
            ->withColors($colors)
            ->withSizes($sizes)
            ->withBrands($brands)
            ->withGenders($genders)
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
        $genders = genderConstants::ALL;

        return view('dashboard.catalog.products.create')
                ->withBrands($brands)
                ->withCategories($categories)
                ->withColors($colors)
                ->withGenders($genders)
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
                    'trace' => $e->getTrace()
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

            if (isset($request->stocks)) {
                $producto->load('product_combinations.color', 'product_combinations.size');
            }

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
        $genders = genderConstants::ALL;

        return view('dashboard.catalog.products.edit')
                ->withBrands($brands)
                ->withCategories($categories)
                ->withColors($colors)
                ->withGenders($genders)
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
     * Update the product's stock by stock column
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $product_id [Inside Request]
     * @param  int  $stock_column [Inside Request] = Tipo de stock
     * @return \Illuminate\Http\Response
     */
    public function updateStock(ProductStockRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->productRepository->update($request->product_id, array($request->stock_column => $request->stock));
            DB::commit();
            flash("El stock <b>$request->stock_name</b> ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('productos.edit', $request->product_id)
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

    /**
     * Se eliminan combinaciones (Seleccionadas) de un producto
     */
    public function destroyCombinations(Request $request)
    {
        try {
            $ids = explode(',', $request->products);
            if (count($ids)) {
                DB::beginTransaction();
                $this->productRepository->deleteByIds($ids);
                DB::commit();

                return response()->json([
                    'success' => true,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se ha indicado tallas a eliminar.'
                ]);
            }
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
     * Se descarga PDF - Catalogo con los productos y combinaciones existentes que tengan stock
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
        // try {
            $criteria = $request->only('brand', 'category', 'color', 'gender', 'size', 'price_from', 'price_to');
            $products = $this->productRepository->onlyPrincipals($criteria);
            $categories = array();
            $category_id = null;

            /**
             * Los productos se ordenan por categoria
             */
            foreach ($products as $product) {
                if ($product->category_id != $category_id) {
                    $category_id = $product->category_id;
                    $categories[$product->category_id] = array();
                    $categories[$product->category_id]['name'] = optional($product->category)->name;
                    $categories[$product->category_id]['products'] = array();
                }

                if (count($product->product_combinations)) {
                    $combinations = array();

                    /**
                     * Una combinacion no se muestra si:
                     * - No tiene stock de ningun tipo
                     * - No cumple alguno de los filtros en la variable criteria
                     */
                    foreach ($product->product_combinations as $product_combination) {
                        if (
                            $product_combination->stock_depot == 0 
                            && $product_combination->stock_local == 0 
                            && $product_combination->stock_truck == 0
                        ) {
                            break;
                        }

                        if (isset($criteria['color']) && !in_array($product_combination->color_id, $criteria['color'])) {
                            break;
                        }

                        if (isset($criteria['size']) && !in_array($product_combination->size_id, $criteria['size'])) {
                            break;
                        }

                        if (!empty($criteria['price_from']) && $product_combination->getRegularPrice() >= $criteria['price_from']) {
                            break;
                        }

                        if (!empty($criteria['price_to']) && $product_combination->getRegularPrice() >= $criteria['price_to']) {
                            break;
                        }
                        
                        array_push($combinations, $product_combination);
                    }

                    if (count($combinations)) {
                        $product['combinations'] = $combinations;
                        array_push($categories[$product->category_id]['products'], $product);
                    }
                } else if ($product->stock_depot >0 || $product->stock_local >0 || $product->stock_truck >0) {
                    array_push($categories[$product->category_id]['products'], $product);
                }
            }

            // $customPaper = array(0, 0, 5000, 1440);
            $customPaper = array(0, 0, 5000, 650);

            $pdf = \PDF::loadView('pdf/catalog', [
                'categories' => $categories,
                'date' => now()->format('d-m-Y')
            ])
            ->setPaper($customPaper, 'landscape');

            return $pdf->download(config('app.name') . ' - catalogo.pdf');
        // } catch (\Throwable $th) {
        //     flash("Ha ocurrido un error al tratar de descargar el catálogo.")->error();
        //     return back();
        // }
    }
}
