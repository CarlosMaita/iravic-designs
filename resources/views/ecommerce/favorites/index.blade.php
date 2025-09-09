@extends('ecommerce.dashboard.base')

@section('title', 'Mis Favoritos')

@push('css')
<style>
    .product-card {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .product-image {
        height: 200px;
        object-fit: cover;
        background-color: #f8f9fa;
    }
    .favorite-btn {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        z-index: 2;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .favorite-btn:hover {
        background: white;
        transform: scale(1.1);
    }
    .favorite-btn.active {
        color: #dc3545;
    }
    .price-tag {
        font-weight: 600;
        color: #198754;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Mis Favoritos</h1>
            <p class="text-muted mb-0">Productos que has guardado para más tarde</p>
        </div>
        @if($favorites->count() > 0)
            <div class="text-muted">
                {{ $favorites->total() }} producto{{ $favorites->total() != 1 ? 's' : '' }}
            </div>
        @endif
    </div>

    @if($favorites->count() > 0)
        <!-- Products Grid -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
            @foreach($favorites as $product)
                <div class="col">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            @if($product->images->count() > 0)
                                <img src="{{ $product->images->first()->url ?? asset('img/no_image.jpg') }}" 
                                     class="card-img-top product-image" 
                                     alt="{{ $product->name }}">
                            @else
                                <div class="card-img-top product-image d-flex align-items-center justify-content-center bg-light">
                                    <i class="ci-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            
                            <!-- Favorite Button -->
                            <button type="button" 
                                    class="btn favorite-btn active" 
                                    data-product-id="{{ $product->id }}"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="left"
                                    title="Remover de favoritos"
                                    onclick="toggleFavorite({{ $product->id }}, this)">
                                <i class="ci-heart-filled"></i>
                            </button>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">
                                <a href="{{ route('ecommerce.product.detail', $product->slug ?? $product->id) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $product->name }}
                                </a>
                            </h5>
                            
                            @if($product->description)
                                <p class="card-text text-muted small mb-2">
                                    {{ Str::limit($product->description, 80) }}
                                </p>
                            @endif
                            
                            <div class="mt-auto">
                                @if($product->category)
                                    <div class="d-flex align-items-center mb-2">
                                        <small class="text-muted">
                                            <i class="ci-tag me-1"></i>{{ $product->category->name }}
                                        </small>
                                    </div>
                                @endif
                                
                                @if($product->price)
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="price-tag h6 mb-0">${{ number_format($product->price, 0, ',', '.') }}</span>
                                        <a href="{{ route('ecommerce.product.detail', $product->slug ?? $product->id) }}" 
                                           class="btn btn-primary btn-sm">
                                            Ver Producto
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('ecommerce.product.detail', $product->slug ?? $product->id) }}" 
                                       class="btn btn-primary btn-sm w-100">
                                        Ver Producto
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($favorites->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $favorites->links() }}
            </div>
        @endif

    @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <i class="ci-heart"></i>
                    <h4 class="text-muted mb-3">No tienes productos favoritos aún</h4>
                    <p class="text-muted mb-4">
                        Explora nuestra tienda y guarda los productos que más te gusten haciendo clic en el corazón.
                    </p>
                    <a href="{{ route('ecommerce.catalog') }}" class="btn btn-primary">
                        <i class="ci-store me-2"></i>Explorar Tienda
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('js')
<script>
// CSRF token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

/**
 * Toggle favorite status for a product
 */
async function toggleFavorite(productId, button) {
    const icon = button.querySelector('i');
    const originalClass = icon.className;
    
    // Show loading state
    icon.className = 'ci-refresh spinner-border-sm';
    button.disabled = true;
    
    try {
        const response = await fetch('/api/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ product_id: productId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (data.is_favorite) {
                // Added to favorites
                icon.className = 'ci-heart-filled';
                button.classList.add('active');
                button.setAttribute('title', 'Remover de favoritos');
            } else {
                // Removed from favorites - remove the card from the page
                const card = button.closest('.col');
                card.style.transition = 'opacity 0.3s ease';
                card.style.opacity = '0';
                
                setTimeout(() => {
                    card.remove();
                    
                    // Check if there are no more products
                    const remainingProducts = document.querySelectorAll('.product-card').length;
                    if (remainingProducts === 0) {
                        // Reload the page to show empty state
                        window.location.reload();
                    }
                }, 300);
            }
            
            // Show success message
            showToast(data.message, 'success');
        } else {
            // Restore original state
            icon.className = originalClass;
            showToast(data.message || 'Error al actualizar favoritos', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        icon.className = originalClass;
        showToast('Error de conexión. Inténtalo de nuevo.', 'error');
    } finally {
        button.disabled = false;
    }
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush