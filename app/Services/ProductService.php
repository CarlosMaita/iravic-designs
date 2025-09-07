<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio para manejar la lógica de negocio de productos.
 * 
 * Este servicio encapsula la lógica compleja relacionada con productos,
 * mantiendo los controladores limpios y siguiendo el principio de
 * responsabilidad única.
 */
class ProductService
{
    /**
     * Crear un nuevo producto.
     */
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            // Generar slug único
            $data['slug'] = $this->generateUniqueSlug($data['name']);
            
            // Crear el producto
            $product = Product::create($data);
            
            // Procesar imágenes si existen
            if (isset($data['images'])) {
                $this->processProductImages($product, $data['images']);
            }
            
            // Log de auditoría
            \Log::info('Product created', [
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'data' => $data
            ]);
            
            return $product->fresh(['category', 'brand']);
        });
    }

    /**
     * Actualizar un producto existente.
     */
    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            // Actualizar slug si cambió el nombre
            if (isset($data['name']) && $data['name'] !== $product->name) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $product->id);
            }
            
            // Actualizar el producto
            $product->update($data);
            
            // Procesar imágenes si existen
            if (isset($data['images'])) {
                $this->processProductImages($product, $data['images']);
            }
            
            // Log de auditoría
            \Log::info('Product updated', [
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'changes' => $product->getChanges()
            ]);
            
            return $product->fresh(['category', 'brand']);
        });
    }

    /**
     * Eliminar un producto.
     */
    public function delete(Product $product): bool
    {
        return DB::transaction(function () use ($product) {
            // Eliminar imágenes asociadas
            $this->deleteProductImages($product);
            
            // Soft delete del producto
            $deleted = $product->delete();
            
            // Log de auditoría
            \Log::info('Product deleted', [
                'product_id' => $product->id,
                'user_id' => auth()->id()
            ]);
            
            return $deleted;
        });
    }

    /**
     * Duplicar un producto.
     */
    public function duplicate(Product $product): Product
    {
        return DB::transaction(function () use ($product) {
            // Preparar datos para la duplicación
            $data = $product->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at'], $data['deleted_at']);
            
            // Modificar nombre y slug
            $data['name'] = $data['name'] . ' (Copia)';
            $data['slug'] = $this->generateUniqueSlug($data['name']);
            
            // Crear producto duplicado
            $duplicatedProduct = Product::create($data);
            
            // Duplicar imágenes
            $this->duplicateProductImages($product, $duplicatedProduct);
            
            // Log de auditoría
            \Log::info('Product duplicated', [
                'original_product_id' => $product->id,
                'duplicated_product_id' => $duplicatedProduct->id,
                'user_id' => auth()->id()
            ]);
            
            return $duplicatedProduct->fresh(['category', 'brand']);
        });
    }

    /**
     * Cambiar el estado de múltiples productos.
     */
    public function bulkUpdateStatus(array $productIds, bool $isActive): int
    {
        $updated = Product::whereIn('id', $productIds)
            ->update(['is_active' => $isActive]);
            
        // Log de auditoría
        \Log::info('Bulk product status update', [
            'product_ids' => $productIds,
            'is_active' => $isActive,
            'updated_count' => $updated,
            'user_id' => auth()->id()
        ]);
        
        return $updated;
    }

    /**
     * Generar slug único para el producto.
     */
    protected function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Verificar si un slug ya existe.
     */
    protected function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Product::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Procesar imágenes del producto.
     */
    protected function processProductImages(Product $product, array $images): void
    {
        foreach ($images as $image) {
            if (isset($image['file'])) {
                $path = Storage::disk('products')->put(
                    $product->id, 
                    $image['file']
                );
                
                $product->images()->create([
                    'path' => $path,
                    'alt_text' => $image['alt_text'] ?? $product->name,
                    'is_primary' => $image['is_primary'] ?? false
                ]);
            }
        }
        
        // Asegurar que solo haya una imagen primaria
        $this->ensureSinglePrimaryImage($product);
    }

    /**
     * Eliminar imágenes del producto.
     */
    protected function deleteProductImages(Product $product): void
    {
        foreach ($product->images as $image) {
            Storage::disk('products')->delete($image->path);
            $image->delete();
        }
    }

    /**
     * Duplicar imágenes de un producto a otro.
     */
    protected function duplicateProductImages(Product $originalProduct, Product $duplicatedProduct): void
    {
        foreach ($originalProduct->images as $image) {
            $originalPath = $image->path;
            $newPath = str_replace(
                $originalProduct->id, 
                $duplicatedProduct->id, 
                $originalPath
            );
            
            // Copiar archivo
            Storage::disk('products')->copy($originalPath, $newPath);
            
            // Crear registro de imagen
            $duplicatedProduct->images()->create([
                'path' => $newPath,
                'alt_text' => $image->alt_text,
                'is_primary' => $image->is_primary
            ]);
        }
    }

    /**
     * Asegurar que solo hay una imagen primaria por producto.
     */
    protected function ensureSinglePrimaryImage(Product $product): void
    {
        $primaryImages = $product->images()->where('is_primary', true)->get();
        
        if ($primaryImages->count() > 1) {
            // Mantener solo la primera como primaria
            $primaryImages->skip(1)->each(function ($image) {
                $image->update(['is_primary' => false]);
            });
        } elseif ($primaryImages->count() === 0 && $product->images()->count() > 0) {
            // Si no hay imagen primaria, hacer la primera como primaria
            $product->images()->first()->update(['is_primary' => true]);
        }
    }

    /**
     * Obtener productos relacionados.
     */
    public function getRelatedProducts(Product $product, int $limit = 4): \Illuminate\Database\Eloquent\Collection
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Buscar productos por criterios específicos.
     */
    public function searchProducts(array $criteria): \Illuminate\Database\Eloquent\Builder
    {
        $query = Product::query();
        
        if (!empty($criteria['search'])) {
            $query->where(function ($q) use ($criteria) {
                $q->where('name', 'like', "%{$criteria['search']}%")
                  ->orWhere('description', 'like', "%{$criteria['search']}%");
            });
        }
        
        if (!empty($criteria['category_id'])) {
            $query->where('category_id', $criteria['category_id']);
        }
        
        if (!empty($criteria['brand_id'])) {
            $query->where('brand_id', $criteria['brand_id']);
        }
        
        if (isset($criteria['price_min'])) {
            $query->where('price', '>=', $criteria['price_min']);
        }
        
        if (isset($criteria['price_max'])) {
            $query->where('price', '<=', $criteria['price_max']);
        }
        
        if (isset($criteria['is_featured'])) {
            $query->where('is_featured', $criteria['is_featured']);
        }
        
        if (isset($criteria['is_active'])) {
            $query->where('is_active', $criteria['is_active']);
        }
        
        return $query;
    }
}