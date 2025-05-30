<template>
     <!-- A slider equipped with thumbnails that allow users to control and navigate the main slider -->
    <div class="col-md-6">
        
        <!-- Main slider -->
        <div ref="mainSwiper" class="swiper hover-effect-opacity" >
        <div class="swiper-wrapper">

            <!-- Item -->
            <div v-for="(image, index) in images" :key="index" class="swiper-slide">
                <div class="ratio ratio-1x1 bg-white rounded">
                    <div class="position-absolute top-0 start-0 w-100 d-flex align-items-center justify-content-center display-4">
                        <img class="img-fluid" :src="image" alt="Image">
                    </div>
                </div>
            </div>

        </div>

        <!-- Prev button -->
        <div class="position-absolute top-50 start-0 z-2 translate-middle-y ms-3 ms-sm-4 hover-effect-target opacity-0">
            <button type="button" class="btn btn-prev btn-icon btn-secondary bg-body border-0 rounded-circle animate-slide-start" aria-label="Prev">
            <i class="ci-chevron-left fs-lg animate-target"></i>
            </button>
        </div>

        <!-- Next button -->
        <div class="position-absolute top-50 end-0 z-2 translate-middle-y me-3 me-sm-4 hover-effect-target opacity-0">
            <button type="button" class="btn btn-next btn-icon btn-secondary bg-body border-0 rounded-circle animate-slide-end" aria-label="Next">
            <i class="ci-chevron-right fs-lg animate-target"></i>
            </button>
        </div>
        </div>
     
    </div>      
</template>
<script>


    export default {
        components: {
        },
        props: {
        },
        data() {
            return {
              images : [],
            };
        },
        methods: {
            setImages(images){
                this.images = images;
            },
              initializeSwipers() {
                // Si usas import (Opción A), usa Swiper directamente.
                // Si dependes de la carga global (Opción B), usa window.Swiper.
                const SwiperInstance = window.Swiper || Swiper; // Fallback por si acaso

                // Inicializa el slider principal y conecta las miniaturas
                this.mainSwiper = new SwiperInstance(this.$refs.mainSwiper, {
                    spaceBetween: 24,
                    loop: true,
                    navigation: {
                    prevEl: this.$refs.mainSwiper.querySelector('.btn-prev'),
                    nextEl: this.$refs.mainSwiper.querySelector('.btn-next')
                    },
                    thumbs: {
                    swiper: this.thumbsSwiper // Conecta el slider de miniaturas
                    }
                });
                }
        },
        mounted() {
            this.initializeSwipers(); // Inicializa el Swiper, aunque esté vacío al principio
        }
    }
</script>