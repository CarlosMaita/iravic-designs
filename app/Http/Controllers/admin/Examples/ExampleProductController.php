<?php

namespace App\Http\Controllers\admin\Examples;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\Catalog\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador de ejemplo que sigue los estándares de codificación.
 * 
 * Este controlador demuestra:
 * - Uso correcto del patrón MVC
 * - Validación con Form Requests
 * - Inyección de dependencias
 * - Manejo de errores consistente
 */
class ExampleProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        
        // Middleware de autorización
        $this->middleware('auth');
        $this->middleware('can:manage-products');
    }

    /**
     * Mostrar listado de productos.
     */
    public function index(Request $request)
    {
        // Para requests AJAX (DataTables)
        if ($request->ajax()) {
            return $this->getProductsDataTable($request);
        }

        // Vista normal
        $categories = Category::active()->orderBy('name')->get();
        
        return view('admin.examples.products.index', compact('categories'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        
        return view('admin.examples.products.create', compact('categories'));
    }

    /**
     * Almacenar nuevo producto.
     */
    public function store(ProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->create($request->validated());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Producto creado correctamente.',
                    'data' => $product
                ]);
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Producto creado correctamente.');
                
        } catch (\Exception $e) {
            \Log::error('Error creating product: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el producto.'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Error al crear el producto.');
        }
    }

    /**
     * Mostrar producto específico.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images']);
        
        return view('admin.examples.products.show', compact('product'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->orderBy('name')->get();
        
        return view('admin.examples.products.edit', compact('product', 'categories'));
    }

    /**
     * Actualizar producto.
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            $updatedProduct = $this->productService->update($product, $request->validated());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Producto actualizado correctamente.',
                    'data' => $updatedProduct
                ]);
            }

            return redirect()
                ->route('admin.products.show', $product)
                ->with('success', 'Producto actualizado correctamente.');
                
        } catch (\Exception $e) {
            \Log::error('Error updating product: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el producto.'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el producto.');
        }
    }

    /**
     * Eliminar producto.
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $this->productService->delete($product);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Producto eliminado correctamente.'
                ]);
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Producto eliminado correctamente.');
                
        } catch (\Exception $e) {
            \Log::error('Error deleting product: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el producto.'
                ], 500);
            }

            return back()->with('error', 'Error al eliminar el producto.');
        }
    }

    /**
     * Datos para DataTables.
     */
    protected function getProductsDataTable(Request $request): JsonResponse
    {
        $products = Product::with(['category', 'brand'])
            ->when($request->category_id, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy($request->get('order_by', 'created_at'), $request->get('order_dir', 'desc'))
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total()
            ]
        ]);
    }

    /**
     * Activar/desactivar producto vía AJAX.
     */
    public function toggleStatus(Product $product): JsonResponse
    {
        try {
            $product->update(['is_active' => !$product->is_active]);

            return response()->json([
                'success' => true,
                'message' => $product->is_active 
                    ? 'Producto activado correctamente.' 
                    : 'Producto desactivado correctamente.',
                'data' => ['is_active' => $product->is_active]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error toggling product status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del producto.'
            ], 500);
        }
    }
}