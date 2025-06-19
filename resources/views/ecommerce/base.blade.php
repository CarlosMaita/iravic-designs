<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-pwa="true">
  <head>
    <meta charset="utf-8">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">

    <!-- SEO Meta Tags -->
    <title> @yield('title') | Iravic </title>
    <meta name="description" content="@yield('meta-description', 'Tienda de ropa para niños')">
    <meta name="keywords" content="@yield('meta-keywords', 'tienda, ecommerce, ropa para niños, moda infantil')">
    <meta name="author" content="Iravic">
    <meta name="robots" content="nofollow">

    @yield('meta-tags')

    <!-- Webmanifest + Favicon / App icons -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    @if (app()->environment('production'))
    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){
            w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
            var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
            j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TM3WBXHH');
    </script>
    <!-- End Google Tag Manager -->
    @endif

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
    <!-- Topbar -->
    <div class="alert alert-dismissible bg-dark text-white rounded-0 py-2 px-0 m-0 fade show" data-bs-theme="dark">
      <div class="container position-relative d-flex min-w-0">
        <div class="d-flex flex-nowrap align-items-center g-2 w-100 min-w-0 mx-auto mt-n1" style="max-width: 458px">
          <div class="nav me-2">
            <button type="button" class="nav-link fs-lg p-0" id="topbarPrev" aria-label="Prev">
              <i class="ci-chevron-left"></i>
            </button>
          </div>
          <div class="swiper fs-sm text-white" data-swiper='{
            "spaceBetween": 24,
            "loop": true,
            "autoplay": {
              "delay": 5000,
              "disableOnInteraction": false
            },
            "navigation": {
              "prevEl": "#topbarPrev",
              "nextEl": "#topbarNext"
            }
          }'>
            <div class="swiper-wrapper min-w-0">
              <div class="swiper-slide text-truncate text-center">🏭 Somos Fabricantes</div>
              <div class="swiper-slide text-truncate text-center">🚚 Luego de compras de 15$ el envío es gratis</div>
              <div class="swiper-slide text-truncate text-center">🧵 Confeccionamos sus productos en 2 días</div>
            </div>
          </div>
          <div class="nav ms-2">
            <button type="button" class="nav-link fs-lg p-0" id="topbarNext" aria-label="Next">
              <i class="ci-chevron-right"></i>
            </button>
          </div>
        </div>
        <button type="button" class="btn-close position-static flex-shrink-0 p-1 ms-3 ms-md-n4" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
    <!-- End Topbar -->


    {{-- App Vue --}}
    <div id="app">
      
    <!-- shopping cart -->
    @include('ecommerce.shared.shopping-cart')

    <!-- Search offcanvas -->
    @include('ecommerce.shared.search-box')

    <!-- Navigation bar (Page header) -->
    @include('ecommerce.shared.header')

    <!-- Page content -->
    <main class="content-wrapper"  >

      @yield('breadcrumb')  
     
      @yield('content')
     
    </main>


    @include('ecommerce.shared.footer')

    {{-- Toast Ecommerce component --}}
    <toast-ecommerce-component 
      ref="toastEcommerceComponent">
    </toast-ecommerce-component>

    {{-- include filter --}}
    @yield('bottom-filter-buttom')

    
    {{-- Back to top button --}}
    @include( 'ecommerce.shared.back-to-top-button')

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

