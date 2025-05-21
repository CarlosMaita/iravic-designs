<template>
      <div class="col-md-6">
    <div class="ps-md-4 ps-xl-5">
        <!-- Title -->
        <h1 class="h3">{{ name.charAt(0).toUpperCase() + name.slice(1).toLowerCase() }}</h1>

        <!-- Description -->
        <p class="fs-sm mb-0">{{description}}</p>
    
        <!-- Price -->
        <div class="h4 d-flex align-items-center my-4">
        {{price}}
        <!-- <del class="fs-sm fw-normal text-body-tertiary ms-2">$156.00</del> -->
        </div>

        <!-- Color options -->
        <div v-if="!is_regular">
            <div v-if="currentCombination" class="mb-4">
                <label class="form-label fw-semibold pb-1 mb-2">Color: <span class="text-body fw-normal" id="colorOption">{{ currentCombination.text_color }}</span></label>
                <div class="d-flex flex-wrap gap-2" data-binded-label="#colorOption">
                    <div v-for="(combination, index) in combinations" :key="index"  :checked="combination.id === currentCombination.id">
                        <input @input="selectCombination(combination)"   
                            type="radio" 
                            class="btn-check" 
                            name="colors" 
                            :id="`combination-${ combination.id }`" 
                            :value="combination.id" />
                        <label :for="`combination-${ combination.id }`"  class="btn btn-image p-0" :data-label="combination.text_color">
                            <img :src="combination.url_thumbnail" width="56" :alt="combination.text_color">
                            <span class="visually-hidden">{{ combination.text_color }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Size select -->
            <div v-if="currentCombination"  class="mb-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <label class="form-label fw-semibold mb-0">Size</label>
                </div>
                <select class="form-select form-select-lg" data-select='{
                    "classNames": {
                    "containerInner": ["form-select", "form-select-lg"]
                    }
                }' aria-label="Material select">
                    <option value="">Elige una talla</option>
                    <option v-for="size in currentCombination.sizes" :key="size.size_id" 
                        :value="size.size_id">{{ size.size_name.toUpperCase() }}</option>
                </select>
            </div>
        </div>

        <!-- Count input + Add to cart button -->
        <div v-if="total_stock > 0" class="d-flex gap-3 pb-3 pb-lg-4 mb-3">
        <div class="count-input flex-shrink-0">
            <button @click="quantity--" type="button" class="btn btn-icon btn-lg" data-decrement aria-label="Decrement quantity">
            <i class="ci-minus"></i>
            </button>
            <input v-model="quantity" type="number" class="form-control form-control-lg" min="1" value="1" readonly>
            <button @click="quantity++" type="button" class="btn btn-icon btn-lg" data-increment aria-label="Increment quantity">
            <i class="ci-plus"></i>
            </button>
        </div>
        <button type="button" class="btn btn-lg btn-dark w-100">Add to cart</button>
        </div>
        <div v-else class="d-flex gap-3 pb-3 pb-lg-4 mb-3">
            <div class="count-input flex-shrink-0">
                <button @click="quantity--" type="button" class="btn btn-icon btn-lg" data-decrement aria-label="Decrement quantity">
                <i class="ci-minus"></i>
                </button>
                <input v-model="quantity" type="number" class="form-control form-control-lg" min="1" value="1" readonly>
                <button @click="quantity++" type="button" class="btn btn-icon btn-lg" data-increment aria-label="Increment quantity">
                <i class="ci-plus"></i>
                </button>
            </div>
            <button type="button" class="btn btn-lg btn-dark w-100" disabled>Agotado</button>
        </div>
    </div>
    </div>
</template>
<script>
    export default {
        components: {
        },
        props: {
            name: {
                type: String,
                required: true,
            },
            description: {
                type: String,
                default: '',
                required: true,
            },
            price: {
                type: String,
                required: true,
            },
            is_regular: {
                type: Boolean,
                required: true,
            },
            total_stock:{
                type: Number,
                required: true,
            },
            combinations: {
                type: Array, 
                Required: false,
            },
        },
        data() {
            return {
                quantity: 1,
                currentCombination: null 
            };
        },
         mounted() {
            console.log(this.combinations)
            // Set the default combination when the component is mounted
            if (this.combinations && this.combinations.length > 0 && !this.is_regular) {
                this.selectCombination(this.combinations[0]);
            }
        },
        watch: {
            quantity(newValue) {
                if (newValue < 1) {
                    this.quantity = 1;
                }
                if (newValue > this.total_stock ) {
                    this.quantity = this.total_stock;
                }
            }
        },
        methods: {
            selectCombination(combination){
                this.currentCombination = combination;
                // emit event to parent component
                this.$emit('combination-selected', combination);
            }
        }
    }
</script>