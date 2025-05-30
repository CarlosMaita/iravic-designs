<template>
      <div class="col-md-6">
    <div class="ps-md-4 ps-xl-5">
        <!-- Title -->
        <h1 class="h3">{{ name.charAt(0).toUpperCase() + name.slice(1).toLowerCase() }}</h1>

        <!-- Description -->
        <p class="fs-sm mb-0">{{description}}</p>
    
        <!-- Price -->
        <div class="h4 d-flex align-items-center my-4">
        {{price_str}}
        <!-- <del class="fs-sm fw-normal text-body-tertiary ms-2">$156.00</del> -->
        </div>

        <!-- Color options -->
        <div v-if="!is_regular">
            <div v-if="combinationSelected" class="mb-4">
                <label class="form-label fw-semibold pb-1 mb-2">Color: <span class="text-body fw-normal" id="colorOption">{{ combinationSelected.text_color }}</span></label>
                <div class="d-flex flex-wrap gap-2" data-binded-label="#colorOption">
                    <div v-for="(combination, index) in combinations" :key="index"  :checked="combination.id === combinationSelected.id">
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
            <div v-if="combinationSelected"  class="mb-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <label class="form-label fw-semibold mb-0">Talla</label>
                </div>
                <select v-model="sizeSelected" class="form-select form-select-lg" data-select='{
                    "classNames": {
                    "containerInner": ["form-select", "form-select-lg"]
                    }
                }' aria-label="Material select">
                    <option value="">Elige una talla</option>
                    <option v-for="size in combinationSelected.sizes" :key="size.size_id" 
                        :value="size">{{ size.size_name.toUpperCase() }}</option>
                </select>
            </div>
        </div>

        <!-- Count input + Add to cart button -->
        <div v-if="currentStock > 0" class="d-flex gap-3 pb-3 pb-lg-4">
            <div class="count-input flex-shrink-0">
                <button @click="quantity--" type="button" class="btn btn-icon btn-lg" data-decrement aria-label="Decrement quantity">
                <i class="ci-minus"></i>
                </button>
                <input v-model="quantity" type="number" class="form-control form-control-lg" min="1" value="1" readonly>
                <button @click="quantity++" type="button" class="btn btn-icon btn-lg" data-increment aria-label="Increment quantity">
                <i class="ci-plus"></i>
                </button>
            </div>
            <button @click="addItemCart()" type="button" class="btn btn-lg btn-dark w-100">Agregar al carrito</button>
        </div>
        <div v-else class="d-flex gap-3 pb-3 pb-lg-4 mb-3">
            <button type="button" class="btn btn-lg btn-dark w-100 gap-3" disabled>
                <i class="ci-frown fs-3"></i>
                Producto agotado
            </button>
        </div>


        <!-- Solicitar al ws buttom -->
        <div class="d-flex gap-3 pb-3 pb-lg-4 mb-3>">
            <button type="button" class="btn btn-lg btn-warning w-100 gap-3" @click="askWhatsApp()">
                <i class="ci-whatsapp fs-lg me-2"></i>
                <span class="">Consultar por WhatsApp</span>
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
            id: {
                type: Number,
                required: true,
            },
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
                type: Number,
                required: true,
            },
            price_str: {
                type: String,
                required: true,
            },
            is_regular: {
                type: Boolean,
                required: true,
            },
            url_detail : {
                type: String,
                required: true,
            },
            url_thumbnail: {
                type: String,
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
                combinationSelected: null ,
                sizeSelected: '',
                currentStock: 0,
            };
        },
         mounted() {
            // Set the default combination when the component is mounted
            if (this.combinations && this.combinations.length > 0 && !this.is_regular) {
                this.selectCombination(this.combinations[0]);
            }

            // Set the default stock when the component is mounted
            this.currentStock = this.is_regular ? this.total_stock : 1; // por defecto 1 si es NO regular

        },
        watch: {
            quantity(newValue) {
                if (newValue < 1) {
                    this.quantity = 1;
                }
                if (newValue > this.currentStock ) {
                    this.quantity = this.currentStock;
                }
            },
            sizeSelected(size) {
                if (size) {
                    this.currentStock = size.stock_total;
                }
            },
        },
        methods: {
            selectCombination(combination){
                this.combinationSelected = combination;
                this.sizeSelected = '', // reset size selection
                this.currentStock = 1; // reset stock to 1 when a combination is selected
                // emit event to parent component
                this.$emit('combination-selected', combination);
            },
            addItemCart(){
                // chequear que la talla fue seleccionada en caso de ser producto no regular 
                if( !this.is_regular && !this.sizeSelected){
                    this.$root.$refs.toastEcommerceComponent.showToast({
                        title: 'Error',
                        message: 'Por favor selecciona una talla',
                        type: 'error',
                    });
                    return;
                }

                const itemId = !this.is_regular ? this.combinationSelected.id : this.id;
                const sizeId = !this.is_regular ? this.sizeSelected.size_id : null;
                const encryptedId = btoa(`${itemId}-${sizeId}`);

                const item = {
                    id: encryptedId,
                    name: this.name,
                    price: this.price,
                    price_str: this.price_str,
                    color: !this.is_regular ? this.combinationSelected?.text_color : null,
                    size: !this.is_regular ? this.sizeSelected?.size_name?.toUpperCase() : null,
                    image: !this.is_regular ? this.combinationSelected.url_thumbnail : this.url_thumbnail,
                    url: this.url_detail,
                    quantity: this.quantity,
                };

                // Logic to add item to cart
                this.$root.$refs.cartEcommerceComponent.addItem(item);

                // alert success
                this.$root.$refs.toastEcommerceComponent.showToast({
                    title: 'Éxito',
                    message: 'Producto agregado al carrito',
                    type: 'success',
                });
            }, 
            askWhatsApp() {
                const message = `Hola, estoy interesado en el producto: ${this.name}. 
                \nPrecio: ${this.price_str}. 
                \nPor favor, contáctame para más información.`;
                const phoneNumber = '+584144519511'; // Reemplaza con el número de WhatsApp
                const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
                window.open(url, '_blank');
            },
        }
    }
</script>