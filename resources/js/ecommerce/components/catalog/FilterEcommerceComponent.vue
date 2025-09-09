<template>
  <!-- Filter sidebar that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
  <aside class="col-lg-3">
    <div class="offcanvas-lg offcanvas-start pe-lg-4" id="filterSidebar">
      <div class="offcanvas-header py-3">
        <h5 class="offcanvas-title">Filtrar productos</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="offcanvas"
          data-bs-target="#filterSidebar"
          aria-label="Cerrar"
        ></button>
      </div>
      <div class="offcanvas-body flex-column pt-2 py-lg-0">
        <!-- Filtros seleccionados + Ordenamiento -->
        <div   class="pb-4 mb-2 mb-xl-3">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="h6 mb-0">Filtros</h4>
            <button
              v-if="isFilterActive"
              @click="clearFilters()"
              type="button"
              class="btn btn-sm btn-secondary bg-transparent border-0 text-decoration-underline p-0 ms-2"
            >
              Limpiar todo
            </button>
          </div>
          <div class="d-flex flex-wrap gap-2">
            <!-- search selection -->
            <button v-if="selectedSearch" type="button" class="btn btn-sm btn-secondary">
              <i @click="removeSelection('Search')" class="ci-close fs-sm ms-n1 me-1"></i>
              {{ selectedSearch }}  
            </button>
            

            <!-- category selection -->
            <button v-if="selectedCategory"   type="button" class="btn btn-sm btn-secondary">
              <i @click="removeSelection('Category')" class="ci-close fs-sm ms-n1 me-1"></i>
              {{ selectedCategory.name }}
            </button>
            <!-- end category selection -->

                 
           <!-- price selection -->
            <button v-if="isPriceSelected" type="button" class="btn btn-sm btn-secondary">
              <i @click="cleanPriceSelection()" class="ci-close fs-sm ms-n1 me-1"></i>
              ${{ selectedminPrice }} - ${{ selectedmaxPrice }}
            </button>
            <!-- end price selection -->

            <!-- brand selection -->
            <button v-if="selectedBrand" type="button" class="btn btn-sm btn-secondary">
              <i @click="removeSelection('Brand')" class="ci-close fs-sm ms-n1 me-1"></i>
              {{ selectedBrand.name }}
            </button>
            <!-- end brand selection -->

              <!-- gender selection -->
            <button v-if="selectedGender" type="button" class="btn btn-sm btn-secondary">
              <i @click="removeSelection('Gender')" class="ci-close fs-sm ms-n1 me-1"></i>
              {{ selectedGender }}
            </button>
            <!-- end gender selection -->

            <!-- color seletion -->
            <button v-if="selectedColor" type="button" class="btn btn-sm btn-secondary">
              <i @click="removeSelection('Color')" class="ci-close fs-sm ms-n1 me-1"></i>
              {{ selectedColor.name }}
            </button>
            <!-- end color selection -->
          </div>
        </div>

        <div class="accordion">
          <!-- Categories -->
          <div v-if="!selectedCategory" class="accordion-item border-0 pb-1 pb-xl-2">
            <h4 class="accordion-header" id="headingCategories">
              <button
                type="button"
                class="accordion-button p-0 pb-3"
                data-bs-toggle="collapse"
                data-bs-target="#categories"
                aria-expanded="true"
                aria-controls="categories"
              >
                Categorías
              </button>
            </h4>
            <div
              class="accordion-collapse collapse show"
              id="categories"
              aria-labelledby="headingCategories"
            >
              <div class="accordion-body p-0 pb-4 mb-1 mb-xl-2">
                <div
                  style="height: 200px"
                  data-simplebar
                  data-simplebar-auto-hide="false"
                >
                  <ul class="nav flex-column gap-2 pe-3">
                    
                    <li v-for="category in categories" :key="category.id" class="nav-item mb-1">
                      <a @click="selectCategory(category)" class="nav-link d-block fw-normal p-0" href="#!">
                        {{ category.name }}
                        <!-- <span class="fs-xs text-body-secondary ms-1">(235)</span> -->
                      </a>
                    </li>

                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Price -->
          <range-price-filter-ecommerce-component  v-if="!isPriceSelected" @price-filter-applied="applyPriceFilter"></range-price-filter-ecommerce-component>

            <!-- Brands -->
          <div v-if="!selectedBrand" class="accordion-item border-0 pb-1 pb-xl-2 d-none">
            <h4 class="accordion-header" id="headingBrands">
              <button
                type="button"
                class="accordion-button p-0 pb-3"
                data-bs-toggle="collapse"
                data-bs-target="#brands"
                aria-expanded="true"
                aria-controls="brands"
              >
                Marcas
              </button>
            </h4>
            <div
              class="accordion-collapse collapse show"
              id="brands"
              aria-labelledby="headingBrands"
            >
              <div class="accordion-body p-0 pb-4 mb-1 mb-xl-2">
                <div
                  style="height: 220px"
                  data-simplebar
                  data-simplebar-auto-hide="false"
                >
                  <ul class="nav flex-column gap-2 pe-3">
                    
                    <li v-for="brand, index in brands" :key="index" class="nav-item mb-1">
                      <a @click="selectBrand(brand)" class="nav-link d-block fw-normal p-0" href="#!">
                        {{ brand.name }}
                        <!-- <span class="fs-xs text-body-secondary ms-1">(235)</span> -->
                      </a>
                    </li>

                  </ul>
                </div>
              </div>
            </div>
          </div>
  

          <!-- Genders -->
          <div v-if="!selectedGender" class="accordion-item border-0 pb-1 pb-xl-2">
            <h4 class="accordion-header" id="headingGenders">
              <button
                type="button"
                class="accordion-button p-0 pb-3"
                data-bs-toggle="collapse"
                data-bs-target="#genders"
                aria-expanded="true"
                aria-controls="genders"
              >
                Géneros
              </button>
            </h4>
            <div
              class="accordion-collapse collapse show"
              id="genders"
              aria-labelledby="headingGenders"
            >
              <div class="accordion-body p-0 pb-4 mb-1 mb-xl-2">
                <div
                  style="height: 100px"
                  data-simplebar
                  data-simplebar-auto-hide="false"
                >
                  <ul class="nav flex-column gap-2 pe-3">
                    
                    <li v-for="gender, index in genders" :key="index" class="nav-item mb-1">
                      <a @click="selectGender(gender)" class="nav-link d-block fw-normal p-0" href="#!">
                        {{ gender }}
                        <!-- <span class="fs-xs text-body-secondary ms-1">(235)</span> -->
                      </a>
                    </li>

                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!--  end Genders -->


          <!-- Color -->
          <div v-if="!selectedColor" class="accordion-item border-0 pb-1 pb-xl-2">
            <h4 class="accordion-header" id="headingColor">
              <button
                type="button"
                class="accordion-button p-0 pb-3"
                data-bs-toggle="collapse"
                data-bs-target="#color"
                aria-expanded="true"
                aria-controls="color"
              >
                Color
              </button>
            </h4>
            <div
              class="accordion-collapse collapse show"
              id="color"
              aria-labelledby="headingColor"
            >
              <div class="accordion-body p-0 pb-4 mb-1 mb-xl-2">
                <div class="d-flex flex-column gap-2">
                  <!-- colors options -->
                  <div v-for="color in colors" :key="color.id" class="d-flex align-items-center mb-1">
                    <input @click="selectColor(color)" type="checkbox" class="btn-check" :id="`color-${color.id}`" />
                    <label
                      :for="`color-${color.id}`"
                      class="btn btn-color fs-xl"
                      :style="`color: ${ color.code }`"
                    ></label>
                    <label :for="`color-${color.id}`" class="fs-sm ms-2">{{ color.name }}</label>
                  </div>
                  <!-- end colors options -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </aside>
</template>
<script>

export default {
    name: 'FilterEcommerceComponent',
    props:{
      categories : {
        type: Array,
        required: true
      }, 
      brands : {
        type: Array,
        required: true
      },
      genders : {
        type: Array,
        required: true
      },
      colors : {
        type: Array,
        required: true
      },
      search: {
        type: String,
        default: '',
      },
      category: {
        type: Number,
        default: '',
      },
    }, 
    
    data() {
        return {
          selectedCategory: null,
          selectedBrand: null,
          selectedGender: null,
          selectedColor: null,
          selectedSearch: null,
          selectedminPrice: null,
          selectedmaxPrice: null,
          isPriceSelected: false,
          isFilterActive: false,
        };
    },
    mounted(){
      if(this.search){
        this.selectedSearch = this.search;
      }
      if(this.category){
        this.selectedCategory = this.categories.find(category => category.id == this.category);
      }
      this.setFilter();
    },
    methods : {
      // Add any methods you need here
      selectCategory(category) {
        this.selectedCategory = category;
        this.isFilterActive = true
        this.setFilter();
      },
      selectBrand(brand) {
        this.selectedBrand = brand;
        this.isFilterActive = true
        this.setFilter();
      },
      selectGender(gender) {
        this.selectedGender = gender;
        this.isFilterActive = true
        this.setFilter();
      },
      selectColor(color) {
        this.selectedColor = color;
        this.isFilterActive = true
        this.setFilter();
      },
      applyPriceFilter(range) {
        // Implement your price filter logic here
        this.selectedminPrice = range.min;
        this.selectedmaxPrice = range.max;
        this.isPriceSelected = true;
        this.isFilterActive = true
        this.setFilter();
      },
      setFilter() {
        const filter = {
          category: this.selectedCategory ? this.selectedCategory.id : null,
          brand: this.selectedBrand ? this.selectedBrand.id : null,
          gender: this.selectedGender,
          color: this.selectedColor ? this.selectedColor.id : null,
          minPrice: this.selectedminPrice,
          maxPrice: this.selectedmaxPrice,
          search: this.selectedSearch,
        };
        // Emit an event to the parent component with the selected filters
        this.$emit('filter-applied', filter);
      },

      checkFilterActive(){
        if (this.selectedCategory == null && this.selectedBrand == null && this.selectedGender == null && this.selectedColor == null && this.selectedminPrice == null && this.selectedmaxPrice == null) {
          this.isFilterActive = false
        }
      },
      removeSelection(type) {
        this[`selected${type}`] = null;
        this.checkFilterActive();
        this.setFilter();
        type === 'Search'   ? this.cleanSearchParams('search') : null;
        type === 'Category' ? this.cleanSearchParams('category') : null;
      },
      cleanPriceSelection(){
        this.selectedminPrice = null;
        this.selectedmaxPrice = null;
        this.isPriceSelected = false;
        this.isFilterActive = false;
        this.checkFilterActive();
        this.setFilter();
      },
      clearFilters() {
        this.selectedCategory = null;
        this.selectedBrand = null;
        this.selectedGender = null;
        this.selectedColor = null;
        this.isPriceSelected = false;
        this.isFilterActive = false;
        this.setFilter();
      },
      cleanSearchParams(param) {
        const url = new URL(window.location.href);
        url.searchParams.delete(param);
        window.history.replaceState({}, '', url);
      }
      
    }
};
</script>
