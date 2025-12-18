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

  <!-- CSRF Token for AJAX / SPA requests -->
  <meta name="csrf-token" content="{{ csrf_token() }}">


    @yield('meta-tags')

    {{-- Canonical URL for all public pages (strip query params and force canonical host) --}}
    @php
      $canonicalHost = 'iravicdesigns.store';
      $scheme = 'https';
      $canonicalUrl = $scheme . '://' . $canonicalHost . request()->getPathInfo();
    @endphp
    <link rel="canonical" href="{{ $canonicalUrl }}">

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
    @else
    {{--  Si no es produccion que el buscador no la siga --}}
    <meta name="robots" content="nofollow">

    @endif

    {{-- <link rel="manifest" href="/manifest.json"> --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/cartzilla/app-icons/icon-32x32.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('assets/cartzilla/app-icons/icon-180x180.png') }}">

    <!-- Scripts -->
    <script>
      // Expose csrfToken early for scripts that need it before app.js mounts
      window.Laravel = Object.assign({}, window.Laravel, { csrfToken: '{{ csrf_token() }}' });

      // Expose currency data globally for components
      window.currencyData = {!! \App\Helpers\CurrencyHelper::getJavascriptData() !!};
    </script>
    <script src="{{ asset('js/ecommerce/app.js') }}" defer></script>
    <script>
      // Prefill shipping modal fields from saved customer shipping info when the modal opens
      document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('show.bs.modal', function (evt) {
          var target = evt && evt.target;
          if (!target || target.id !== 'shipping-modal') return;
          try {
            fetch('/api/customer/shipping', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
              .then(function(resp){ if (!resp || !resp.ok) return null; return resp.json(); })
              .then(function(data){
                if (!data || !data.success || !data.shipping) return;
                var s = data.shipping;
                var setVal = function(id, val){
                  var el = document.getElementById(id);
                  if (!el) return;
                  el.value = val || '';
                  try {
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                  } catch (e) {}
                };
                setVal('shipping-name', s.name);
                setVal('shipping-dni', s.dni);
                setVal('shipping-phone', s.phone);
                setVal('shipping-agency', s.agency || 'MRW');
                setVal('shipping-address', s.address);
              })
              .catch(function(){});
          } catch (e) {}
        });
      });
    </script>

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
    
    <!-- Custom styles for e-commerce header -->
    <style>
      /* Reduce e-commerce header height to 64px */
      .navbar-ecommerce-header {
        min-height: 64px;
      }
      .navbar-ecommerce-header .container {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
      }
      @media (min-width: 992px) {
        .navbar-ecommerce-header .container {
          padding-top: 0.75rem !important;
          padding-bottom: 0.75rem !important;
        }
      }
    </style>
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
          <div class="swiper promo fs-sm text-white" data-swiper='{
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

    <!-- Currency utility shim: lock experience to USD pricing -->
    <script>
      (function() {
        const DEFAULT_CURRENCY = 'USD';
        const exchangeRate = window.currencyData ? window.currencyData.exchangeRate : 1;

        try {
          localStorage.setItem('preferred_currency', DEFAULT_CURRENCY);
          localStorage.setItem('selectedCurrency', DEFAULT_CURRENCY);
        } catch (error) {}

        const formatUsd = (amount) => {
          const value = Number(amount || 0);
          return '$' + value.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          });
        };

        const passthrough = (amount) => Number(amount || 0);

        window.currencyUtils = {
          currentCurrency: () => DEFAULT_CURRENCY,
          exchangeRate,
          formatPrice: formatUsd,
          convertPrice: passthrough,
          updateExchangeRate: function() {}
        };

        window.addEventListener('DOMContentLoaded', function() {
          document.querySelectorAll('option[value="VES"]').forEach(function(option) {
            const select = option.parentElement && option.parentElement.tagName === 'SELECT'
              ? option.parentElement
              : option.closest('select');
            option.remove();
            if (select && select.value !== DEFAULT_CURRENCY) {
              select.value = DEFAULT_CURRENCY;
              try {
                select.dispatchEvent(new Event('change', { bubbles: true }));
              } catch (error) {}
            }
          });

          window.dispatchEvent(new CustomEvent('currency-changed', {
            detail: {
              currency: DEFAULT_CURRENCY,
              exchangeRate,
              formatPrice: formatUsd,
              convertPrice: passthrough
            }
          }));
        });
      })();
    </script>

    @stack('scripts')
    @stack('js')

    <script>
      // Bridge to show toasts from ad-hoc Vue widgets
      document.addEventListener('DOMContentLoaded', function(){
        window.addEventListener('app:toast', function(ev){
          try {
            var payload = ev && ev.detail ? ev.detail : null;
            var root = document.getElementById('app');
            if (payload && root && root.__vue__ && root.__vue__.$refs && root.__vue__.$refs.toastEcommerceComponent){
              root.__vue__.$refs.toastEcommerceComponent.showToast(payload);
            }
          } catch (e) {
            // no-op
          }
        });
      });
    </script>

  </body>
</html>

