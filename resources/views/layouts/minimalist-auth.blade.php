<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    
    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Iniciar Sesión') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta-description', 'Accede a tu cuenta en Iravic.')">
    <meta name="keywords" content="@yield('meta-keywords', 'login, cuenta, tienda online')">
    <meta name="author" content="Iravic">
    <meta name="robots" content="noindex, nofollow">
    
    @yield('meta-tags')
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/cartzilla/app-icons/icon-32x32.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('assets/cartzilla/app-icons/icon-180x180.png') }}">

    <!-- Theme switcher -->
    <script src="{{ asset('assets/cartzilla/js/theme-switcher.js')}}"></script>

    <!-- Font -->
    <link rel="preload" href="{{ asset('assets/cartzilla/fonts/inter-variable-latin.woff2')}}" as="font" type="font/woff2" crossorigin>

    <!-- Icons -->
    <link rel="preload" href="{{ asset('assets/cartzilla/icons/cartzilla-icons.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/icons/cartzilla-icons.min.css')}}">

    <!-- Bootstrap + Theme -->
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/css/theme.min.css')}}" id="theme-styles">

    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background-color: #f8f9fa;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        @media (min-width: 576px) {
            .auth-card {
                padding: 2.5rem;
            }
        }
    </style>

    @stack('css')
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap + Theme scripts -->
    <script src="{{ asset('assets/cartzilla/js/theme.min.js')}}"></script>

    @stack('js')
</body>
</html>
