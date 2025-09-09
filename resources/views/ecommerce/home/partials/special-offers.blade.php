@php
$offers = $specialOffers ?? collect();
$slidesCount = $offers->count();
$enableLoop = $slidesCount > 2; // Avoid loop warnings when slides < 3
@endphp

@if($offers->count() > 0)
<!-- Ofertas especiales (adaptado de "Special offers for you" de Cartzilla) -->
<section class="container pt-5 mt-2 mt-sm-3 mt-lg-4 mt-xl-5 mb-5">
  <h3 class="text-center pt-xxl-2 pb-2 pb-md-3">Ofertas especiales para ti</h3>

  <div class="position-relative px-4 px-md-3 px-lg-4">
    <div class="row position-relative z-2 justify-content-center">

      <!-- Slider maestro de productos -->
      <div class="col-md-4 col-xl-5 order-md-2 d-flex justify-content-center justify-content-md-end py-4 py-md-3 py-lg-4">
        <div class="swiper m-0" id="specialOffersMaster" data-swiper='{
          "spaceBetween": 24,
          "loop": {{ $enableLoop ? 'true' : 'false' }},
          "watchOverflow": true,
          "speed": 400,
          "pagination": {
            "el": "#bullets",
            "clickable": true
          },
          "navigation": {
            "prevEl": "#offerPrev",
            "nextEl": "#offerNext"
          }
        }' style="max-width: 416px">
          <div class="swiper-wrapper">
            @foreach($offers as $offer)
              @php
                $hasProduct = isset($offer->product);
                $origPrice = $hasProduct && isset($offer->product->price) ? (float) $offer->product->price : null;
                $discount = isset($offer->discount_percentage) ? (float) $offer->discount_percentage : null;
                $finalPrice = $origPrice !== null && $discount ? max(0, $origPrice * (1 - $discount/100)) : $origPrice;
                $cardImg = null;
                if($hasProduct && $offer->product->images && $offer->product->images->count() > 0) {
                  $cardImg = $offer->product->images->first()->full_url_img;
                } elseif(!empty($offer->image_url)) {
                  $cardImg = $offer->image_url;
                }
              @endphp
              <div class="swiper-slide h-auto">
                <div class="card animate-underline h-100 rounded-5 border-0">
                  <div class="pt-3 px-3 position-relative">
                    @if(!empty($discount))
                      <span class="badge text-bg-danger position-absolute top-0 start-0 z-2 mt-2 mt-sm-3 ms-2 ms-sm-3">-{{ number_format($discount, 0) }}%</span>
                    @endif
                    @if($cardImg)
                      <img src="{{ $cardImg }}" alt="{{ $hasProduct ? ($offer->product->name ?? 'Oferta') : ($offer->title ?? 'Oferta') }}">
                    @endif
                  </div>
                  <div class="card-body text-center py-3">
                    <div class="d-flex justify-content-center min-w-0 fs-sm fw-medium text-dark-emphasis mb-2">
                      <span class="animate-target text-truncate">{{ $hasProduct ? ($offer->product->name ?? $offer->title) : ($offer->title ?? 'Oferta especial') }}</span>
                    </div>
                    <div class="h6 mb-3">
                      @if($finalPrice !== null && $discount)
                        ${{ number_format($finalPrice, 2) }}
                        <del class="fs-sm fw-normal text-body-tertiary">${{ number_format($origPrice, 2) }}</del>
                      @elseif($finalPrice !== null)
                        ${{ number_format($finalPrice, 2) }}
                      @endif
                    </div>
                    <a class="btn btn-sm btn-dark stretched-link" href="{{ $hasProduct ? route('ecommerce.product.detail', $offer->product->slug) : route('ecommerce.catalog') }}">
                      {{ $hasProduct ? 'Ver producto' : 'Ver catálogo' }}
                    </a>
                  </div>

                  @if(!empty($offer->end_date))
                    <div class="card-footer d-flex align-items-center justify-content-center bg-transparent border-0 pb-4" data-countdown-date="{{ $offer->end_date instanceof \Carbon\Carbon ? $offer->end_date->format('m/d/Y H:i:s') : (string) $offer->end_date }}">
                      <div class="btn btn-secondary pe-none px-2">
                        <span data-days></span>
                        <span>d</span>
                      </div>
                      <div class="animate-blinking text-body-tertiary fs-lg fw-medium mx-2">:</div>
                      <div class="btn btn-secondary pe-none px-2">
                        <span data-hours></span>
                        <span>h</span>
                      </div>
                      <div class="animate-blinking text-body-tertiary fs-lg fw-medium mx-2">:</div>
                      <div class="btn btn-secondary pe-none px-2">
                        <span data-minutes></span>
                        <span>m</span>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Paginación (bullets) del slider maestro -->
      @if($slidesCount > 1)
        <div class="swiper-pagination position-static d-md-none mt-n3 mb-2" id="bullets"></div>
      @endif

      <!-- Imágenes de vista previa (slider controlado) -->
      <div class="col-sm-9 col-md-8 col-xl-7 order-md-1 align-self-end">
        <div class="swiper user-select-none" id="previewImages" data-swiper='{
          "allowTouchMove": false,
          "loop": {{ $enableLoop ? 'true' : 'false' }},
          "watchOverflow": true,
          "effect": "fade",
          "fadeEffect": { "crossFade": true }
        }'>
          <div class="swiper-wrapper">
            @foreach($offers as $offer)
              @php
                $previewImg = !empty($offer->image_url) ? $offer->image_url : (isset($offer->product) && $offer->product->images && $offer->product->images->count() > 0 ? $offer->product->images->first()->full_url_img : null);
              @endphp
              <div class="swiper-slide">
                <div class="ratio" style="--cz-aspect-ratio: calc(542 / 718 * 100%)">
                  @if($previewImg)
                    <img src="{{ $previewImg }}" alt="{{ $offer->title ?? 'Vista previa de la oferta' }}" class="w-100 h-100 object-fit-cover">
                  @else
                    <span class="d-inline-block w-100 h-100 bg-body-tertiary"></span>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- Fondos (slider controlado) -->
    @php
      $bgColorsLight = ['#dceee7', '#dddad7', '#e2daec', '#e9e0eb', '#e9e8e8'];
      $bgColorsDark  = ['#1b282c', '#272729', '#2a2735', '#2f2c3a', '#323232'];
      $bgIdx = 0;
      $useOfferColor = function($offer, $fallbackLight, $fallbackDark) {
        $c = isset($offer->background_color) && $offer->background_color ? $offer->background_color : null;
        // If custom color provided, use it for both modes; otherwise fallback palettes
        return [ $c ?: $fallbackLight, $c ?: $fallbackDark ];
      };
    @endphp
    <div class="swiper position-absolute top-0 start-0 w-100 h-100 user-select-none rounded-5" id="backgrounds" data-swiper='{
      "allowTouchMove": false,
      "loop": {{ $enableLoop ? 'true' : 'false' }},
      "watchOverflow": true,
      "effect": "fade",
      "fadeEffect": { "crossFade": true }
    }'>
      <div class="swiper-wrapper">
        @foreach($offers as $offer)
          @php
            $fallbackLight = $bgColorsLight[$bgIdx % count($bgColorsLight)];
            $fallbackDark  = $bgColorsDark[$bgIdx % count($bgColorsDark)];
            [$light, $dark] = $useOfferColor($offer, $fallbackLight, $fallbackDark);
            $bgIdx++;
          @endphp
          <div class="swiper-slide">
            <span class="position-absolute top-0 start-0 w-100 h-100 rounded-5 d-none-dark" style="background-color: {{ $light }}"></span>
            <span class="position-absolute top-0 start-0 w-100 h-100 rounded-5 d-none d-block-dark" style="background-color: {{ $dark }}"></span>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Botones Prev/Next del slider maestro -->
  @if($slidesCount > 1)
    <div class="d-none d-md-flex justify-content-center gap-2 pt-3 mt-2 mt-lg-3">
      <button type="button" class="btn btn-icon btn-outline-secondary animate-slide-start rounded-circle me-1" id="offerPrev" aria-label="Anterior">
        <i class="ci-chevron-left fs-lg animate-target"></i>
      </button>
      <button type="button" class="btn btn-icon btn-outline-secondary animate-slide-end rounded-circle" id="offerNext" aria-label="Siguiente">
        <i class="ci-chevron-right fs-lg animate-target"></i>
      </button>
    </div>
  @endif

  @if($specialOffers->count() > 3)
    <div class="text-center pt-4 mt-2 mt-md-3">
      <a href="{{ route('ecommerce.catalog') }}" class="btn btn-outline-primary">
        <i class="ci-eye fs-base me-2"></i>
        Ver todas las ofertas
      </a>
    </div>
  @endif
  @push('scripts')
  <script>
  // Link master slider with preview and background sliders after they are initialized by theme.js
  (function() {
    function linkSpecialOfferSliders() {
      var masterEl = document.getElementById('specialOffersMaster');
      var previewEl = document.getElementById('previewImages');
      var bgEl = document.getElementById('backgrounds');
      if (!masterEl || !previewEl || !bgEl) return;
      var m = masterEl.swiper;
      var p = previewEl.swiper;
      var b = bgEl.swiper;
      if (m && p && b && !m._czLinked) {
        try {
          m.controller.control = [p, b];
          m._czLinked = true;
        } catch (e) {}
      }
    }
    // Run once now and also on window load in case scripts initialize later
    document.addEventListener('DOMContentLoaded', linkSpecialOfferSliders);
    window.addEventListener('load', linkSpecialOfferSliders);
    // Also retry shortly after in case of async init timing
    setTimeout(linkSpecialOfferSliders, 50);
    setTimeout(linkSpecialOfferSliders, 200);
  })();
  </script>
  @endpush

</section>
@endif