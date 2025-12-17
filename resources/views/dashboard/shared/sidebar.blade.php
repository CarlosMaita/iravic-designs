<div class="c-sidebar-brand test">
    <a href="{{ route('admin.home') }}">
        {{-- <img class="c-sidebar-brand-full" src="{{ url('/assets/brand/coreui-base-white.svg') }}" width="118" height="46" alt="CoreUI Logo"> --}}
        <img class="c-sidebar-brand-minimized" src="{{ asset('img/logo-white.png') }}" width="40" height="40" alt="Logo">
    </a>
</div>
<ul class="c-sidebar-nav">
    {{-- dashboard --}}
    <li class="c-sidebar-nav-item {{ $menuService->isActive($url,"/admin/", false, true) }}">
        <a class="c-sidebar-nav-link" href="{{ route('admin.home') }}"><i class="cil-compass c-sidebar-nav-icon"></i> {{ __('dashboard.breadcrumb.home') }}</a>
    </li>

    {{-- Catalog links --}}
    <li class="c-sidebar-nav-dropdown {{
        $menuService->isActive($url,"/admin/catalogo/categorias", false, true) . " " .
        $menuService->isActive($url,"/admin/catalogo/marcas", false, true) . " " . 
        $menuService->isActive($url,"/admin/catalogo/productos", false, true)
    }}">
        <a class="c-sidebar-nav-dropdown-toggle" href="#"><i class="cil-spreadsheet c-sidebar-nav-icon"></i>{{ __('dashboard.sidebar.catalog') }}</a>
        <ul class="c-sidebar-nav-dropdown-items">
            {{-- Categorias --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/catalogo/categorias")}}" href="{{ route('categorias.index') }}"></span>{{ __('dashboard.sidebar.categories') }}</a>
            </li>
            {{-- Marcas --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/catalogo/marcas")}}" href="{{ route('marcas.index') }}"></span>{{ __('dashboard.sidebar.brands') }}</a>
            </li>
            {{-- Productos --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/catalogo/productos")}}" href="{{ route('productos.index') }}"></span>{{ __('dashboard.sidebar.products') }}</a>
            </li>
            {{-- Transferencias Stock --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/catalogo/stocks-transferencias")}}" href="{{ route('stock-transferencias.index') }}"></span>{{ __('dashboard.sidebar.products_transfers') }}</a>
            </li>
            {{-- Colors --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/catalogo/colors")}}" href="{{ route('colors.index') }}"></span>{{ __('dashboard.sidebar.colors') }}</a>
            </li>
        </ul>
    </li>

    {{-- Stock Management links --}}
    <li class="c-sidebar-nav-dropdown {{
        $menuService->isActive($url,"/admin/almacenamiento/depositos", false, true) 
    }}">
        <a class="c-sidebar-nav-dropdown-toggle" href="#"><i class="cil-spreadsheet c-sidebar-nav-icon"></i>{{ __('dashboard.sidebar.stock') }}</a>
        <ul class="c-sidebar-nav-dropdown-items">
            {{-- Depositos --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/almacenamiento/depositos")}}" href="{{ route('depositos.index') }}"></span>{{ __('dashboard.sidebar.stores') }}</a>
            </li>
        </ul>
    </li>

    {{-- Customer Management links --}}
    <li class="c-sidebar-nav-dropdown {{ $menuService->isActive($url,"/admin/gestion-clientes/clientes", false, true) }}">
        <a class="c-sidebar-nav-dropdown-toggle" href="#"><i class="cil-contact c-sidebar-nav-icon"></i>{{ __('dashboard.sidebar.customers-management') }}</a>
        <ul class="c-sidebar-nav-dropdown-items">
            {{-- customers --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/gestion-clientes/clientes")}}" href="{{ route('clientes.index') }}"></span>{{ __('dashboard.sidebar.customers') }}</a>
            </li>
        </ul>
    </li>

    {{-- Orders Management links --}}
    <li class="c-sidebar-nav-dropdown {{ 
        $menuService->isActive($url,"/admin/ordenes", false, true) . " " .
        $menuService->isActive($url,"/admin/pagos", false, true) . " " .
        $menuService->isActive($url,"/admin/metodos-pago", false, true)
    }}">
        <a class="c-sidebar-nav-dropdown-toggle" href="#"><i class="cil-cart c-sidebar-nav-icon"></i>Órdenes</a>
        <ul class="c-sidebar-nav-dropdown-items">
            {{-- Orders --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/ordenes")}}" href="{{ route('admin.orders.index') }}"><span class="c-sidebar-nav-icon"></span>Órdenes</a>
            </li>
            {{-- Payments --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/pagos")}}" href="{{ route('admin.payments.index') }}"><span class="c-sidebar-nav-icon"></span>Pagos</a>
            </li>
            {{-- Payment Methods --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/metodos-pago")}}" href="{{ route('admin.payment-methods.index') }}"><span class="c-sidebar-nav-icon"></span>Métodos de Pago</a>
            </li>
        </ul>
    </li>

    {{-- Configuration links --}}
    <li class="c-sidebar-nav-dropdown {{
        $menuService->isActive($url,"/admin/config/usuarios", false, true) . " " .
        $menuService->isActive($url,"/admin/config/general", false, true) . " " .
        $menuService->isActive($url,"/admin/config/tasa-cambio", false, true) . " " .
        $menuService->isActive($url,"/admin/banners", false, true) . " " .
        $menuService->isActive($url,"/admin/ofertas-especiales", false, true)
    }}">
        <a class="c-sidebar-nav-dropdown-toggle" href="#"><i class="cil-cog c-sidebar-nav-icon"></i>{{ __('dashboard.sidebar.settings') }}</a>
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/config/general")}}" href="{{ route('general.index') }}"></span>{{ __('dashboard.sidebar.general') }}</a>
            </li>
            {{-- Exchange Rate --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/config/tasa-cambio")}}" href="{{ route('admin.exchange-rate.index') }}">
                    <span class="c-sidebar-nav-icon"></span> Tasa de Cambio
                </a>
            </li>
            {{-- Banner CRUD --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,'/admin/banners')}}" href="{{ route('banners.index') }}">
                    <span class="c-sidebar-nav-icon"></span> Banners
                </a>
            </li>
            {{-- Special Offers CRUD --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,'/admin/ofertas-especiales')}}" href="{{ route('special-offers.index') }}">
                    <span class="c-sidebar-nav-icon"></span> Ofertas Especiales
                </a>
            </li>
            {{-- Users --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/config/usuarios")}}" href="{{ route('usuarios.index') }}"></span>{{ __('dashboard.sidebar.users') }}</a>
            </li>
        </ul>
    </li>

</ul>
<button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>