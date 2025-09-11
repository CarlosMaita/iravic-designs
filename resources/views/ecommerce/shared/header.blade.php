 <header class="navbar navbar-expand-lg navbar-sticky bg-body d-lg-block sticky-top p-0" data-sticky-navbar='{"offset": 100}'>
      <div class="container py-2 py-lg-3">

          <!-- Categories mega menu -->
          <div class=" navbar-nav d-none d-lg-flex align-items-center justify-content-between flex-wrap gap-3 me-lg-3 mb-2 mb-lg-0">
            
            <div class="dropdown position-relative pb-lg-2">
              <button type="button" class="nav-link animate-underline fw-semibold text-uppercase ps-0" data-bs-toggle="dropdown" data-bs-trigger="hover" data-bs-auto-close="outside" aria-expanded="false">
                <i class="ci-menu fs-lg me-2"></i>
                <span class="animate-target">Categorías</span>
              </button>
              <div class="dropdown-menu p-4 px-xl-5" style="--cz-dropdown-spacer: .5rem; width: max-content;">

                <div class="row g-4">
                    <div class="col-12">
                      <h6 class="mb-3">Todas las categorias</h6>
                    </div>
                    @php
                      $chunks = $categories->chunk(ceil($categories->count() / 4));
                      @endphp
                    @foreach ($chunks as $chunk)
                    <div class="col-lg-3">
                      <ul class="nav flex-column gap-2 mt-0">
                        @foreach ($chunk as $category)
                          <li class="d-flex w-100 pt-1">
                            <a class="nav-link animate-underline animate-target d-inline fw-normal p-0" href="/catalogo?category={{ $category->id }}">{{ $category->name }}</a>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                    @endforeach
                </div>

              </div>
            </div>

            <!-- Currency Switcher Component (for when Vue is working) -->
            <currency-switcher-component class="d-none"></currency-switcher-component>
          </div>

          
          <div class="d-flex d-lg-none align-items-center gap-3">
            <!-- Mobile offcanvas menu toggler (Hamburger) -->
            <button type="button" class="navbar-toggler me-4 me-md-2" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          </div>

          <!-- Navbar brand (Logo) -->
          <a class="navbar-brand d-flex align-items-center justify-content-end fs-2 py-0 m-0 me-auto me-sm-n5" href="{{route('ecommerce.home')}}">
                  <h6 class="m-0 text-uppercase" style="font-family: Roboto, sans-serif; letter-spacing: 3px;">Iravic Designs</h6>
          </a>





        <!-- Button group -->
        <div class="d-flex align-items-center">

            <!-- Currency Switcher Button (Mobile) - Pure HTML/JS fallback -->
            <div class="me-2">
                <!-- Mobile Currency Button -->
                <button 
                    type="button" 
                    class="btn btn-icon btn-lg fs-xl btn-outline-secondary border-0 rounded-circle animate-shake d-lg-none"
                    id="mobile-currency-btn"
                    data-bs-toggle="modal" 
                    data-bs-target="#mobile-currency-modal"
                    title="Cambiar moneda"
                >
                    <i class="ci-dollar-sign animate-target"></i>
                    <span class="visually-hidden">Cambiar moneda</span>
                </button>

                <!-- Desktop Currency Switcher -->
                <div class="d-none d-lg-flex align-items-center" data-currency-switcher>
                    <span class="text-muted me-2 small">Moneda:</span>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Currency switcher">
                        <input type="radio" class="btn-check" name="currency" id="currency-usd" value="USD" checked>
                        <label class="btn btn-outline-secondary" for="currency-usd">USD</label>
                        
                        <input type="radio" class="btn-check" name="currency" id="currency-ves" value="VES">
                        <label class="btn btn-outline-secondary" for="currency-ves">VES</label>
                    </div>
                </div>
            </div>

            <!-- Search toggle button visible on screens < 992px wide (lg breakpoint) -->
          <button  type="button" class="btn btn-icon btn-lg fs-xl btn-outline-secondary border-0 rounded-circle animate-shake" data-bs-toggle="offcanvas" data-bs-target="#searchBox" aria-controls="searchBox" aria-label="Toggle search bar" >
            <i class="ci-search animate-target"></i>
          </button>

          <!-- Navbar stuck nav toggler -->
          <button type="button" class="navbar-toggler d-none navbar-stuck-show me-3" data-bs-toggle="collapse" data-bs-target="#stuckNav" aria-controls="stuckNav" aria-expanded="false" aria-label="Toggle navigation in navbar stuck state">
            <span class="navbar-toggler-icon"></span>
          </button>

          
          <!-- Account button visible on screens > 768px wide (md breakpoint) -->
          @auth('customer')
            <!-- User dropdown when authenticated -->
            <div class="dropdown">
              <button type="button" class="btn btn-icon btn-sm fs-lg btn-outline-secondary border-0 rounded-circle animate-shake" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ci-user animate-target"></i>
                <span class="visually-hidden">Cuenta</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" style="--cz-dropdown-min-width: 200px">
                <li>
                  <h6 class="dropdown-header">{{ Auth::guard('customer')->user()->name }}</h6>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="{{ route('customer.dashboard') }}">
                    <i class="ci-grid me-2"></i>Dashboard
                  </a>
                </li>
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
                  <a class="dropdown-item" href="{{ route('customer.payments.index') }}">
                    <i class="ci-credit-card me-2"></i>Mis Pagos
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
          @else
            <!-- Login link when not authenticated -->
            <a class="btn btn-icon btn-sm fs-lg btn-outline-secondary border-0 rounded-circle animate-shake" href="{{route('customer.login.form')}}">
              <i class="ci-user animate-target"></i>
              <span class="visually-hidden">Cuenta</span>
            </a>
          @endauth

          <!-- Theme switcher (light/dark/auto) -->
          <div class="dropdown">
            <button type="button" class="theme-switcher btn btn-icon d-none  d-md-flex btn-lg btn-outline-secondary fs-lg border-0 rounded-circle animate-scale" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Toggle theme (light)">
              <span class="theme-icon-active d-flex animate-target">
                <i class="ci-sun"></i>
              </span>
            </button>
            <ul class="dropdown-menu" style="--cz-dropdown-min-width: 9rem">
              <li>
                <button type="button" class="dropdown-item active" data-bs-theme-value="light" aria-pressed="true">
                  <span class="theme-icon d-flex fs-base me-2">
                    <i class="ci-sun"></i>
                  </span>
                  <span class="theme-label">Light</span>
                  <i class="item-active-indicator ci-check ms-auto"></i>
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item" data-bs-theme-value="dark" aria-pressed="false">
                  <span class="theme-icon d-flex fs-base me-2">
                    <i class="ci-moon"></i>
                  </span>
                  <span class="theme-label">Dark</span>
                  <i class="item-active-indicator ci-check ms-auto"></i>
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item" data-bs-theme-value="auto" aria-pressed="false">
                  <span class="theme-icon d-flex fs-base me-2">
                    <i class="ci-auto"></i>
                  </span>
                  <span class="theme-label">Auto</span>
                  <i class="item-active-indicator ci-check ms-auto"></i>
                </button>
              </li>
            </ul>
          </div>
          
          <!-- Wishlist button visible on screens > 768px wide (md breakpoint) -->
          {{-- <a class="btn btn-icon btn-lg fs-lg btn-outline-secondary border-0 rounded-circle animate-pulse d-none d-md-inline-flex" href="#!">
            <i class="ci-heart animate-target"></i>
            <span class="visually-hidden">Deseos</span>
          </a> --}}
          
          <!-- Cart button -->
          <icon-header-cart-ecommerce-component ref="iconHeaderCartEcommerceComponent"></icon-header-cart-ecommerce-component>

        </div>
      </div>

      <!-- Main navigation that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
      <div class="collapse navbar-stuck-hide" id="stuckNav">
        <nav class="offcanvas offcanvas-start" id="navbarNav" tabindex="-1" aria-labelledby="navbarNavLabel">
          <div class="offcanvas-header py-3">
            <h5 class="offcanvas-title" id="navbarNavLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>

          <div class="offcanvas-body pt-1 pb-3 py-lg-0">
            <div class="container pb-lg-2 px-0 px-lg-3">

              <div class="position-relative d-lg-flex align-items-center justify-content-between">

                <!-- Categories mega menu -->
                <div class="navbar-nav d-none d-lg-flex align-items-center justify-content-between flex-wrap gap-3 me-lg-3 mb-2 mb-lg-0">
                  
                  <div class="dropdown position-static pb-lg-2">
                    <div class="dropdown-menu w-100 p-4 px-xl-5" style="--cz-dropdown-spacer: .75rem">

                      <div class="row g-4">
                          <div class="col-12">
                            <h6 class="mb-3">Todas las categorias</h6>
                          </div>
                        @php
                            $chunks = $categories->chunk(ceil($categories->count() / 4));
                            @endphp
                          @foreach ($chunks as $chunk)
                          <div class="col-lg-3">
                            <ul class="nav flex-column gap-2 mt-0">
                              @foreach ($chunk as $category)
                                <li class="d-flex w-100 pt-1">
                                  <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="/catalogo?category={{ $category->id }}">{{ $category->name }}</a>
                                </li>
                              @endforeach
                            </ul>
                          </div>
                          @endforeach
                      </div>

                    </div>
                  </div>
                </div>

                <!-- Search form visible on screens < 992px wide (lg breakpoint) -->
                <div class="row d-lg-none">
                    <div class="col-12">
                      <h6 class="mb-3">Todas las categorias</h6>
                    </div>
                    <div class="col-lg-3">
                      <ul class="nav flex-column gap-2 mt-0">
                        @foreach ($categories as $category)
                        <li class="d-flex w-100 pt-1">
                            <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="/catalogo?category={{ $category->id }}">{{ $category->name }}</a>
                          </li>
                          @endforeach
                      </ul>
                    </div>
                </div>
                      

                       
                <!-- Search toggle visible on screens > 991px wide (lg breakpoint) -->
                {{-- <button type="button" class="btn btn-outline-secondary justify-content-start w-100 px-3 mb-lg-2 ms-3 d-none d-lg-inline-flex" style="max-width: 240px" data-bs-toggle="offcanvas" data-bs-target="#searchBox" aria-controls="searchBox">
                  <i class="ci-search fs-base ms-n1 me-2"></i>
                  <span class="text-body-tertiary fw-normal">Buscar</span>
                </button> --}}
                
              </div>
            </div>
          </div>

          <!-- Account and Wishlist buttons visible on screens < 768px wide (md breakpoint) -->
          <div class="offcanvas-header border-top px-0 py-3 mt-3 d-md-none">
            <div class="nav nav-justified w-100">
              @auth('customer')
                <a class="nav-link border-end" href="{{route('customer.dashboard')}}">
                  <i class="ci-user fs-lg opacity-60 me-2"></i>
                  Mi Cuenta
                </a>
                <a class="nav-link" href="{{route('customer.favorites.index')}}">
                  <i class="ci-heart fs-lg opacity-60 me-2"></i>
                  Favoritos
                </a>
              @else
                <a class="nav-link border-end" href="{{route('customer.login.form')}}">
                  <i class="ci-user fs-lg opacity-60 me-2"></i>
                  Iniciar Sesión
                </a>
                <a class="nav-link" href="{{route('customer.register.form')}}">
                  <i class="ci-user-plus fs-lg opacity-60 me-2"></i>
                  Registrarse
                </a>
              @endauth
            </div>
          </div>
        </nav>
      </div>
    </header>

    <!-- Mobile Currency Modal -->
    <div class="modal fade" id="mobile-currency-modal" tabindex="-1" aria-labelledby="currencyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="currencyModalLabel">
                        <i class="ci-dollar-sign me-2"></i>
                        Seleccionar Moneda
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Elige la moneda para mostrar los precios:
                    </p>
                    <div class="d-grid gap-2">
                        <button 
                            type="button" 
                            class="btn btn-lg btn-outline-primary currency-option" 
                            data-currency="USD"
                            id="mobile-usd-btn"
                        >
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-start">
                                    <strong>USD - Dólar Americano</strong>
                                    <div class="small text-muted">Moneda base del catálogo</div>
                                </div>
                                <div class="fs-4">$</div>
                            </div>
                        </button>
                        
                        <button 
                            type="button" 
                            class="btn btn-lg btn-outline-primary currency-option" 
                            data-currency="VES"
                            id="mobile-ves-btn"
                        >
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-start">
                                    <strong>VES - Bolívar Venezolano</strong>
                                    <div class="small text-muted" id="exchange-rate-display">
                                        Tasa: <span id="rate-value">{{ \App\Helpers\CurrencyHelper::getCurrentExchangeRate() }}</span> Bs/$
                                    </div>
                                </div>
                                <div class="fs-4">Bs.</div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Currency switcher functionality
    document.addEventListener('DOMContentLoaded', function() {
        const currentCurrency = localStorage.getItem('selectedCurrency') || 'USD';
        const exchangeRate = window.currencyData ? window.currencyData.exchangeRate : {{ \App\Helpers\CurrencyHelper::getCurrentExchangeRate() }};
        
        // Update UI based on stored currency
        updateCurrencyUI(currentCurrency);
        
        // Desktop currency switcher
        const usdRadio = document.getElementById('currency-usd');
        const vesRadio = document.getElementById('currency-ves');
        
        if (usdRadio && vesRadio) {
            if (currentCurrency === 'VES') {
                vesRadio.checked = true;
                usdRadio.checked = false;
            } else {
                usdRadio.checked = true;
                vesRadio.checked = false;
            }
            
            usdRadio.addEventListener('change', function() {
                if (this.checked) changeCurrency('USD');
            });
            
            vesRadio.addEventListener('change', function() {
                if (this.checked) changeCurrency('VES');
            });
        }
        
        // Mobile currency buttons
        const currencyOptions = document.querySelectorAll('.currency-option');
        currencyOptions.forEach(function(button) {
            button.addEventListener('click', function() {
                const currency = this.getAttribute('data-currency');
                changeCurrency(currency);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('mobile-currency-modal'));
                if (modal) modal.hide();
            });
        });
        
        function changeCurrency(currency) {
            localStorage.setItem('selectedCurrency', currency);
            updateCurrencyUI(currency);
            updatePrices(currency);
            
            // Update desktop radio buttons
            if (currency === 'USD') {
                if (usdRadio) usdRadio.checked = true;
                if (vesRadio) vesRadio.checked = false;
            } else {
                if (vesRadio) vesRadio.checked = true;
                if (usdRadio) usdRadio.checked = false;
            }
        }
        
        function updateCurrencyUI(currency) {
            // Update mobile button styles
            const usdBtn = document.getElementById('mobile-usd-btn');
            const vesBtn = document.getElementById('mobile-ves-btn');
            
            if (usdBtn && vesBtn) {
                if (currency === 'USD') {
                    usdBtn.className = 'btn btn-lg btn-primary currency-option';
                    vesBtn.className = 'btn btn-lg btn-outline-primary currency-option';
                } else {
                    usdBtn.className = 'btn btn-lg btn-outline-primary currency-option';
                    vesBtn.className = 'btn btn-lg btn-primary currency-option';
                }
            }
            
            // Update mobile currency button title
            const mobileBtn = document.getElementById('mobile-currency-btn');
            if (mobileBtn) {
                mobileBtn.title = 'Moneda: ' + currency;
            }
        }
        
        function updatePrices(currency) {
            const priceElements = document.querySelectorAll('[data-usd-price]');
            
            priceElements.forEach(function(element) {
                const usdPrice = parseFloat(element.getAttribute('data-usd-price'));
                if (!isNaN(usdPrice)) {
                    if (currency === 'VES' && exchangeRate) {
                        const vesPrice = usdPrice * exchangeRate;
                        element.textContent = 'Bs. ' + formatNumber(vesPrice);
                    } else {
                        element.textContent = '$' + formatNumber(usdPrice);
                    }
                }
            });
        }
        
        function formatNumber(number) {
            return new Intl.NumberFormat('es-VE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
        }
        
        // Initial price update
        updatePrices(currentCurrency);
    });
    </script>