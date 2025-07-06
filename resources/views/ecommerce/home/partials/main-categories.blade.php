<!-- Featured categories that turns into carousel on screen < 992px (lg breakpoint) -->
<section class="container pt-4 pb-5 mb-2 mb-sm-3 mb-lg-4 mb-xl-5">
  <div class="swiper" data-swiper='{
    "slidesPerView": 1,
    "spaceBetween": 24,
    "pagination": {
      "el": ".swiper-pagination",
      "clickable": true
    },
    "breakpoints": {
      "680": {
        "slidesPerView": 2
      },
      "992": {
        "slidesPerView": 3
      }
    }
  }'>
    <div class="swiper-wrapper">
      @foreach($categories_cards as $category)
      <div class="swiper-slide h-auto">
        <div class="position-relative d-flex justify-content-between align-items-center h-100 bg-primary-subtle rounded-5 overflow-hidden ps-2 ps-xl-3">
          <div class="d-flex flex-column pt-4 px-3 pb-3">
            <p class="fs-xs pb-2 mb-1">{{ $category->products_count ?? '—' }} productos</p>
            <h2 class="h5 mb-2 mb-xxl-3">{{ $category->name }}</h2>
            <div class="nav">
              <a class="nav-link animate-underline stretched-link text-body-emphasis text-nowrap px-0" href="{{ route('ecommerce.catalog', ['categoria' => $category->slug ?? Str::slug($category->name)]) }}">
                <span class="animate-target">Ver productos</span>
                <i class="ci-chevron-right fs-base ms-1"></i>
              </a>
            </div>
          </div>
          <div class="ratio w-100 align-self-end rtl-flip" style="max-width: 216px; --cz-aspect-ratio: calc(240 / 216 * 100%)">
            <img src="{{ asset('/storage/'.$category->image_banner) }}" alt="{{ $category->name }}">
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <!-- Slider pagination (Bullets) -->
    <div class="swiper-pagination position-static mt-3 mt-sm-4"></div>
  </div>
</section>
