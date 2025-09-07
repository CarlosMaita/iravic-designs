<!-- Sidebar Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed top-0 start-0 h-100 shadow-lg" style="width: 280px; z-index: 1050;">
    <div class="d-flex flex-column h-100 w-100">
        
        <!-- Brand -->
        <div class="navbar-brand d-flex align-items-center justify-content-center py-4 border-bottom border-secondary">
            <a href="{{ route('admin.home') }}" class="text-decoration-none text-white">
                <img src="{{ asset('img/logo-white.png') }}" width="40" height="40" alt="Logo" class="me-2">
                <span class="fw-bold">Admin Panel</span>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-grow-1 overflow-auto py-3" data-simplebar>
            <ul class="nav nav-pills flex-column px-3">
                
                <!-- Dashboard -->
                <li class="nav-item mb-1">
                    <a class="nav-link text-white rounded {{ $menuService->isActive($url,"/admin/", false, true) }}" href="{{ route('admin.home') }}">
                        <i class="ci-home me-2"></i> {{ __('dashboard.breadcrumb.home') }}
                    </a>
                </li>

                <!-- Catalog Management -->
                @if (
                    Auth::user()->can('viewany', App\Models\Brand::class) || 
                    Auth::user()->can('viewany', App\Models\Category::class) || 
                    Auth::user()->can('viewany', App\Models\Product::class) || 
                    Auth::user()->can('viewany', App\Models\ProductStockTransfer::class) || 
                    Auth::user()->can('viewany', App\Models\Color::class)
                )
                <li class="nav-item mb-1">
                    <a class="nav-link text-white rounded dropdown-toggle" data-bs-toggle="collapse" href="#catalogMenu" role="button" aria-expanded="false">
                        <i class="ci-menu me-2"></i> {{ __('dashboard.sidebar.catalog') }}
                    </a>
                    <div class="collapse {{ 
                        $menuService->isActive($url,"/admin/catalogo/categorias", false, true) . " " .
                        $menuService->isActive($url,"/admin/catalogo/marcas", false, true) . " " . 
                        $menuService->isActive($url,"/admin/catalogo/productos", false, true) ? 'show' : ''
                    }}" id="catalogMenu">
                        <ul class="nav nav-pills flex-column ms-3">
                            @can('viewany', App\Models\Category::class)
                            <li class="nav-item">
                                <a class="nav-link text-light small {{$menuService->isActive($url,"/admin/catalogo/categorias")}}" href="{{ route('categorias.index') }}">
                                    {{ __('dashboard.sidebar.categories') }}
                                </a>
                            </li>
                            @endcan
                            
                            @can('viewany', App\Models\Brand::class)
                            <li class="nav-item">
                                <a class="nav-link text-light small {{$menuService->isActive($url,"/admin/catalogo/marcas")}}" href="{{ route('marcas.index') }}">
                                    {{ __('dashboard.sidebar.brands') }}
                                </a>
                            </li>
                            @endcan
                            
                            @can('viewany', App\Models\Product::class)
                            <li class="nav-item">
                                <a class="nav-link text-light small {{$menuService->isActive($url,"/admin/catalogo/productos")}}" href="{{ route('productos.index') }}">
                                    {{ __('dashboard.sidebar.products') }}
                                </a>
                            </li>
                            @endcan
                            
                            @can('viewany', App\Models\ProductStockTransfer::class)
                            <li class="nav-item">
                                <a class="nav-link text-light small {{$menuService->isActive($url,"/admin/catalogo/stocks-transferencias")}}" href="{{ route('stock-transferencias.index') }}">
                                    {{ __('dashboard.sidebar.products_transfers') }}
                                </a>
                            </li>
                            @endcan
                            
                            @can('viewany', App\Models\Color::class)
                            <li class="nav-item">
                                <a class="nav-link text-light small {{$menuService->isActive($url,"/admin/catalogo/colors")}}" href="{{ route('colors.index') }}">
                                    {{ __('dashboard.sidebar.colors') }}
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Stock Management -->
                @if (Auth::user()->can('viewany', App\Models\Store::class))
                <li class="nav-item mb-1">
                    <a class="nav-link text-white rounded dropdown-toggle" data-bs-toggle="collapse" href="#stockMenu" role="button" aria-expanded="false">
                        <i class="ci-package me-2"></i> {{ __('dashboard.sidebar.stock') }}
                    </a>
                    <div class="collapse {{ $menuService->isActive($url,"/admin/almacenamiento/depositos", false, true) ? 'show' : '' }}" id="stockMenu">
                        <ul class="nav nav-pills flex-column ms-3">
                            @can('viewany', App\Models\Store::class)
                            <li class="nav-item">
                                <a class="nav-link text-light small {{$menuService->isActive($url,"/admin/almacenamiento/depositos")}}" href="{{ route('depositos.index') }}">
                                    {{ __('dashboard.sidebar.stores') }}
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Customer Management -->
                @if (Auth::user()->can('viewany', App\Models\Customer::class))
                <li class="nav-item mb-1">
                    <a class="nav-link text-white rounded dropdown-toggle" data-bs-toggle="collapse" href="#customerMenu" role="button" aria-expanded="false">
                        <i class="ci-user me-2"></i> {{ __('dashboard.sidebar.customers-management') }}
                    </a>
                    <div class="collapse {{ $menuService->isActive($url,"/admin/gestion-clientes/clientes", false, true) ? 'show' : '' }}" id="customerMenu">
                        <ul class="nav nav-pills flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link text-light small {{$menuService->isActive($url,"/admin/gestion-clientes/clientes")}}" href="{{ route('clientes.index') }}">
                                    {{ __('dashboard.sidebar.customers') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Configuration -->
                @if (
                    Auth::user()->can('viewany', App\Models\Config::class) || 
                    Auth::user()->can('viewany', App\Models\Permission::class) || 
                    Auth::user()->can('viewany', App\Models\Role::class) || 
                    Auth::user()->can('viewany', App\User::class) ||
                    Auth::user()->can('viewany', App\Models\Banner::class)
                )
                <li class="nav-item mb-1">
                    <a class="nav-link text-white rounded dropdown-toggle" data-bs-toggle="collapse" href="#configMenu" role="button" aria-expanded="false">
                        <i class="ci-settings me-2"></i> {{ __('dashboard.sidebar.settings') }}
                    </a>
                    <div class="collapse {{
                        $menuService->isActive($url,"/admin/config/usuarios", false, true) . " " .
                        $menuService->isActive($url,"/admin/config/roles", false, true) . " " . 
                        $menuService->isActive($url,"/admin/config/permisos", false, true) . " " . 
                        $menuService->isActive($url,"/admin/config/general", false, true) . " " .
                        $menuService->isActive($url,"/admin/banners", false, true) ? 'show' : ''
                    }}" id="configMenu">
                        <ul class="nav nav-pills flex-column ms-3">
                            @can('viewany', App\Models\Config::class)
                            <li class="nav-item">
                                <a class="nav-link text-light small {{$menuService->isActive($url,"/admin/config/general")}}" href="{{ route('general.index') }}">
                                    {{ __('dashboard.sidebar.general') }}
                                </a>
                            </li>
                            @endcan
                            
                            @can('viewany', App\Models\Banner::class)
                            <li class="nav-item">
                                <a class="nav-link text-light small {{$menuService->isActive($url,'/admin/banners')}}" href="{{ route('banners.index') }}">
                                    Banners
                                </a>
                            </li>
                            @endcan
                            
                            @can('viewany', App\User::class)
                            <li class="nav-item">
                                <a class="nav-link text-light small {{$menuService->isActive($url,"/admin/config/usuarios")}}" href="{{ route('usuarios.index') }}">
                                    {{ __('dashboard.sidebar.users') }}
                                </a>
                            </li>
                            @endcan
                            
                            @if (Auth::user()->can('viewany', App\Models\Role::class) || Auth::user()->can('viewany', App\Models\Permission::class))
                            <li class="nav-item">
                                <span class="nav-link text-light small fw-bold">{{ __('dashboard.sidebar.privileges') }}</span>
                                <ul class="nav nav-pills flex-column ms-3">
                                    @can('viewany', App\Models\Role::class)
                                    <li class="nav-item">
                                        <a class="nav-link text-light small" href="{{ route('roles.index') }}">
                                            {{ __('dashboard.sidebar.roles') }}
                                        </a>
                                    </li>
                                    @endcan
                                    
                                    @can('viewany', App\Models\Permission::class)
                                    <li class="nav-item">
                                        <a class="nav-link text-light small" href="{{ route('permisos.index') }}">
                                            {{ __('dashboard.sidebar.permissions') }}
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif
                
            </ul>
        </div>
    </div>
</nav>