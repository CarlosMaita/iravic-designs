<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Ingreso de Clientes') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta-description', 'Accede a tu cuenta en Iravic y descubre nuestra colección de moda infantil. Gestiona tus pedidos y favoritos.')">
    <meta name="keywords" content="@yield('meta-keywords', 'login, registro, cuenta cliente, tienda online, moda infantil')">
    <meta name="author" content="Iravic">
    <meta name="robots" content="noindex, nofollow">
    
    @yield('meta-tags')
    
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
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">

    @yield('content') 

    <!-- Vendor scripts -->
    <script src="{{ asset('assets/cartzilla/vendor/simplebar/dist/simplebar.min.js')}}"></script>

    <!-- Bootstrap + Theme scripts -->
    <script src="{{ asset('assets/cartzilla/js/theme.min.js')}}"></script>

    @stack('js')
</body>
</html>