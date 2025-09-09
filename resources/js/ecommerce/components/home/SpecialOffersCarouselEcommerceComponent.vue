<template>
  <section v-if="offers && offers.length > 0" class="container pt-5 mt-2 mt-sm-3 mt-lg-4 mt-xl-5 mb-5">
    <h3 class="text-center pt-xxl-2 pb-2 pb-md-3">Ofertas especiales para ti</h3>

    <div class="position-relative px-4 px-md-3 px-lg-4">
      <div class="row position-relative z-2 justify-content-center">
        <!-- Maestro -->
        <div class="col-md-4 col-xl-5 order-md-2 d-flex justify-content-center justify-content-md-end py-4 py-md-3 py-lg-4">
          <div class="swiper m-0" :id="ids.master" :data-swiper="masterConfigJSON" style="max-width: 416px">
            <div class="swiper-wrapper">
              <div class="swiper-slide h-auto" v-for="(offer, idx) in offers" :key="'o-'+idx">
                <div class="card animate-underline h-100 rounded-5 border-0">
                  <div class="pt-3 px-3 position-relative">
                    <span v-if="percent(offer)" class="badge text-bg-danger position-absolute top-0 start-0 z-2 mt-2 mt-sm-3 ms-2 ms-sm-3">-{{ percent(offer) }}%</span>
                    <img v-if="cardImg(offer)" :src="cardImg(offer)" :alt="title(offer)" />
                  </div>
                  <div class="card-body text-center py-3">
                    <div class="d-flex justify-content-center min-w-0 fs-sm fw-medium text-dark-emphasis mb-2">
                      <span class="animate-target text-truncate">{{ title(offer) }}</span>
                    </div>
                    <div class="h6 mb-3">
                      <template v-if="finalPrice(offer) !== null && percent(offer)">
                        ${{ finalPrice(offer).toFixed(2) }}
                        <del class="fs-sm fw-normal text-body-tertiary">${{ origPrice(offer)?.toFixed(2) }}</del>
                      </template>
                      <template v-else-if="finalPrice(offer) !== null">
                        ${{ finalPrice(offer).toFixed(2) }}
                      </template>
                    </div>
                    <a class="btn btn-sm btn-dark stretched-link" :href="ctaUrl(offer)">
                      {{ hasProduct(offer) ? 'Ver producto' : 'Ver catálogo' }}
                    </a>
                  </div>

                  <div v-if="endDate(offer)" class="card-footer d-flex align-items-center justify-content-center bg-transparent border-0 pb-4">
                    <div class="btn btn-secondary pe-none px-2">
                      <span>{{ countdown[idx]?.days || 0 }}</span>
                      <span>d</span>
                    </div>
                    <div class="animate-blinking text-body-tertiary fs-lg fw-medium mx-2">:</div>
                    <div class="btn btn-secondary pe-none px-2">
                      <span>{{ countdown[idx]?.hours || 0 }}</span>
                      <span>h</span>
                    </div>
                    <div class="animate-blinking text-body-tertiary fs-lg fw-medium mx-2">:</div>
                    <div class="btn btn-secondary pe-none px-2">
                      <span>{{ countdown[idx]?.minutes || 0 }}</span>
                      <span>m</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Bullets (mobile) -->
        <div v-if="slidesCount > 1" class="swiper-pagination position-static d-md-none mt-n3 mb-2" :id="ids.bullets"></div>

        <!-- Preview -->
        <div class="col-sm-9 col-md-8 col-xl-7 order-md-1 align-self-end">
          <div class="swiper user-select-none" :id="ids.preview" :data-swiper="previewConfigJSON">
            <div class="swiper-wrapper">
              <div class="swiper-slide" v-for="(offer, idx) in offers" :key="'p-'+idx">
                <div class="ratio" style="--cz-aspect-ratio: calc(542 / 718 * 100%)">
                  <img v-if="previewImg(offer)" :src="previewImg(offer)" :alt="title(offer)" class="w-100 h-100 object-fit-cover" />
                  <span v-else class="d-inline-block w-100 h-100 bg-body-tertiary"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Backgrounds -->
      <div class="swiper position-absolute top-0 start-0 w-100 h-100 user-select-none rounded-5" :id="ids.backgrounds" :data-swiper="backgroundConfigJSON">
        <div class="swiper-wrapper">
          <div class="swiper-slide" v-for="(offer, idx) in offers" :key="'b-'+idx">
            <span class="position-absolute top-0 start-0 w-100 h-100 rounded-5 d-none-dark" :style="{ backgroundColor: lightColor(idx) }"></span>
            <span class="position-absolute top-0 start-0 w-100 h-100 rounded-5 d-none d-block-dark" :style="{ backgroundColor: darkColor(idx) }"></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Prev/Next -->
    <div v-if="slidesCount > 1" class="d-none d-md-flex justify-content-center gap-2 pt-3 mt-2 mt-lg-3">
      <button type="button" class="btn btn-icon btn-outline-secondary animate-slide-start rounded-circle me-1" :id="ids.prev" aria-label="Anterior">
        <i class="ci-chevron-left fs-lg animate-target"></i>
      </button>
      <button type="button" class="btn btn-icon btn-outline-secondary animate-slide-end rounded-circle" :id="ids.next" aria-label="Siguiente">
        <i class="ci-chevron-right fs-lg animate-target"></i>
      </button>
    </div>
  </section>
</template>

<script>
export default {
  name: 'SpecialOffersCarouselEcommerceComponent',
  props: {
    offers: { type: Array, default: () => [] },
    catalogRoute: { type: String, required: true }
  },
  data(){
    return {
      master: null,
      preview: null,
      backgrounds: null,
      countdown: [],
      bgLight: ['#dceee7', '#dddad7', '#e2daec', '#e9e0eb', '#e9e8e8'],
      bgDark: ['#1b282c', '#272729', '#2a2735', '#2f2c3a', '#323232'],
      tickHandle: null
    };
  },
  computed: {
    slidesCount(){ return (this.offers || []).length; },
    enableLoop(){ return this.slidesCount > 2; },
    ids(){
      const p = 'so-' + this._uid;
      return {
        master: p + '-master',
        preview: p + '-preview',
        backgrounds: p + '-backgrounds',
        bullets: p + '-bullets',
        prev: p + '-prev',
        next: p + '-next'
      };
    },
    masterConfig(){
      return {
        spaceBetween: 24,
        loop: this.enableLoop,
        watchOverflow: true,
        speed: 400,
        pagination: this.slidesCount > 1 ? { el: '#' + this.ids.bullets, clickable: true } : undefined,
        navigation: this.slidesCount > 1 ? { prevEl: '#' + this.ids.prev, nextEl: '#' + this.ids.next } : undefined
      };
    },
    previewConfig(){
      return { allowTouchMove: false, loop: this.enableLoop, watchOverflow: true, effect: 'fade', fadeEffect: { crossFade: true } };
    },
    backgroundConfig(){
      return { allowTouchMove: false, loop: this.enableLoop, watchOverflow: true, effect: 'fade', fadeEffect: { crossFade: true } };
    },
    masterConfigJSON(){ return JSON.stringify(this.masterConfig); },
    previewConfigJSON(){ return JSON.stringify(this.previewConfig); },
    backgroundConfigJSON(){ return JSON.stringify(this.backgroundConfig); }
  },
  methods: {
    hasProduct(offer){ return offer && offer.product; },
    title(offer){ return this.hasProduct(offer) ? (offer.product.name || offer.title || 'Oferta especial') : (offer.title || 'Oferta especial'); },
    origPrice(offer){ return this.hasProduct(offer) && offer.product.price ? Number(offer.product.price) : null; },
    percent(offer){ return offer && offer.discount_percentage ? Number(offer.discount_percentage) : 0; },
    finalPrice(offer){ const o = this.origPrice(offer); const d = this.percent(offer); return o !== null ? Math.max(0, o * (1 - d/100)) : null; },
    cardImg(offer){
      if (this.hasProduct(offer) && offer.product.images && offer.product.images.length > 0) return offer.product.images[0].full_url_img;
      if (offer && offer.image_url) return offer.image_url;
      return null;
    },
    previewImg(offer){
      if (offer && offer.image_url) return offer.image_url;
      if (this.hasProduct(offer) && offer.product.images && offer.product.images.length > 0) return offer.product.images[0].full_url_img;
      return null;
    },
    ctaUrl(offer){ return this.hasProduct(offer) ? (offer.product && offer.product.slug ? `/producto/${offer.product.slug}` : this.catalogRoute) : this.catalogRoute; },
    endDate(offer){
      if (!offer || !offer.end_date) return null;
      try { return new Date(offer.end_date); } catch(e) { return null; }
    },
    lightColor(idx){
      const offer = this.offers[idx];
      const custom = offer && offer.background_color ? offer.background_color : null;
      return custom || this.bgLight[idx % this.bgLight.length];
    },
    darkColor(idx){
      const offer = this.offers[idx];
      const custom = offer && offer.background_color ? offer.background_color : null;
      return custom || this.bgDark[idx % this.bgDark.length];
    },
    initSwipers(){
      if (typeof window.Swiper === 'undefined') return;
      const masterEl = this.$el.querySelector('#' + this.ids.master);
      const previewEl = this.$el.querySelector('#' + this.ids.preview);
      const bgEl = this.$el.querySelector('#' + this.ids.backgrounds);
      const Sw = window.Swiper;
      this.master = new Sw(masterEl, this.masterConfig);
      this.preview = new Sw(previewEl, this.previewConfig);
      this.backgrounds = new Sw(bgEl, this.backgroundConfig);
      try { this.master.controller && (this.master.controller.control = [this.preview, this.backgrounds]); } catch(e) {}
    },
    destroySwipers(){
      [this.master, this.preview, this.backgrounds].forEach(s => { if (s && s.destroy) try { s.destroy(true, true); } catch(e){} });
      this.master = this.preview = this.backgrounds = null;
    },
    startTick(){
      if (this.tickHandle) return;
      const compute = () => {
        this.countdown = (this.offers || []).map(o => {
          const end = this.endDate(o);
          if (!end) return { days: 0, hours: 0, minutes: 0 };
          const now = new Date();
          let diff = Math.max(0, end - now);
          const days = Math.floor(diff / (1000*60*60*24));
          diff -= days * (1000*60*60*24);
          const hours = Math.floor(diff / (1000*60*60));
          diff -= hours * (1000*60*60);
          const minutes = Math.floor(diff / (1000*60));
          return { days, hours, minutes };
        });
      };
      compute();
      this.tickHandle = setInterval(compute, 60 * 1000); // update per minute
    },
    stopTick(){ if (this.tickHandle) { clearInterval(this.tickHandle); this.tickHandle = null; } }
  },
  mounted(){
    this.$nextTick(() => {
      if (this.slidesCount > 0) {
        this.initSwipers();
        this.startTick();
      }
    });
  },
  beforeDestroy(){ this.stopTick(); this.destroySwipers(); },
  watch: {
    offers: {
      handler(){
        this.destroySwipers();
        this.stopTick();
        this.$nextTick(() => { if (this.slidesCount > 0) { this.initSwipers(); this.startTick(); } });
      },
      deep: true
    }
  }
}
</script>

<style scoped>
.object-fit-cover{ object-fit: cover; }
</style>
