<template>
    <div class="mb-3">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Info</a>
            </li>
            <li v-if="is_regular" class="nav-item">
                <a class="nav-link" id="multimedia-tab" data-toggle="tab" href="#multimedia" role="tab" aria-controls="multimedia" aria-selected="true">Multimedia</a>
            </li>
            <li v-if="is_regular" class="nav-item">
                <a class="nav-link" id="stocks-tab" data-toggle="tab" href="#stocks" role="tab" aria-controls="stocks" aria-selected="false">Atributos y Stocks</a>
            </li>
            <li v-if="!is_regular" class="nav-item">
                <a class="nav-link" id="combinations-tab" data-toggle="tab" href="#combinations" role="tab" aria-controls="combinations" aria-selected="false">Combinaciones y Stocks</a>
            </li>
        </ul>
        <!--  -->
        <div class="tab-content" id="myTabContent">
            <!--  -->
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <!--  -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div  class="form-check form-check-inline mb-4">
                            <input type="hidden" name="is_regular" value="0">
                            <input class="form-check-input" type="checkbox" name="is_regular" id="is_regular" value="1"  
                            v-model="is_regular">
                            <label class="form-check-label" for="is_regular">Es producto regular (Sin combinaciones)</label>
                        </div>
                        <div class="form-check form-check-inline mb-4">
                            <input type="hidden" name="is_featured" value="0">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" 
                            v-model="is_featured">
                            <label class="form-check-label" for="is_featured">Producto Destacado</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input class="form-control" id="name" name="name" type="text" v-model="product.name"   autofocus>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <!-- textarea -->
                            <textarea class="form-control" id="description" name="description" v-model="product.description" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="code">Código</label>
                            <input class="form-control" id="code" name="code" type="text" v-model="product.code"  >
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input class="form-control" id="slug" name="slug" type="text" v-model="product.slug" >
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="category_id">Categoría</label>
                            <v-select placeholder="Seleccionar"
                                        :options="categories" 
                                        label="name" 
                                        v-model="category"
                                        @input="setCategorySelected"
                                       >
                            </v-select>
                            <input type="hidden" name="category_id" v-model="categoryId">
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div v-if="has_gender" class="col-6">
                        <div class="form-group">
                            <label for="gender">Género</label>
                            <v-select placeholder="Seleccionar"
                                        :options="genders"
                                        v-model="gender"
                                        @input="setGenderSelected">
                            </v-select>
                            <input type="hidden" name="gender" v-model="product.gender">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="brand_id">Marca</label>
                            <v-select placeholder="Seleccionar"
                                        :options="brands" 
                                        label="name" 
                                        v-model="brand"
                                        @input="setBrandSelected"
                                        >
                            </v-select>
                            <input type="hidden" name="brand_id" v-model="brandId">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div :class="{'col-4' : canPricesPerMethodPayment, 'col-6': !canPricesPerMethodPayment }">
                        <div class="form-group">
                            <label for="price">Precio</label>
                            <input class="form-control" name="price" type="number" min="0" step="any" v-model="product.price">
                        </div>
                    </div>
                </div>
                <!-- hidden input -->
                <input type="hidden" name="temp_code" :value="temp_code">
            </div>
            <!--  -->
            <div class="tab-pane fade" id="multimedia" role="tabpanel" aria-labelledby="multimedia-tab">
                <div class="mt-3">
                    <v-dropzone 
                        ref="dropzone-regular"
                        id="dropzone-regular"
                        :options="dropzoneOptionsRegular"
                        @vdropzone-sending-multiple="sendingEventRegular"
                        @vdropzone-removed-file="removedFileEventRegular"
                        @vdropzone-error="errorEvent"
                        @vdropzone-success-multiple="successEventRegular"
                        v-once
                    ></v-dropzone>
                </div>
                <div v-if="regularProductImages.length > 0" class="mt-4">
                    <h5>Imágenes cargadas ({{ regularProductImages.length }})</h5>
                    <div class="row">
                        <div class="img-container col-md-2 mb-3" v-for="(image, index_image) in regularProductImages" :key="`imagen-regular-${image.id || index_image}`">
                            <span class="btn-img-remove" type="button" @click="removeImageRegular($event, image.id)">
                                <i class="fas fa-times"></i> 
                            </span>
                            <span v-if="image.is_primary" class="badge-primary-img">
                                <i class="fas fa-star"></i>
                            </span>
                            <span v-else class="btn-img-primary" type="button" @click="setPrimaryImageRegular($event, image.id)" title="Establecer como principal">
                                <i class="far fa-star"></i> 
                            </span>
                            <span class="badge-position-img">
                                {{ index_image + 1 }}
                            </span>
                            <img :src="image.url_img" class="img-thumbnail">
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="stocks" role="tabpanel" aria-labelledby="stocks-tab">
                <!-- stock with store -->
                <div class="row mt-3"> 
                    <div v-for="(product_store) in product_stores" :key="`store-${product_store.store_id}`" class="col-md-4">
                        <div class="form-group">
                            <label :for="`store-${product_store.store_id}`">{{ product_store.store_name }}</label>
                            <input type="number" class="form-control input-store" 
                                :id="`store-${product_store.store_id}`" 
                                :name="`stores[${product_store.store_id}]`"
                                v-model="product_stores.find(store => store.store_id == product_store.store_id).stock">
                        </div>
                    </div>
                </div>
                <!-- end stock with store -->
            </div>

            <div class="tab-pane fade" id="combinations" role="tabpanel" aria-labelledby="combinations-tab">
                <div class="d-flex justify-content-end my-3">
                    <button class="btn btn-light" type="button" @click="addCombination"><i class="fas fa-plus"></i> Agregar Combinación</button>
                </div>
                <div v-for="(combination, index) in combinations" :key="`combination-${index}`">
                    <div>
                        <input type="hidden" name="combinations_group[]" :value="index">
                    </div>
                    <div  class="card p-3">
                        <div class="row">
                            <div class="col-12">
                                <p><b>Combinación #{{ (index + 1) }}</b> <button class="btn btn-sm btn-danger" type="button" @click="removeCombination(index, combination)"><i class="fas fa-trash-alt"></i></button></p> 
                            </div>
                        </div>
                        <div class="row">
                            <!-- color -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label :for="`color-${index}`">Color</label>
                                    <v-select placeholder="Seleccionar"
                                                :options="colors" 
                                                label="name" 
                                                v-model="combinations[index].color_prop"
                                                @input="setCombinationColorSelected(combinations[index].color_prop, index)">
                                    </v-select>
                                    <!-- <input type="hidden" :name="getCombinationInputName('colors', combination, index)" v-model="combinations[index].color_id"> -->
                                    <input type="hidden" :name="`combinations_group_colors[${index}]`" v-model="combinations[index].color_id">
                                </div>
                            </div>
                            <!-- text color -->
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label :for="`text_color-${index}`">Color en texto</label>
                                    <input class="form-control" :id="`text_color-${index}`" :name="`combinations_group_text_colors[${index}]`" type="text" 
                                    v-model="combinations[index].text_color">
                                </div>
                            </div>

                            <!-- codigo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Código</label>
                                    <!-- <input class="form-control" :id="`code-${index}`" type="text" :name="getCombinationInputName('codes', combination, index)" v-model="combination.code"> -->
                                    <input class="form-control" :id="`code-${index}`" :name="`combinations_group_code[${index}]`" type="text" v-model="combination.code">
                                </div>
                            </div>
                        </div>
                        <!-- selector de images de combinacion -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <v-dropzone :ref="`dropzone-${index}`" 
                                    :id="`dropzone-${index}`"
                                    :options="dropzoneOptions"
                                    @vdropzone-sending-multiple="sendingEvent"
                                    @vdropzone-removed-file="removedFileEvent"
                                    @vdropzone-error="errorEvent"
                                v-once ></v-dropzone>
                            </div>
                        </div>
                        <!-- imagenes de la combinacion  -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="img-container" v-for="(image, index_image) in images.filter( image => image.combination_index == combination.combination_index)" :key="`imagen-${index_image}`">
                                    <span class="btn-img-remove" type="button" @click="removeImage($event, image.id)">
                                        <i class="fas fa-times"></i> 
                                    </span>
                                    <span v-if="image.is_primary" class="badge-primary-img">
                                        <i class="fas fa-star"></i>
                                    </span>
                                    <span v-else class="btn-img-primary" type="button" @click="setPrimaryImage($event, image.id)" title="Establecer como principal">
                                        <i class="far fa-star"></i> 
                                    </span>
                                    <img :src="image.url_img" class="img-thumbnail" :data-combination-id="`${image.combination_index}`">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div v-for="(size, index_size) in combination.sizes" :key="`combination-${index_size}`">
                                <div class="row">
                                    <div class="col-12">
                                        <input v-if="!size.product_id" type="hidden" :name="`combinations[${index}][]`" :value="index_size">
                                        <input v-if="size.product_id" type="hidden" :name="`product_combinations[${index}][]`" :value="size.id">
                                        <input type="hidden" :name="getCombinationInputName('colors', size, index, index_size)" v-model="combination.color_id">
                                        <input type="hidden" :name="getCombinationInputName('text_colors', size, index, index_size)" v-model="combination.text_color">
                                        <input type="hidden" :name="getCombinationInputName('codes', size, index, index_size)" v-model="combination.code">
                                    </div>
                                </div>
                                <div class="row">
                                    <div v-show="has_size" :class="{'col-md-3' : canPricesPerMethodPayment, 'col-6': !canPricesPerMethodPayment }">
                                        <div class="form-group">
                                            <label  v-if="has_size" :for="`size-${index}-${index_size}`">Talla <b>#{{ (index_size + 1)}}</b>  <button class="btn btn-sm btn-danger" type="button" @click="removeSize(index_size, index, size.id)"><i class="fas fa-trash-alt"></i></button></label>
                                            <v-select  v-if="has_size" placeholder="Seleccionar"
                                                        label="name" 
                                                        :options="SizesFiltered" 
                                                        v-model="combinations[index].sizes[index_size].size_prop"
                                                        :selectable="(option) => !combination.sizes.map(function(size) {return size.size_id;}).includes(option.id)"
                                                        @input="setCombinationSizeSelected(combinations[index].sizes[index_size].size_prop, index, index_size)">
                                            </v-select>
                                            <input type="hidden" :name="getCombinationInputName('sizes', size, index, index_size)" v-model="combinations[index].sizes[index_size].size_id">
                                        </div>
                                    </div>
                                    <div :class="{'col-md-3' : canPricesPerMethodPayment, 'col-6': !canPricesPerMethodPayment }">
                                        <div class="form-group">
                                            <label :for="`price-${index}-${index_size}`">Precio</label>
                                            <input class="form-control" :id="`price-${index}-${index_size}`" type="number" min="0" step="any" :name="getCombinationInputName('prices', size, index, index_size)" v-model="combination.sizes[index_size].price">
                                        </div>
                                    </div>
                                </div>
                                <!-- store for combination  -->
                                 <div class="row">
                                    <div v-for="(product_store) in combination.sizes[index_size].product_stores"  :key="`store-${product_store.store_id}`"   class="col-md-4">
                                        <div class="form-group">
                                            <label :for="`store-${product_store.store_id}-${index}-${index_size}`">{{ product_store.store_name }}</label>
                                            <input type="number" class="form-control" 
                                                    :id="`store-${product_store.store_id}-${index}-${index_size}`" 
                                                    :name="getCombinationInputName(`store_${product_store.store_id}`, size, index, index_size)" 
                                                    v-model="combination.sizes[index_size].product_stores.find(store => store.store_id == product_store.store_id).stock">  
                                        </div>
                                    </div>
                                 </div>
                                <hr>
                            </div>
                            <div v-if="has_size" class="d-flex justify-content-end my-3">
                                <button class="btn btn-light" type="button" @click="addSize(combination)"><i class="fas fa-plus"></i> Agregar otra Talla</button>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
        },
        props: {
            product: {
                type: Object,
                default: () => ({
                    name: '',
                    description: '',
                    code: '',
                    is_regular: 1,
                    is_featured: 0,
                    product_combinations: []
                })
            },
            brands: {
                type: Array,
                default: []
            },
            categories: {
                type: Array,
                default: []
            },
            canPricesPerMethodPayment: {
                type: Boolean,
                default : false
            },
            colors: {
                type: Array,
                default: []
            },
            genders: {
                type: Array,
                default: []
            },
            sizes: {
                type: Array,
                default: []
            },
            stores: {
                type: Array,
                default: []
            },
            images: {
                type: Array,
                default: []
            },
            is_updating: {
                type: Boolean,
                default: false
            },
            type_sizes: {
                type: Array,
                default: []
            },
            temp_code: {
                type: String,
                default: ''
            },
            urlProducts: {
                type: String,
                default: ''
            },
            urlProductsCombinations: {
                type: String,
                default: ''
            },
            urlResource: {
            type: String,
            default: "",
            },
            urlDeleteResource: {
                type: String,
                default: "",
            },
            
        },
        data: () => ({
            brand: null,
            category: null,
            gender: null,
            product_stores: [],
            is_regular: 1,
            is_featured: 0,
            combinations: [],
            loading: false,
            mounted: false,
            dropzoneOptions: {
                url: "",
                acceptedFiles: "image/*",
                autoProcessQueue: true,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 10,
                maxFilesize: 2,
                addRemoveLinks: true,
                thumbnailWidth: 150,
                autoDiscover: false,
            },
            dropzoneOptionsRegular: {
                url: "",
                acceptedFiles: "image/*",
                autoProcessQueue: true,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 10,
                maxFilesize: 2,
                addRemoveLinks: true,
                thumbnailWidth: 150,
                autoDiscover: false,
            },
            regularProductImages: [],
            has_gender : true, 
            has_size : true,
        }),
	    computed: {
            categoryId: function(){
                let ids = this.categories.map( obj => obj.id)
                if(this.category){
                    if(!ids.includes(this.category.id) ) this.category = null;
                    return this.category.id
                }
                return null;
            },
            brandId: function(){
                let ids = this.brands.map( obj => obj.id);
                if(this.brand){
                    if(!ids.includes(this.brand.id) ) this.brand.id = null;
                    return this.brand.id;
                }
                return null;
            },
            SizesFiltered: function(){
                // Validar si el producto tiene categoria
                if (!this.category) return null;
                // Validar si la categoria tiene talla 
                if (!this.has_size) {
                    return this.sizes.filter( (size) =>  size.type_size_id === null ); // retorna la talla - sin talla
                }
                // Validar si el producto tiene genero
                if (!this.gender)   return null;
                

                let base_category_id = this.category.base_category_id;
                let type_size_filtered = this.type_sizes.filter(
                    (type_size) => {
                        let genders = type_size.genders.split(',');
                        return type_size.base_category_id == base_category_id && genders.includes(this.gender)
                    }
                );
                let type_size_filtered_id = type_size_filtered.length > 0 ? type_size_filtered[0].id : null;
                // this.resetCombinations();
                return this.sizes.filter( (size) =>  type_size_filtered_id === size.type_size_id );
            } 
	    },
        async mounted() {
           
            this.mounted = true;

            Object.assign(this.dropzoneOptions, {
                url: this.urlResource,
                dictDefaultMessage: "Arrastra los archivos aquí para subirlos (Max 2MB)",
                dictFallbackMessage: "Su navegador no admite la carga de archivos mediante la función de arrastrar y soltar.",
                dictFallbackText: "Utilice el formulario de respaldo a continuación para cargar sus archivos como en los viejos tiempos.",
                dictFileTooBig: "El archivo es demasiado grande. Máx: 2MB.",
                dictInvalidFileType: "No puede cargar archivos de este tipo.",
                dictResponseError: "El servidor respondió con el código statusCode.",
                dictCancelUpload: "Cancelar carga",
                dictCancelUploadConfirmation: "¿Estás seguro de que deseas cancelar esta carga?",
                dictRemoveFile: "Remover archivo",
                dictMaxFilesExceeded: "No puede cargar más archivos.", 
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('input[name="_token"]')
                        .getAttribute("value"),
                }
            });

            // Initialize dropzone options for regular products
            Object.assign(this.dropzoneOptionsRegular, {
                url: this.urlResource,
                dictDefaultMessage: "Arrastra los archivos aquí para subirlos (Max 2MB)",
                dictFallbackMessage: "Su navegador no admite la carga de archivos mediante la función de arrastrar y soltar.",
                dictFallbackText: "Utilice el formulario de respaldo a continuación para cargar sus archivos como en los viejos tiempos.",
                dictFileTooBig: "El archivo es demasiado grande. Máx: 2MB.",
                dictInvalidFileType: "No puede cargar archivos de este tipo.",
                dictResponseError: "El servidor respondió con el código statusCode.",
                dictCancelUpload: "Cancelar carga",
                dictCancelUploadConfirmation: "¿Estás seguro de que deseas cancelar esta carga?",
                dictRemoveFile: "Remover archivo",
                dictMaxFilesExceeded: "No puede cargar más archivos.", 
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('input[name="_token"]')
                        .getAttribute("value"),
                }
            });

            // Load existing images for regular products
            if (this.product.id && this.product.is_regular !== false) {
                this.regularProductImages = this.images.filter(img => !img.combination_index || img.combination_index === 0);
            }


             /**
             * Cargar stores de producto Regular
             */
            this.product_stores = this.stores.map((store) => {
                const productStore =  (this.product.stores && this.product.stores.find) ? this.product.stores.find(pStore => pStore.id === store.id) : null;
                return {
                    store_id: store.id,
                    store_name: store.name,
                    stock: productStore ? productStore.pivot.stock : 0
                }
            });

            /**
             * Si el producto existe, se asignan sus datos a los models, y se crean items para sus combinaciones y tallas.
             */
            if (this.product.id) {
                if (!this.product.is_regular) {
                    this.is_regular = 0;
                }
                if (this.product.is_featured) {
                    this.is_featured = 1;
                }
                this.brand = this.product.brand;
                this.category = this.product.category;
                this.gender = this.product.gender;

                const selectedCategory = this.categories.find(category => category.id === this.product.category_id);
                if (selectedCategory) {
                    this.has_gender = !!selectedCategory.base_category.has_gender;
                    this.has_size = !!selectedCategory.base_category.has_size;
                }

                // ordenar productos combinados por combinacion index
                this.product.product_combinations.sort((a, b) => a.combination_index - b.combination_index);

                if (this.product.product_combinations) {
                    for (var i=0; i<this.product.product_combinations.length; i++) {
                        const combination = this.product.product_combinations[i];
                        var index = this.getIndex(combination.combination_index);

                        if (index < 0) {
                            var new_combination = {
                                code: combination.code,
                                color_id: combination.color_id,
                                text_color: combination.text_color,
                                combination_index: combination.combination_index,
                                color_prop: combination.color,
                                sizes: [
                                    {
                                        id: combination.id,
                                        product_id: combination.product_id,
                                        size_id: combination.size_id,
                                        size_prop: combination.size,
                                        price: combination.price,
                                        product_stores:  this.stores.map((store) => {
                                            const productStore =  (combination.stores && combination.stores.find) ? combination.stores.find(pStore => pStore.id === store.id) : null;
                                            return {
                                                store_id: store.id,
                                                store_name: store.name,
                                                stock: productStore ? productStore.pivot.stock : 0
                                            }
                                        })
                                    }
                                ]
                            };

                            this.combinations.push(new_combination);
                        } else {
                            var new_size = {
                                id: combination.id,
                                product_id: combination.product_id,
                                size_id: combination.size_id,
                                size_prop: combination.size,
                                price: combination.price,
                                product_stores: this.stores.map((store) => {
                                    const productStore =  (combination.stores && combination.stores.find) ? combination.stores.find(pStore => pStore.id === store.id) : null;
                                    return {
                                        store_id: store.id,
                                        store_name: store.name,
                                        stock: productStore ? productStore.pivot.stock : 0
                                    }
                                })
                            }

                            this.combinations[index].sizes.push(new_size);
                        }
                    }

                }
            }
        },
        methods: {

            

            /**
             * Retorna indice de la combinacion  del listado de combinaciones del producto
             */
            getIndex(combination_index) {
                var index = this.combinations.map(e => e.combination_index).indexOf(combination_index);
                return index;
            },

            /**
             * Setea la marca seleccionada. Porque el select vue retorna el objeto
             */
            setBrandSelected(value) {
                this.product.brand_id = value ? value.id : null;
            },

            /**
             * Setea la categoria seleccionada. Porque el select vue retorna el objeto
             */
            setCategorySelected(value) {
                this.resetCombinations();
                this.product.category_id = value ? value.id : null;
                this.has_gender = !!value.base_category.has_gender;
                this.has_size = !!value.base_category.has_size;
            },

            /**
             * Setea el genero seleccionado. Porrque el select vue retorna el objeto
             */
            setGenderSelected(value) {
                this.resetCombinations();
                this.product.gender = value;
            },

            /**
             * Setea el color de una combinacion. Porque el select vue retorna el objeto
             */
            setCombinationColorSelected(value, index) {
                this.combinations[index].color_id = value ? value.id : null;
            },

            /**
             * Setea la talla de una combinacion. Porque el select vue retorna el objeto
             */
            setCombinationSizeSelected(value, index, index_size) {
                this.combinations[index].sizes[index_size].size_id = value ? value.id : null;
            },

            /**
             * Agrega al listado de combinaciones, un objeto combinacion sin datos
             */
            addCombination() {

                if ( !this.category && !this.gender ) {
                    new Noty({
                            text: 'Debe seleccionar la categoría y el genero del producto ',
                            type: 'error'
                        }).show();
                    return false;
                }

                let new_combination = {
                    code: null,
                    color_prop: null,
                    color_id: null,
                    text_color: null,
                    sizes: [
                        {
                            id: null,
                            product_id: null,
                            color_id: null,
                            size_id: null,
                            size_prop: null,
                            code: null,
                            price: null,
                            product_stores:  this.stores.map((store) => {
                                return {
                                    store_id: store.id,
                                    store_name: store.name,
                                    stock: 0
                                }
                            })

                        }
                    ]
                };

                if (!this.has_size){
                    // selecciono la talla sin talla
                    const size_sin_talla =   this.sizes.find( (size) => size.type_size_id === null );
                    new_combination.sizes[0].size_id = size_sin_talla.id;
                    new_combination.sizes[0].size_prop = size_sin_talla;
                } 

                this.combinations.push(new_combination);
            },
            
            /**
             * Agrega al listado de tallas de una combinacion, un objeto talla sin datos
             */
            addSize(combination) {
                var combination_size = {
                            color_prop: null,
                            color_id: null,
                            id: null,
                            product_id: null,
                            size_id: null,
                            size_prop: null,
                            price: null,
                            product_stores:  this.stores.map((store) => {
                                return {
                                    store_id: store.id,
                                    store_name: store.name,
                                    stock: 0
                                }
                            })
                        };

                combination.sizes.push(combination_size);
            },

            /**
             * Retorna nombre que se le asigna a un input de la combinancion
             * Para que todos los inputs de una misma combinacion tengan sentido, se les pone el mismo prefio
             * Nombre del input + existing (Si existe en la bd) + 
             *  - id de la combinacion si existe
             *  - indice de la talla si no existe y es una nueva talla
             */
            getCombinationInputName(input, product_combination, index, index_size) {
                var input_name = '';

                if (product_combination.product_id) {
                    input_name = `${input}_existing[${index}][${product_combination.id}]`;
                } else {
                    input_name = `${input}[${index}][${index_size}]`;
                }

                return input_name;
            },


            /**
             * Peticion HTTP a la api para eliminar una talla de una combinacion
             */
            async httpDeleteCombinationSize(index, index_combination, product_combination_id) {
                try {
                    $('body').append('<div class="loading">Loading&#8230;</div>');
                    var url = `${this.urlProducts}/${product_combination_id}`;
                    var response = await this.$axios.delete(url);
                    
                    if (response.data.success) {
                        this.combinations[index_combination].sizes.splice(index, 1);

                        new Noty({
                            text: 'La talla ha sido eliminada con éxito.',
                            type: 'success'
                        }).show();
                    } else {
                        new Noty({
                            text: 'La talla no ha podido ser eliminada en este momento.',
                            type: 'error'
                        }).show();
                    }

                    $('.loading').remove();
                } catch (_error) {
                    $('.loading').remove();

                    new Noty({
                            text: 'La talla no ha podido ser eliminada en este momento.',
                            type: 'error'
                        }).show();
                }
            },

            /**
             * Peticion HTTP a la api para eliminar una combinacion
             */
            async httpDeleteCombination(index, combination) {
                try {
                    $('body').append('<div class="loading">Loading&#8230;</div>');
                    var products_params = this.getProductIdsToDeleteCombination(combination);
                    var url = `${this.urlProductsCombinations}?products=${products_params}`;
                    var response = await this.$axios.delete(url);

                    if (response.data.success) {
                        this.combinations.splice(index, 1);

                        new Noty({
                            text: 'La combinación ha sido eliminada con éxito.',
                            type: 'success'
                        }).show();
                    } else {
                        new Noty({
                            text: 'La combinación no ha podido ser eliminada en este momento.',
                            type: 'error'
                        }).show();
                    }

                    $('.loading').remove();
                } catch (_error) {
                    $('.loading').remove();

                    new Noty({
                            text: 'La combinación no ha podido ser eliminada en este momento.',
                            type: 'error'
                        }).show();
                }
            },

            /**
             * Retorna booleano indicando si una combinacion tiene un producto existente en la BD
             */
            hasCombinationExistingProduct(combination) {
                if (combination.sizes.filter(e => e.product_id > 0).length > 0) {
                    return true;
                }

                return false;
            },

            /**
             * Retorna listado de ids de combinaciones de un producto
             */
            getProductIdsToDeleteCombination(combination) {
                var ids = combination.sizes
                        .filter(function(obj) {
                            return obj.id > 0;
                        })
                        .map(function(obj) {
                            return obj.id;
                        });

                return ids.join();
            },

            /**
             * Elimina una combinacion del producto.
             * Si la combinacion ya existia en BD, llama a httpDeleteCombination para realizar peticion a la api y eliminarla
             * Si no existe en la BD, simplemente se elimina del listado de combinaciones
             */
            removeCombination(index, combination) {
                if (index < 0) return;
                
                var self = this;

                if (this.hasCombinationExistingProduct(combination)) {
                    swal({
                        title: '',
                        text: "¿Seguro desea eliminar esta combinación?",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Si',
                        cancelButtonText: 'No'
                    }).then(function () {
                        self.httpDeleteCombination(index, combination);
                    }).catch(swal.noop);
                } else {
                    self.combinations.splice(index, 1);
                }
            },

            /**
             * Elimina una talla de una combinacion.
             * Si la talla ya existia en BD, llama a httpDeleteCombinationSize para realizar peticion a la api y eliminarla
             * Si no existe en la BD, simplemente se elimina del listado de tallas de la combinacion
             */
            removeSize(index, index_combination, product_combination_id = null) {
                if (index < 0) return;
                
                let self = this;

                if (product_combination_id) {
                    swal({
                        title: '',
                        text: "¿Seguro desea eliminar esta talla?",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Si',
                        cancelButtonText: 'No'
                    }).then(function () {
                        self.httpDeleteCombinationSize(index, index_combination, product_combination_id);
                    }).catch(swal.noop);
                } else {
                    self.combinations[index_combination].sizes.splice(index, 1);
                }
            },
            resetCombinations() {
                if(this.combinations.length > 0 ){ 
                    this.combinations = [];
                }
            }, 
            sendingEvent(file, xhr, formData) {
                let ref =  file[0].previewElement.parentElement.id;
                let combination_index = ref.replace("dropzone-", "");
                formData.append('combination_index', combination_index);
                formData.append('temp_code', this.temp_code);
            },
            errorEvent(file, message, xhr) {
                if (typeof message !== 'string') {
                    //Es un mensaje de validación de laravel
                    message = message.message
                }
               
                // remover file 
                new Noty({
                        text: message,
                        type: 'error'
                    }).show();


                let ref =  file.previewElement.parentElement.id;
                this.$refs[ref][0].removeFile(file)
            }, 
            removedFileEvent(file, error, xhr){
                let response = JSON.parse(file.xhr.response);
                let combination_index = response.data[0].combination_index
                
                // make a request to your server to delete the file
                axios({
                    url: this.urlDeleteResource,
                    method: 'post',
                    headers: { 
                    "X-CSRF-TOKEN": document
                        .querySelector('input[name="_token"]')
                        .getAttribute("value"),
                    },
                    data: {
                        fileName: file.name,
                        combinationIndex: combination_index
                    }
                }).then(response => {
                    new Noty({
                        text: 'Imagen removida con exito.',
                        type: 'success'
                    }).show();
                }).catch(error => {
                    // console.log(error);
                });
            },
            removeImage(e, image_id) {
                

                let url = this.urlDeleteResource;

                swal({
                        title: '',
                        text: "¿Seguro desea eliminar esta imagen?",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Si',
                        cancelButtonText: 'No'
                    }).then(function ()  {
                        axios({
                            url: url,
                            method: 'post',
                            headers: { 
                            "X-CSRF-TOKEN": document
                                .querySelector('input[name="_token"]')
                                .getAttribute("value"),
                            },
                            data: {
                                image_id
                            }
                        }).then(response => {
                            // remover imagen en DOM
                            e.target.parentElement.parentElement.remove();
                            new Noty({
                                text: 'Imagen removida con exito.',
                                type: 'success'
                            }).show();
                            return false;
                        }).catch(error => {
                            // console.log(error);
                        });
                       
                    }).catch(swal.noop);

               
            },

            setPrimaryImage(e, image_id) {
                const vm = this;
                let url = '/admin/catalogo/producto-imagen/set-primary';

                axios({
                    url: url,
                    method: 'post',
                    headers: { 
                        "X-CSRF-TOKEN": document
                            .querySelector('input[name="_token"]')
                            .getAttribute("value"),
                    },
                    data: {
                        image_id
                    }
                }).then(response => {
                    if (response.data.success) {
                        // Actualizar el estado de las imágenes
                        vm.images = vm.images.map(img => ({
                            ...img,
                            is_primary: img.id === image_id
                        }));
                        
                        new Noty({
                            text: response.data.message,
                            type: 'success'
                        }).show();
                    }
                }).catch(error => {
                    new Noty({
                        text: error.response?.data?.message || 'Error al establecer imagen principal',
                        type: 'error'
                    }).show();
                });
            },

            // Methods for regular products dropzone
            sendingEventRegular(files, xhr, formData) {
                formData.append('combination_index', 0); // 0 for regular products
                formData.append('temp_code', this.temp_code);
            },

            successEventRegular(files, response) {
                if (response.success && response.data) {
                    // Add new images to the list with proper position indexing
                    response.data.forEach((imageData, index) => {
                        this.regularProductImages.push({
                            id: null, // Will be set when product is saved
                            url: imageData.url,
                            url_img: imageData.url_img || `/storage/products/${imageData.url}`,
                            url_original: imageData.url_original,
                            temp_code: imageData.temp_code,
                            combination_index: imageData.combination_index,
                            is_primary: false,
                            position: this.regularProductImages.length + index
                        });
                    });

                    new Noty({
                        text: 'Imágenes cargadas con éxito',
                        type: 'success'
                    }).show();
                }
            },

            removedFileEventRegular(file, error, xhr) {
                if (!file.xhr) return;
                
                try {
                    let response = JSON.parse(file.xhr.response);
                    if (!response.data || !response.data.length) return;
                    
                    let fileName = file.name;
                    
                    // Remove from server
                    axios({
                        url: this.urlDeleteResource,
                        method: 'post',
                        headers: { 
                            "X-CSRF-TOKEN": document
                                .querySelector('input[name="_token"]')
                                .getAttribute("value"),
                        },
                        data: {
                            fileName: fileName,
                            combinationIndex: 0
                        }
                    }).then(response => {
                        // Remove from local list
                        this.regularProductImages = this.regularProductImages.filter(img => 
                            img.url_original !== fileName
                        );
                        
                        new Noty({
                            text: 'Imagen removida con éxito.',
                            type: 'success'
                        }).show();
                    }).catch(error => {
                        console.error('Error removing image:', error);
                    });
                } catch (parseError) {
                    console.error('Error parsing response:', parseError);
                    // Still allow removal from dropzone even if we can't parse the response
                }
            },

            removeImageRegular(e, image_id) {
                const vm = this;
                let url = this.urlDeleteResource;

                swal({
                    title: '',
                    text: "¿Seguro desea eliminar esta imagen?",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                }).then(function() {
                    axios({
                        url: url,
                        method: 'post',
                        headers: { 
                            "X-CSRF-TOKEN": document
                                .querySelector('input[name="_token"]')
                                .getAttribute("value"),
                        },
                        data: {
                            image_id
                        }
                    }).then(response => {
                        // Remove from local list
                        vm.regularProductImages = vm.regularProductImages.filter(img => img.id !== image_id);
                        
                        new Noty({
                            text: 'Imagen removida con éxito.',
                            type: 'success'
                        }).show();
                    }).catch(error => {
                        new Noty({
                            text: 'Error al eliminar la imagen',
                            type: 'error'
                        }).show();
                    });
                }).catch(swal.noop);
            },

            setPrimaryImageRegular(e, image_id) {
                const vm = this;
                let url = '/admin/catalogo/producto-imagen/set-primary';

                axios({
                    url: url,
                    method: 'post',
                    headers: { 
                        "X-CSRF-TOKEN": document
                            .querySelector('input[name="_token"]')
                            .getAttribute("value"),
                    },
                    data: {
                        image_id
                    }
                }).then(response => {
                    if (response.data.success) {
                        // Update local state
                        vm.regularProductImages = vm.regularProductImages.map(img => ({
                            ...img,
                            is_primary: img.id === image_id
                        }));
                        
                        new Noty({
                            text: response.data.message,
                            type: 'success'
                        }).show();
                    }
                }).catch(error => {
                    new Noty({
                        text: error.response?.data?.message || 'Error al establecer imagen principal',
                        type: 'error'
                    }).show();
                });
            },

        }, 
        
    }
</script>

<style lang="scss">
    .select2-container .select2-selection--single {
        height: 37px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 37px;
    }

    .vue-dropzone {
        border: 2px dashed gray;
    }
    .img-container{
        position: relative;
        margin-left: 10px;
    }

    .btn-img-remove{
        position: absolute;
        top: 0px;
        right: 5px;
        cursor: pointer;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 12px;
        z-index: 10;
    }
    
    .btn-img-primary{
        position: absolute;
        top: 0px;
        left: 5px;
        cursor: pointer;
        background: rgba(255, 193, 7, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 12px;
        z-index: 10;
    }
    
    .badge-primary-img{
        position: absolute;
        top: 0px;
        left: 5px;
        background: rgba(40, 167, 69, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 12px;
        z-index: 10;
    }

    .badge-position-img{
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: rgba(0, 123, 255, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 12px;
        z-index: 10;
    }

    .img-thumbnail{
        width: 100px;
        height: 100px;
    }

    .dz-error-message {
        display: none !important;
    }
</style>