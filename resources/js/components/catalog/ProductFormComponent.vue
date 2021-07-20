<template>
    <div class="mb-3">
        <!--  -->
        <div>
            <div class="row">
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
                        <select class="form-control" id="gender" name="gender" v-model="product.gender">
                            <option :value="null" selected disabled>Seleccionar</option>
                            <option v-for="(gender,index) in genders" :value="gender" :key="`gender-${index}`">{{ gender }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="category_id">Categoría</label>
                        <select class="form-control" id="category" name="category_id" v-model="product.category_id">
                            <option :value="null" selected disabled>Seleccionar</option>
                            <option v-for="(category,index) in categories" :value="category.id" :key="`category-${index}`">{{ category.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="brand_id">Marca</label>
                        <select class="form-control" id="brand" name="brand_id" v-model="product.brand_id">
                            <option :value="null" selected disabled>Seleccionar</option>
                            <option v-for="(brand,index) in brands" :value="brand.id" :key="`brand-${index}`">{{ brand.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <br>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li v-if="is_regular" class="nav-item">
                <a :class="`nav-link ${is_regular ? 'active' : ''}`" id="stocks-tab" data-toggle="tab" href="#stocks" role="tab" aria-controls="stocks" aria-selected="true">Atributos y Stocks</a>
            </li>
            <li v-if="!is_regular" class="nav-item">
                <a :class="`nav-link ${!is_regular ? 'active' : ''}`" id="combinations-tab" data-toggle="tab" href="#combinations" role="tab" aria-controls="combinations" aria-selected="false">Combinaciones y Stocks</a>
            </li>
        </ul>
        <!--  -->
        <div class="tab-content" id="myTabContent">
            <!--  -->
            <div :class="`tab-pane fade ${is_regular ? 'show active' : ''}`" id="stocks" role="tabpanel" aria-labelledby="stocks-tab">
                <!--  -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="color">Color</label>
                            <select class="form-control" id="color" name="color_id" v-model="product.color_id">
                                <option :value="null" selected disabled>Seleccionar</option>
                                <option v-for="(color,index) in colors" :value="color.id" :key="`color-${index}`">{{ color.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label :for="`size`">Talla</label>
                            <select class="form-control" :id="`size`" name="size_id" v-model="product.size_id">
                                <option :value="null" selected disabled>Seleccionar</option>
                                <option v-for="(size,index) in sizes" :value="size.id" :key="`color-${index}`">{{ size.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label :for="`stock-depot`">Stock Depósito</label>
                            <input type="number" class="form-control" :id="`stock-depot`" name="stock_depot" v-model="product.stock_depot">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label :for="`stock-local`">Stock Local</label>
                            <input type="number" class="form-control" :id="`stock-local`" name="stock_local" v-model="product.stock_local">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label :for="`stock-truck`">Stock Camioneta</label>
                            <input type="number" class="form-control" :id="`stock-truck`" name="stock_truck" v-model="product.stock_truck">
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div :class="`tab-pane fade ${!is_regular ? 'show active' : ''}`" id="combinations" role="tabpanel" aria-labelledby="combinations-tab">
                <div class="d-flex justify-content-end my-3">
                    <button class="btn btn-light" type="button" @click="addCombination"><i class="fas fa-plus"></i> Agregar Combinación</button>
                </div>
                <!--  -->
                <div v-for="(combination, index) in combinations" :key="`combination-${index}`">
                    <!--  -->
                    <div>
                        <input type="hidden" name="combinations[]" :value="index">
                        <input v-if="combination.product_id" type="hidden" name="product_combinations[]" :value="combination.id">
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p>Combinación #{{ (index + 1) }} <button class="btn btn-danger" type="button" @click="removeCombination(index)"><i class="fas fa-trash-alt"></i></button></p> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label :for="`color-${index}`">Color</label>
                                <select class="form-control" :id="`color-${index}`" :name="`colors[${index}]`" v-model="combination.color_id">
                                    <option :value="null" selected disabled>Seleccionar</option>
                                    <option v-for="(color,j) in colors" :value="color.id" :key="`color-${index}-${j}`">{{ color.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label :for="`size-${index}`">Talla</label>
                                <select class="form-control" :id="`size-${index}`" :name="`sizes[${index}]`" v-model="combination.size_id">
                                    <option :value="null" selected disabled>Seleccionar</option>
                                    <option v-for="(size,j) in sizes" :value="size.id" :key="`color-${index}-${j}`">{{ size.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label :for="`stock-depot-${index}`">Stock Depósito</label>
                                <input type="number" class="form-control" :id="`stock-depot-${index}`" :name="`stocks_depot[${index}]`" v-model="combination.stock_depot">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label :for="`stock-local-${index}`">Stock Local</label>
                                <input type="number" class="form-control" :id="`stock-local-${index}`" :name="`stocks_local[${index}]`" v-model="combination.stock_local">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label :for="`stock-truck-${index}`">Stock Camioneta</label>
                                <input type="number" class="form-control" :id="`stock-truck-${index}`" :name="`stocks_truck[${index}]`" v-model="combination.stock_truck">
                            </div>
                        </div>
                    </div>
                    <hr>
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
            }
        },
        data: () => ({
            brand: null,
            category: null,
            code: '',
            color: null,
            gender: null,
            is_regular: 1,
            name: '',
            size: null,
            stock_depot: null,
            stock_local: null,
            stock_truck: null,
            combinations: [],
            loading: false,
            mounted: false,
        }),
	    computed: {
	    },
        async mounted() {
            this.mounted = true;

            console.log(this.product)

            if (this.product.id) {
                this.combinations = this.product.product_combinations;

                if (!this.product.is_regular) {
                    this.is_regular = 0;
                }
            }
        },
        methods: {
            addCombination() {
                let new_combination = {
                    color_id: null,
                    size_id: null,
                    stock_depot: null,
                    stock_local: null,
                    stock_truck: null
                };


                // if (!this.product.id) {
                //     console.log('no hay poducto');

                //     this.product.brand_id = null;
                //     this.product.category_id = null;
                //     this.product.code = '';
                //     this.product.gender = '';
                //     this.product.is_regular = 1;
                //     this.product.name = '';
                //     this.product.product_combinations = []; 
                // }

                // if (!this.product.id) {
                //     this.product.product_combinations = [];    
                // }

                
                // this.product.product_combinations.push(new_combination);

                this.combinations.push(new_combination);
            },
            removeCombination(index) {
                if (index < 0)
                    return;
                
                this.combinations.splice(index, 1);
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