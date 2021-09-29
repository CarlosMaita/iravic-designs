<div class="c-sidebar-brand test">
    <a href="{{ route('admin.home') }}">
        {{-- <img class="c-sidebar-brand-full" src="{{ url('/assets/brand/coreui-base-white.svg') }}" width="118" height="46" alt="CoreUI Logo"> --}}
        <img class="c-sidebar-brand-minimized" src="{{ asset('img/logo-white.png') }}" width="40" height="40" alt="Logo">
    </a>
</div>
<ul class="c-sidebar-nav">
    {{-- Schedule link --}}
    @if (Auth::user()->can('viewany', App\Models\Schedule::class))
    <li class="c-sidebar-nav-item {{ $menuService->isActive($url,"/admin/gestion-agendas/agendas", false, true) }}">
        <a class="c-sidebar-nav-link" href="{{ route('agendas.index') }}"><i class="cil-calendar-check c-sidebar-nav-icon"></i> {{ __('dashboard.sidebar.schedules') }}</a>
    </li>
    @endif
    {{-- Orders Management links --}}
    @if (
        Auth::user()->can('viewany', App\Models\Box::class) ||
        Auth::user()->can('viewany', App\Models\Order::class) ||
        Auth::user()->can('viewany', App\Models\Refund::class)
    )
        <li class="c-sidebar-nav-dropdown {{
            $menuService->isActive($url,"/admin/cajas-ventas/cajas", false, true) . " " .
            $menuService->isActive($url,"/admin/cajas-ventas/ventas", false, true) . " " .
            $menuService->isActive($url,"/admin/cajas-ventas/devoluciones", false, true)
        }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#"><i class="cil-calculator c-sidebar-nav-icon"></i>{{ __('dashboard.sidebar.boxes-orders') }}</a>
            <ul class="c-sidebar-nav-dropdown-items">
                {{-- Boxes --}}
                @can('viewany', App\Models\Box::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/cajas-ventas/cajas")}}" href="{{ route('cajas.index') }}"></span>{{ __('dashboard.sidebar.boxes') }}</a>
                    </li>
                @endcan
                {{-- Refunds --}}
                @can('viewany', App\Models\Refund::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/cajas-ventas/devoluciones")}}" href="{{ route('devoluciones.index') }}"></span>{{ __('dashboard.sidebar.refunds') }}</a>
                    </li>
                @endcan
                {{-- Orders --}}
                @can('viewany', App\Models\Order::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/cajas-ventas/ventas")}}" href="{{ route('ventas.index') }}"></span>{{ __('dashboard.sidebar.orders') }}</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endif
    {{-- Catalog links --}}
    @if (
        Auth::user()->can('viewany', App\Models\Brand::class) || 
        Auth::user()->can('viewany', App\Models\Category::class) || 
        Auth::user()->can('viewany', App\Models\Product::class) || 
        Auth::user()->can('viewany', App\Models\ProductStockTransfer::class)
    )
        <li class="c-sidebar-nav-dropdown {{
            $menuService->isActive($url,"/admin/catalogo/categorias", false, true) . " " .
            $menuService->isActive($url,"/admin/catalogo/marcas", false, true) . " " . 
            $menuService->isActive($url,"/admin/catalogo/productos", false, true)
        }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#"><i class="cil-spreadsheet c-sidebar-nav-icon"></i>{{ __('dashboard.sidebar.catalog') }}</a>
            <ul class="c-sidebar-nav-dropdown-items">
                {{-- Categorias --}}
                @can('viewany', App\Models\Category::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/catalogo/categorias")}}" href="{{ route('categorias.index') }}"></span>{{ __('dashboard.sidebar.categories') }}</a>
                    </li>
                @endcan
                {{-- Marcas --}}
                @can('viewany', App\Models\Brand::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/catalogo/marcas")}}" href="{{ route('marcas.index') }}"></span>{{ __('dashboard.sidebar.brands') }}</a>
                    </li>
                @endcan
                {{-- Productos --}}
                @can('viewany', App\Models\Product::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/catalogo/productos")}}" href="{{ route('productos.index') }}"></span>{{ __('dashboard.sidebar.products') }}</a>
                    </li>
                @endcan
                {{-- Transferencias Stock --}}
                @can('viewany', App\Models\ProductStockTransfer::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/catalogo/stocks-transferencias")}}" href="{{ route('stock-transferencias.index') }}"></span>{{ __('dashboard.sidebar.products_transfers') }}</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endif
    {{-- Customer Management links --}}
    @if (
        Auth::user()->can('viewany', App\Models\Customer::class) ||
        Auth::user()->can('viewany', App\Models\Zone::class)
    )
        <li class="c-sidebar-nav-dropdown {{
            $menuService->isActive($url,"/admin/gestion-clientes/clientes", false, true) . " " .
            $menuService->isActive($url,"/admin/gestion-clientes/zonas", false, true)
        }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#"><i class="cil-contact c-sidebar-nav-icon"></i>{{ __('dashboard.sidebar.customers-management') }}</a>
            <ul class="c-sidebar-nav-dropdown-items">
                {{-- customers --}}
                @can('viewany', App\Models\Customer::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/gestion-clientes/clientes")}}" href="{{ route('clientes.index') }}"></span>{{ __('dashboard.sidebar.customers') }}</a>
                    </li>
                @endcan
                {{-- Zones --}}
                @can('viewany', App\Models\Zone::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/gestion-clientes/zonas")}}" href="{{ route('zonas.index') }}"></span>{{ __('dashboard.sidebar.zones') }}</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endif
    {{-- Configuration links --}}
    @if (
        Auth::user()->can('viewany', App\Models\Config::class) || 
        Auth::user()->can('viewany', App\Models\Permission::class) || 
        Auth::user()->can('viewany', App\Models\Role::class) || 
        Auth::user()->can('viewany', App\User::class)
    )
        <li class="c-sidebar-nav-dropdown {{
            $menuService->isActive($url,"/admin/config/usuarios", false, true) . " " .
            $menuService->isActive($url,"/admin/config/roles", false, true) . " " . 
            $menuService->isActive($url,"/admin/config/permisos", false, true) . " " . 
            $menuService->isActive($url,"/admin/config/general", false, true)
        }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#"><i class="cil-cog c-sidebar-nav-icon"></i>{{ __('dashboard.sidebar.settings') }}</a>
            <ul class="c-sidebar-nav-dropdown-items">
                @can('viewany', App\Models\Config::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/config/general")}}" href="{{ route('general.index') }}"></span>{{ __('dashboard.sidebar.general') }}</a>
                    </li>
                @endcan
                {{--  --}}
                @if (Auth::user()->can('viewany', App\Models\Role::class) || Auth::user()->can('viewany', App\Models\Permission::class))
                    <li class="c-sidebar-nav-dropdown {{
                                $menuService->isActive($url,"/admin/config/roles", false, true) . " " . 
                                $menuService->isActive($url,"/admin/config/permisos", false, true)
                            }}">
                        <a class="c-sidebar-nav-dropdown-toggle" href="#">{{ __('dashboard.sidebar.privileges') }}</a>
                        <ul class="c-sidebar-nav-dropdown-items">
                            @can('viewany', App\Models\Role::class)
                                <li class="c-sidebar-nav-item">
                                    <a class="c-sidebar-nav-link" href="{{ route('roles.index') }}"><span class="c-sidebar-nav-icon"></span>{{ __('dashboard.sidebar.roles') }}</a>
                                </li>
                            @endcan
                            {{--  --}}
                            @can('viewany', App\Models\Permission::class)
                                <li class="c-sidebar-nav-item">
                                    <a class="c-sidebar-nav-link" href="{{ route('permisos.index') }}"><span class="c-sidebar-nav-icon"></span>{{ __('dashboard.sidebar.permissions') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                {{--  --}}
                @can('viewany', App\User::class)
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/config/usuarios")}}" href="{{ route('usuarios.index') }}"></span>{{ __('dashboard.sidebar.users') }}</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endif
</ul>
<button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>