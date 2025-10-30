<!DOCTYPE html>
<html lang="es" data-bs-theme="light" data-pwa="true">
<head>
    <meta charset="utf-8">
    
    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Ingreso de Clientes') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta-description', 'Accede a tu cuenta en Iravic y descubre nuestra colección de moda infantil. Gestiona tus pedidos y favoritos.')">
    <meta name="keywords" content="@yield('meta-keywords', 'login, registro, cuenta cliente, tienda online, moda infantil')">
    <meta name="author" content="Iravic">
    <meta name="robots" content="noindex, nofollow">
    
    @yield('meta-tags')
    
    <!-- Webmanifest + Favicon / App icons -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="icon" type="image/png" href="{{ asset('assets/cartzilla/app-icons/icon-32x32.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('assets/cartzilla/app-icons/icon-180x180.png') }}">

    <!-- Theme switcher (color modes) -->
    <script src="{{ asset('assets/cartzilla/js/theme-switcher.js')}}"></script>

    <!-- Preloaded local web font (Inter) -->
    <link rel="preload" href="{{ asset('assets/cartzilla/fonts/inter-variable-latin.woff2')}}" as="font" type="font/woff2" crossorigin>

    <!-- Font icons -->
    <link rel="preload" href="{{ asset('assets/cartzilla/icons/cartzilla-icons.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/icons/cartzilla-icons.min.css')}}">

    <!-- Bootstrap + Theme styles -->
    <link rel="preload" href="{{ asset('assets/cartzilla/css/theme.min.css')}}" as="style">
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/css/theme.min.css')}}" id="theme-styles">

    @stack('css')
</head>

<!-- Body -->
<body>

    <!-- Page content -->
    <main class="content-wrapper w-100 px-3 ps-lg-5 pe-lg-4 mx-auto" style="max-width: 1920px">
      <div class="d-lg-flex">

        @yield('content') 

        <!-- Right cover area -->
        @hasSection('cover')
          @yield('cover')
        @else
          <!-- Default cover image visible on screens > 992px wide (lg breakpoint) -->
          <div class="d-none d-lg-block w-100 py-4 ms-auto" style="max-width: 1034px">
            <div class="d-flex flex-column justify-content-end h-100 rounded-5 overflow-hidden">
              <span class="position-absolute top-0 start-0 w-100 h-100 d-none-dark" style="background: linear-gradient(-90deg, #accbee 0%, #e7f0fd 100%)"></span>
              <span class="position-absolute top-0 start-0 w-100 h-100 d-none d-block-dark" style="background: linear-gradient(-90deg, #1b273a 0%, #1f2632 100%)"></span>
              <div class="ratio position-relative z-2" style="--cz-aspect-ratio: calc(1030 / 1032 * 100%)">
                <img src="{{ asset('assets/cartzilla/img/account/cover.png') }}" alt="Iravic Designs">
              </div>
            </div>
          </div>
        @endif
      </div>
    </main>

    <!-- Bootstrap + Theme scripts -->
    <script src="{{ asset('assets/cartzilla/js/theme.min.js')}}"></script>

    @stack('js')
</body>
</html>