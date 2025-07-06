<!-- Hero slider -->
<section class="position-relative">
  <div class="swiper hero-swiper position-absolute top-0 start-0 w-100 h-100" data-swiper='{
    "effect": "fade",
    "loop": true,
    "speed": 400,
    "pagination": {
      "el": ".swiper-pagination",
      "clickable": true
    },
    "autoplay": {
      "delay": 5500,
      "disableOnInteraction": false
    }
  }' data-bs-theme="dark">
    <div class="swiper-wrapper">
      @foreach($banners as $banner)
        <div class="swiper-slide" style="background-color: WHITE; }}">
          <div class="position-absolute d-flex align-items-center w-100 h-100 z-2">
            <div class="container mt-lg-n4">
              <div class="row">
                <div class="col-12 col-sm-10 col-md-7 col-lg-6">
                  @if($banner->subtitle)
                    <p class="fs-sm text-white mb-lg-4">{!! $banner->subtitle !!}</p>
                  @endif
                  @if($banner->title)
                    <h2 class="display-4 pb-2 pb-md-3 pb-lg-4">{!! $banner->title !!}</h2>
                  @endif
                  @if($banner->text_button && $banner->url_button)
                    <a class="btn btn-lg btn-outline-light rounded-pill" href="{{ $banner->url_button }}" target="_blank">{{ $banner->text_button }}</a>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <img src="{{ $banner->image_url }}" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover rtl-flip" alt="{{ $banner->titulo ?? 'Banner' }}">
        </div>
      @endforeach
    </div>
    <!-- Slider pagination (Bullets) -->
    <div class="swiper-pagination hero pb-sm-2"></div>
  </div>
  <div class="d-md-none" style="height: 380px"></div>
  <div class="d-none d-md-block d-lg-none" style="height: 420px"></div>
  <div class="d-none d-lg-block d-xl-none" style="height: 500px"></div>
  <div class="d-none d-xl-block d-xxl-none" style="height: 560px"></div>
  <div class="d-none d-xxl-block" style="height: 624px"></div>
</section>

