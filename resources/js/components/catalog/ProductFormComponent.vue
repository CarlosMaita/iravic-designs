<template>
    <div class="mb-3">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Info</a>
            </li>
            <li class="nav-item">
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
                        <div class="form-check form-check-inline mb-4">
                            <input class="form-check-input" type="checkbox" name="is_regular" id="is_regular" value="1" 
                            v-model="is_regular">
                            <label class="form-check-label" for="is_regular">Es producto regular (Sin combinaciones)</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input class="form-control" id="name" name="name" type="text" v-model="product.name" autofocus>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="code">Código</label>
                            <input class="form-control" id="code" name="code" type="text" v-model="product.code">
                        </div>
                    </div>
                    <div class="col-6">
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
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="category_id">Categoría</label>
                            <v-select placeholder="Seleccionar"
                                        :options="categories" 
                                        label="name" 
                                        v-model="category"
                                        @input="setCategorySelected">
                            </v-select>
                            <input type="hidden" name="category_id" v-model="product.category_id">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="brand_id">Marca</label>
                            <v-select placeholder="Seleccionar"
                                        :options="brands" 
                                        label="name" 
                                        v-model="brand"
                                        @input="setBrandSelected">
                            </v-select>
                            <input type="hidden" name="brand_id" v-model="product.brand_id">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="price">Precio</label>
                            <input class="form-control" name="price" type="number" min="0" step="any" v-model="product.price">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-check-inline my-4">
                            <input class="form-check-input" type="checkbox" name="is_price_generic" id="is_price_generic" value="1" 
                            v-model="is_price_generic">
                            <label class="form-check-label" for="is_price_generic">Activar Precio Base.</label>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="tab-pane fade" id="multimedia" role="tabpanel" aria-labelledby="multimedia-tab">
                <div class="dropzone" id="myDropzone"></div>
                <div v-if="product.id" class="mt-4">
                    <table id="datatable_images" class="table" width="100%">
                        <thead>
                            <tr>
                                <th scope="col">Foto</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!--  -->
            <div class="tab-pane fade" id="stocks" role="tabpanel" aria-labelledby="stocks-tab">
                <!--  -->
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="stock-depot">Stock Depósito</label>
                            <input type="number" class="form-control" :id="`stock-depot`" name="stock_depot" v-model="product.stock_depot">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="stock-local">Stock Local</label>
                            <input type="number" class="form-control" :id="`stock-local`" name="stock_local" v-model="product.stock_local">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="stock-truck">Stock Camioneta</label>
                            <input type="number" class="form-control" :id="`stock-truck`" name="stock_truck" v-model="product.stock_truck">
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
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
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Código</label>
                                    <!-- <input class="form-control" :id="`code-${index}`" type="text" :name="getCombinationInputName('codes', combination, index)" v-model="combination.code"> -->
                                    <input class="form-control" :id="`code-${index}`" type="text" v-model="combination.code">
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
                                        <input type="hidden" :name="getCombinationInputName('codes', size, index, index_size)" v-model="combination.code">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label :for="`size-${index}-${index_size}`">Talla <b>#{{ (index_size + 1)}}</b>  <button class="btn btn-sm btn-danger" type="button" @click="removeSize(index_size, index, size.id)"><i class="fas fa-trash-alt"></i></button></label>
                                            <v-select placeholder="Seleccionar"
                                                        :options="sizes" 
                                                        label="name" 
                                                        v-model="combinations[index].sizes[index_size].size_prop"
                                                        @input="setCombinationSizeSelected(combinations[index].sizes[index_size].size_prop, index, index_size)">
                                            </v-select>
                                            <input type="hidden" :name="getCombinationInputName('sizes', size, index, index_size)" v-model="combinations[index].sizes[index_size].size_id">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label :for="`price-${index}-${index_size}`">Precio</label>
                                            <input class="form-control" :id="`price-${index}-${index_size}`" type="number" min="0" step="any" :name="getCombinationInputName('prices', size, index, index_size)" v-model="combination.sizes[index_size].price">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label :for="`stock-depot-${index}-${index_size}`">Stock Depósito</label>
                                            <input class="form-control" 
                                                    :id="`stock-depot-${index}-${index_size}`" 
                                                    type="number" 
                                                    min="0" 
                                                    step="any" 
                                                    :name="getCombinationInputName('stocks_depot', size, index, index_size)" 
                                                    v-model="combination.sizes[index_size].stock_depot">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label :for="`stock-local-${index}-${index_size}`">Stock Local</label>
                                            <input class="form-control" 
                                                    :id="`stock-local-${index}-${index_size}`" 
                                                    type="number" 
                                                    min="0" 
                                                    step="any" 
                                                    :name="getCombinationInputName('stocks_local', size, index, index_size)" 
                                                    v-model="combination.sizes[index_size].stock_local">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label :for="`stock-truck-${index}-${index_size}`">Stock Camioneta</label>
                                            <input class="form-control" 
                                                    :id="`stock-truck-${index}-${index_size}`" 
                                                    type="number" 
                                                    min="0" 
                                                    step="any" 
                                                    :name="getCombinationInputName('stocks_truck', size, index, index_size)" 
                                                    v-model="combination.sizes[index_size].stock_truck">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="d-flex justify-content-end my-3">
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
                    code: '',
                    is_regular: 1,
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
            urlProducts: {
                type: String,
                default: ''
            },
            urlProductsCombinations: {
                type: String,
                default: ''
            }
        },
        data: () => ({
            brand: null,
            category: null,
            gender: null,
            is_price_generic: 0,
            is_regular: 1,
            combinations: [],
            loading: false,
            mounted: false
        }),
	    computed: {
	    },
        async mounted() {
            this.mounted = true;

            if (this.product.id) {
                if (!this.product.is_regular) {
                    this.is_regular = 0;
                }

                if (this.product.is_price_generic) {
                    this.is_price_generic = 1;
                }

                this.brand = this.product.brand.name;
                this.category = this.product.category.name;
                this.gender = this.product.gender;

                if (this.product.product_combinations) {
                    
                    // this.combinations.forEach(function(item, index) {
                    //     item.color_prop = item.color;
                        // item.size_prop = item.sizes.map(item => item.size);
                    //     item.size_values = item.sizes.map(item => item.size_id).toString();
                    // });
                    
                    for (var i=0; i<this.product.product_combinations.length; i++) {
                        const combination = this.product.product_combinations[i];
                        var index = this.getIndex(combination.color_id);

                        if (index < 0) {
                            var new_combination = {
                                code: combination.code,
                                color_id: combination.color_id,
                                color_prop: combination.color,
                                sizes: [
                                    {
                                        id: combination.id,
                                        product_id: combination.product_id,
                                        size_id: combination.size_id,
                                        size_prop: combination.size,
                                        price: combination.price,
                                        stock_depot: combination.stock_depot,
                                        stock_local: combination.stock_local,
                                        stock_truck: combination.stock_truck
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
                                stock_depot: combination.stock_depot,
                                stock_local: combination.stock_local,
                                stock_truck: combination.stock_truck
                            }

                            this.combinations[index].sizes.push(new_size);
                        }

                        /*
                            let new_combination = {
                                code: combination.code,
                                color_id: combination.color_id,
                                color_prop: combination.color,
                                id: combination.id,
                                product_id: combination.product_id,
                                size_id: combination.size_id,
                                size_prop: combination.size,
                                // size_prop: combination.sizes.map(item => item.size),
                                // size_values: combination.sizes.map(item => item.size_id).toString(),
                                price: combination.price,
                                stock_depot: combination.stock_depot,
                                stock_local: combination.stock_local,
                                stock_truck: combination.stock_truck
                            };

                            this.combinations.push(new_combination);
                        */
                    }
                }
            }
        },
        methods: {
            getIndex(color_id) {
                // var index = this.combinations.map(function(e) { return e.color_id; }).indexOf(color_id);
                var index = this.combinations.map(e => e.color_id).indexOf(color_id);
                return index;
            },

            setBrandSelected(value) {
                this.product.brand_id = value ? value.id : null;
            },
            setCategorySelected(value) {
                this.product.category_id = value ? value.id : null;
            },
            setGenderSelected(value) {
                this.product.gender = value;
            },
            setCombinationColorSelected(value, index) {
                this.combinations[index].color_id = value ? value.id : null;
            },
            // setCombinationSizeSelected(value, index) {
            setCombinationSizeSelected(value, index, index_size) {
                this.combinations[index].sizes[index_size].size_id = value ? value.id : null;
            },
            // setCombinationSizeSelected(value, index) {
            //     // this.combinations[index].size_prop = value;
            //     // this.combinations[index].size_values = value.map(item => item.id).toString();
            // },
            // setCombinationSizeSelected(combination, value) {
            //     combination.size_prop = value;
            //     combination.size_values = value.map(item => item.id).toString();
            // },
            addCombination() {
                let new_combination = {
                    code: null,
                    color_prop: null,
                    color_id: null,
                    sizes: [
                        {
                            id: null,
                            product_id: null,
                            color_id: null,
                            size_id: null,
                            size_prop: null,
                            code: null,
                            price: null,
                            stock_depot: null,
                            stock_local: null,
                            stock_truck: null,
                        }
                    ]
                };

                this.combinations.push(new_combination);
            },
            addSize(combination) {
                var combination_size = {
                            color_prop: null,
                            color_id: null,
                            id: null,
                            product_id: null,
                            size_id: null,
                            size_prop: null,
                            price: null,
                            stock_depot: null,
                            stock_local: null,
                            stock_truck: null,
                        };

                combination.sizes.push(combination_size);
            },
            getCombinationInputName(input, product_combination, index, index_size) {
                var input_name = '';

                if (product_combination.product_id) {
                    input_name = `${input}_existing[${index}][${product_combination.id}]`;
                } else {
                    input_name = `${input}[${index}][${index_size}]`;
                }

                return input_name;
            },
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
            hasCombinationExistingProduct(combination) {
                if (combination.sizes.filter(e => e.product_id > 0).length > 0) {
                    return true;
                }

                return false;
            },
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
            }
        }
    }
</script>

<style lang="scss">
    .select2-container .select2-selection--single {
        height: 37px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 37px;
    }
</style>