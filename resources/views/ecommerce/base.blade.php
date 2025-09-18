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

    <!-- Currency Switcher Script -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Currency switcher functionality
        const currencyRadios = document.querySelectorAll('input[name="currency"]');
        if (!currencyRadios.length) return;
        
        // Currency data from backend
        const currencyData = {!! \App\Helpers\CurrencyHelper::getJavascriptData() !!};
        
        // Check if currency module is enabled
        if (!currencyData.enabled) {
          // Hide currency switcher elements and force USD
          currencyRadios.forEach(radio => {
            const container = radio.closest('[data-currency-switcher]');
            if (container) {
              container.style.display = 'none';
            }
          });
          
          // Force USD currency and clear any VES preference
          localStorage.setItem('preferred_currency', 'USD');
          return; // Exit early if module disabled
        }
        
        // Current selected currency
        let currentCurrency = 'USD';
        
        // Get all price elements (will be set by Vue components)
        function getPriceElements() {
          return document.querySelectorAll('[data-price], [data-price-usd]');
        }
        
        // Format price according to currency
        function formatPrice(amount, currency) {
          const decimals = currencyData.decimals[currency] || 2;
          const symbol = currencyData.symbols[currency] || '';
          
          if (currency === 'VES') {
            return symbol + ' ' + amount.toLocaleString('es-VE', {
              minimumFractionDigits: decimals,
              maximumFractionDigits: decimals
            });
          } else {
            return symbol + amount.toLocaleString('en-US', {
              minimumFractionDigits: decimals,
              maximumFractionDigits: decimals
            });
          }
        }
        
        // Convert price between currencies
        function convertPrice(amount, fromCurrency, toCurrency) {
          if (fromCurrency === toCurrency) return amount;
          
          if (fromCurrency === 'USD' && toCurrency === 'VES') {
            return amount * currencyData.exchangeRate;
          } else if (fromCurrency === 'VES' && toCurrency === 'USD') {
            return amount / currencyData.exchangeRate;
          }
          
          return amount;
        }
        
        // Update all prices on the page
        function updatePrices(newCurrency) {
          currentCurrency = newCurrency;
          
          // Store preference in localStorage
          localStorage.setItem('preferred_currency', newCurrency);
          
          // Emit custom event for Vue components to listen to
          window.dispatchEvent(new CustomEvent('currency-changed', {
            detail: { 
              currency: newCurrency,
              exchangeRate: currencyData.exchangeRate,
              formatPrice: formatPrice,
              convertPrice: convertPrice
            }
          }));
        }
        
        // Handle currency change
        currencyRadios.forEach(radio => {
          radio.addEventListener('change', function(e) {
            if (e.target.checked) {
              updatePrices(e.target.value);
            }
          });
        });
        
        // Load saved preference
        const savedCurrency = localStorage.getItem('preferred_currency');
        if (savedCurrency && ['USD', 'VES'].includes(savedCurrency)) {
          const radioButton = document.querySelector(`input[name="currency"][value="${savedCurrency}"]`);
          if (radioButton) {
            radioButton.checked = true;
            updatePrices(savedCurrency);
          }
        }
        
        // Make currency utilities globally available
        window.currencyUtils = {
          currentCurrency: () => currentCurrency,
          exchangeRate: currencyData.exchangeRate,
          formatPrice: formatPrice,
          convertPrice: convertPrice,
          updateExchangeRate: function(newRate) {
            currencyData.exchangeRate = newRate;
            if (currentCurrency === 'VES') {
              updatePrices('VES'); // Re-trigger conversion
            }
          }
        };
        
        // Update exchange rate from API periodically
        function updateExchangeRate() {
          fetch('/api/currency/exchange-rate')
            .then(response => response.json())
            .then(data => {
              window.currencyUtils.updateExchangeRate(data.rate);
            })
            .catch(error => console.log('Error updating exchange rate:', error));
        }
        
        // Update rate every 5 minutes
        setInterval(updateExchangeRate, 300000);
        
        // Enhanced price conversion for Vue components
        function convertVuePrices(newCurrency) {
          // Find all elements that look like product prices
          const priceSelectors = [
            '.h6', // The price display class in Vue components
            '[class*="price"]',
            '[data-price]'
          ];
          
          priceSelectors.forEach(selector => {
            document.querySelectorAll(selector).forEach(element => {
              const text = element.textContent.trim();
              
              // Check if this looks like a price (starts with $ or contains currency symbols)
              if (text.match(/^\$[\d,]+\.?\d*$/) || text.match(/^Bs\.?\s*[\d.,]+$/)) {
                // Extract the numeric value
                let numericValue = parseFloat(text.replace(/[^\d.]/g, ''));
                
                if (!isNaN(numericValue)) {
                  // Store original USD price if not already stored
                  if (!element.dataset.originalUsd) {
                    // If current display is in VES format, convert back to USD first
                    if (text.includes('Bs.')) {
                      element.dataset.originalUsd = (numericValue / currencyData.exchangeRate).toFixed(2);
                    } else {
                      element.dataset.originalUsd = numericValue.toFixed(2);
                    }
                  }
                  
                  const originalUsd = parseFloat(element.dataset.originalUsd);
                  
                  // Convert and format
                  let convertedPrice;
                  if (newCurrency === 'VES') {
                    convertedPrice = originalUsd * currencyData.exchangeRate;
                    element.textContent = 'Bs. ' + convertedPrice.toLocaleString('es-VE', {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2
                    });
                  } else {
                    convertedPrice = originalUsd;
                    element.textContent = '$' + convertedPrice.toLocaleString('en-US', {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2
                    });
                  }
                }
              }
            });
          });
        }
        
        // Observe for dynamically added Vue content
        function observeVueContent() {
          const observer = new MutationObserver((mutations) => {
            let shouldConvert = false;
            mutations.forEach((mutation) => {
              if (mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach((node) => {
                  if (node.nodeType === Node.ELEMENT_NODE) {
                    // Check if this is likely a product container
                    if (node.classList && (node.classList.contains('col-6') || node.classList.contains('col-md-4'))) {
                      shouldConvert = true;
                    }
                    // Also check for price elements within added nodes
                    if (node.querySelector && node.querySelector('.h6')) {
                      shouldConvert = true;
                    }
                  }
                });
              }
            });
            
            if (shouldConvert && currentCurrency === 'VES') {
              setTimeout(() => convertVuePrices(currentCurrency), 100);
            }
          });
          
          // Start observing
          observer.observe(document.body, {
            childList: true,
            subtree: true
          });
        }
        
        // Enhanced updatePrices function
        const originalUpdatePrices = updatePrices;
        updatePrices = function(newCurrency) {
          currentCurrency = newCurrency;
          
          // Store preference in localStorage
          localStorage.setItem('preferred_currency', newCurrency);
          
          // Convert Vue component prices
          convertVuePrices(newCurrency);
          
          // Emit custom event for Vue components to listen to
          window.dispatchEvent(new CustomEvent('currency-changed', {
            detail: { 
              currency: newCurrency,
              exchangeRate: currencyData.exchangeRate,
              formatPrice: formatPrice,
              convertPrice: convertPrice
            }
          }));
        };
        
        // Initialize observer for Vue content
        observeVueContent();
        
        // Apply currency conversion on page load if VES is selected
        setTimeout(() => {
          if (currentCurrency === 'VES') {
            convertVuePrices('VES');
          }
        }, 1000);
      });
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

