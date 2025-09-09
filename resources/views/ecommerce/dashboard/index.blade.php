@extends('ecommerce.dashboard.base')

@section('title', 'Dashboard')

@push('css')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 1rem;
        border: none;
    }
    .stat-card {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .stat-icon {
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>
@endpush

@section('content')
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2">¡Bienvenido/a, {{ $customer->name }}!</h1>
                            <p class="mb-0 opacity-75">Desde aquí puedes gestionar tu perfil, ver tus pedidos y explorar nuestros productos.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="ci-user" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                            <i class="ci-shopping-bag"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-1">Pedidos Realizados</h6>
                            <h4 class="mb-0">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                            <i class="ci-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-1">Estado de Cuenta</h6>
                            <h5 class="mb-0 text-success">{{ $customer->qualification ?? 'Bueno' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="ci-time"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-1">Miembro desde</h6>
                            <h6 class="mb-0">{{ $customer->created_at->format('M Y') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Acciones Rápidas</h5>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('customer.profile') }}" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="ci-user text-primary me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Ver Perfil</h6>
                                        <small class="text-muted">Gestiona tu información</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('ecommerce.catalog') }}" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="ci-store text-success me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Explorar Tienda</h6>
                                        <small class="text-muted">Ver productos</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('customer.orders.index') }}" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="ci-shopping-bag text-warning me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Mis Pedidos</h6>
                                        <small class="text-muted">Historial de compras</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('customer.payments.index') }}" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="ci-credit-card text-info me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Mis Pagos</h6>
                                        <small class="text-muted">Estado de pagos</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('customer.favorites.index') }}" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="ci-heart text-danger me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Mis Favoritos</h6>
                                        <small class="text-muted">Productos guardados</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="ci-support text-info me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Soporte</h6>
                                        <small class="text-muted">Ayuda y contacto</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actividad Reciente</h5>
                    <div class="text-center py-4">
                        <i class="ci-shopping-bag text-muted mb-3" style="font-size: 3rem;"></i>
                        <h6 class="text-muted">No tienes pedidos aún</h6>
                        <p class="text-muted">¡Explora nuestra tienda y realiza tu primera compra!</p>
                        <a href="{{ route('ecommerce.catalog') }}" class="btn btn-primary">
                            <i class="ci-store me-2"></i>Ir a la Tienda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection