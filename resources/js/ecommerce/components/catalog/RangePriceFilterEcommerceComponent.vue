<template>
 <!-- Price -->
<div class="accordion-item border-0 pb-1 pb-xl-2">
  <h4 class="accordion-header" id="headingPrice">
    <button
      type="button"
      class="accordion-button p-0 pb-3"
      data-bs-toggle="collapse"
      data-bs-target="#price"
      aria-expanded="true"
      aria-controls="price"
    >
      Precio
    </button>
  </h4>
  <div
    class="accordion-collapse collapse show"
    id="price"
    aria-labelledby="headingPrice"
  >
    <div class="accordion-body p-0 pb-4 mb-1 mb-xl-2">
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Precio Min.</span>
          <span class="text-muted">Precio Max.</span>
        </div>
        <div class="d-flex align-items-center">
          <div class="position-relative w-40 ms-1">
            <i
              class="ci-dollar-sign position-absolute top-50 start-0 translate-middle-y ms-3"
            ></i>
            <input
              type="number"
              class="form-control form-icon-start"
              min="0"
              data-range-slider-min
              aria-label="Min. precio"
              v-model="minPriceValue"
              @input="validatePriceFilter()"
            />
          </div>
          <i class="ci-minus"></i>
          <div class="position-relative w-40 ms-1">
            <i
              class="ci-dollar-sign position-absolute top-50 start-0 translate-middle-y ms-3"
            ></i>
            <input
              type="number"
              class="form-control form-icon-start"
              min="0"
              data-range-slider-max
              aria-label="Max. precio"
              v-model="maxPriceValue"
              @input="validatePriceFilter()"
            />
          </div>
          <!-- boton de > -->
          <button 
            :disabled="!isFilterValidated"
            type="button"
            class="btn btn-dark ms-2"
            
            data-range-slider-apply
            @click="applyPriceFilter()"
          >
            <i class="ci-arrow-right"></i>
          </button>
        </div>
        <!-- alert -->
        <div v-if="isError" class="text-danger mt-2">
          <strong class="">Importante:</strong> {{ errorMessage }}
        </div>
      </div>
    </div>
  </div>
</template>
<script>

  
  export default {
    name: 'RangePriceFilterEcommerceComponent',
    props: {
   
    },
    data() {
      return {
        isFilterValidated: false,
        isError: false,
        errorMessage: '',
        minPriceValue: null,
        maxPriceValue: null,
      };
    },
    mounted() {
    },
    methods: {
      // Add any methods you need here
      applyPriceFilter() {
        // Emit an event to the parent component with the selected price range
        this.$emit('price-filter-applied', {
          min: this.minPriceValue,
          max: this.maxPriceValue,
        });
      },
      validatePriceFilter() {
        this.isError = false;
        this.isFilterValidated = false;
        this.errorMessage = '';

        const minPrice = parseInt(this.minPriceValue);
        const maxPrice = parseInt(this.maxPriceValue);

        if( this.minPriceValue && this.maxPriceValue ) {
          // Validate the price range
          if (minPrice < 0) {
            this.isError = true;
            this.errorMessage = 'El precio mínimo no puede ser menor a 0';
            return;
          }
          if (maxPrice < 0) {
            this.isError = true;
            this.errorMessage = 'El precio mínimo no puede ser menor a 0';
            return;
          }
          if (minPrice >= maxPrice) {
            this.isError = true;
            this.errorMessage = 'El precio mínimo no puede ser mayor al precio máximo';
            return;
          }

          this.isFilterValidated = true;
        }

      
      },
    },
  }
</script>