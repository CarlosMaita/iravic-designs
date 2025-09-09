<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="description" content="Panel de Cliente - {{ config('app.name') }}">
    <meta name="author" content="Iravic">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/cartzilla/app-icons/icon-32x32.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('assets/cartzilla/app-icons/icon-180x180.png') }}">

    <!-- Theme switcher (color modes) -->
    <script src="{{ asset('assets/cartzilla/js/theme-switcher.js')}}"></script>

    <!-- Preloaded local web font (Inter) -->
    <link rel="preload" href="{{ asset('assets/cartzilla/fonts/inter-variable-latin.woff2')}}" as="font" type="font/woff2" crossorigin>

    <!-- Font icons -->
    <link rel="preload" href="{{ asset('assets/cartzilla/icons/cartzilla-icons.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/icons/cartzilla-icons.min.css')}}">

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/vendor/simplebar/dist/simplebar.min.css')}}">

    <!-- Bootstrap + Theme styles -->
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/css/theme.min.css')}}">

    @stack('css')
</head>
<body>

    <!-- Navigation -->
    <header class="navbar navbar-expand-lg bg-light fixed-top shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('customer.dashboard') }}">
                <h6 class="m-0 text-uppercase" style="font-family: Roboto, sans-serif; letter-spacing: 3px;">Iravic Designs</h6>
            </a>

            <!-- Mobile menu toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                            <i class="ci-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}" href="{{ route('customer.profile') }}">
                            <i class="ci-user me-2"></i>Mi Perfil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}" href="{{ route('customer.orders.index') }}">
                            <i class="ci-shopping-bag me-2"></i>Mis Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.favorites.*') ? 'active' : '' }}" href="{{ route('customer.favorites.index') }}">
                            <i class="ci-heart me-2"></i>Favoritos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ecommerce.home') }}">
                            <i class="ci-store me-2"></i>Tienda
                        </a>
                    </li>
                </ul>

                <!-- User menu -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ci-user me-2"></i>{{ Auth::guard('customer')->user()->name }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="userMenu">
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                <i class="ci-user me-2"></i>Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.orders.index') }}">
                                <i class="ci-shopping-bag me-2"></i>Mis Pedidos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.favorites.index') }}">
                                <i class="ci-heart me-2"></i>Favoritos
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="ci-sign-out me-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="pt-5 mt-4">
        <div class="container py-4">
            @if(Session::has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0 text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Vendor scripts -->
    <script src="{{ asset('assets/cartzilla/vendor/simplebar/dist/simplebar.min.js')}}"></script>

    <!-- Bootstrap + Theme scripts -->
    <script src="{{ asset('assets/cartzilla/js/theme.min.js')}}"></script>

    @stack('js')
</body>
</html>