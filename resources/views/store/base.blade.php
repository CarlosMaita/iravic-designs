<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-pwa="true">
  <head>
    <meta charset="utf-8">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">

    <!-- SEO Meta Tags -->
    <title>Iravic - Tienda de ropa para ñiños</title>
    <meta name="description" content="Tienda de ropa para niños">
    <meta name="keywords" content="Tienda de ropa, ropa para niños, ecommerce, tienda online">
    <meta name="author" content="Iravic">
    <meta name="robots" content="nofollow">

    <!-- Webmanifest + Favicon / App icons -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    {{-- <link rel="manifest" href="/manifest.json"> --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/cartzilla/app-icons/icon-32x32.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('assets/cartzilla/app-icons/icon-180x180.png') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/ecommerce/app.js') }}" defer></script>
    
    <!-- Theme switcher (color modes) -->
    <script src="{{ asset('assets/cartzilla/js/theme-switcher.js')}}"></script>

    <!-- Preloaded local web font (Inter) -->
    <link rel="preload" href="{{ asset('assets/cartzilla/fonts/inter-variable-latin.woff2')}}" as="font" type="font/woff2" crossorigin>

    <!-- Font icons -->
    <link rel="preload" href="{{ asset('assets/cartzilla/icons/cartzilla-icons.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/icons/cartzilla-icons.min.css')}}">

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/vendor/swiper/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/vendor/simplebar/dist/simplebar.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/vendor/choices.js/public/assets/styles/choices.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/vendor/nouislider/dist/nouislider.min.css')}}">

    <!-- Bootstrap + Theme styles -->
    <link rel="preload" href="{{ asset('assets/cartzilla/css/theme.min.css')}}" as="style">
    <link rel="preload" href="{{ asset('assets/cartzilla/css/theme.rtl.min.css')}}" as="style">
    <link rel="stylesheet" href="{{ asset('assets/cartzilla/css/theme.min.css')}}" id="theme-styles">
  </head>


  <!-- Body -->
  <body >
    {{-- App Vue --}}
    <div id="app">
      
    <!-- shopping cart -->
    @include('store.shared.shopping-cart')

    <!-- Search offcanvas -->
    @include('store.shared.search-box')

    <!-- Navigation bar (Page header) -->
    @include('store.shared.header')


    <!-- Page content -->
    <main class="content-wrapper"  >

      @yield('breadcrumb')  
     
      @yield('content')
     
    </main>


    @include('store.shared.footer')

    {{-- Toast Ecommerce component --}}
    <toast-ecommerce-component 
      ref="toastEcommerceComponent">
    </toast-ecommerce-component>

    {{-- include filter --}}
    @yield('bottom-filter-buttom')

    
    {{-- Back to top button --}}
    @include( 'store.shared.back-to-top-button')

  </div>
  {{-- end App Vue --}}

    <!-- Vendor scripts -->
    <script src="{{asset('assets/cartzilla/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('assets/cartzilla/vendor/simplebar/dist/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/cartzilla/vendor/choices.js/public/assets/scripts/choices.min.js')}}"></script>
    <script src="{{asset('assets/cartzilla/vendor/nouislider/dist/nouislider.min.js')}}"></script>
    <script src="{{asset('assets/cartzilla/vendor/list.js/dist/list.min.js')}}"></script>

    <!-- Bootstrap + Theme scripts -->
    <script src="{{asset('assets/cartzilla/js/theme.min.js')}}"></script>
    <script>
      function notyAddCart() {
        new Noty({
          theme: 'sunset',
          type: 'success',
          layout: 'bottomRight',
          text: 'Producto agregado al carrito',
          timeout: 2000,
          animation: {
            open: 'noty_effects_open',
            close: 'noty_effects_close'
          }
        }).show();
      }
    </script>
  </body>
</html>

