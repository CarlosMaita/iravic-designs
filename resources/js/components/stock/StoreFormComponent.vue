<template>
    <form :action="action" :method="method"  id="form-store" @submit.prevent="onSubmit" >
        <slot></slot>
        <!-- fields -->
        <div class="row">
            <div class="col-sm-6 form-group">
                <label for="name">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" autocomplete="off" v-model="store.name" >
            </div>
            <div class="col-sm-6 form-group">
                <label for="store_type">Tipo de Deposito</label>
                <v-select 
                    placeholder="Seleccionar"
                    ref="store_type" 
                    v-model="storeType"
                    :options="storeTypes"
                    label="name" 
                    @input="setStoreTypeSelected"
                    >
                </v-select>
                <input type="hidden" name="store_type_id" v-model="storeTypeId">
            </div>
        </div>
        <a :href="backToListUrl" class="btn btn-primary">Ir al listado</a>
        <button class="btn btn-success"  type="submit" >{{ store.id ? 'Guardar' : 'Crear' }}</button>
    </form>
</template>

<script>
    export default {
        props: {
            method: { type: String, default: 'POST' , require : true },
            action: { type: String, default: '', require : true },
            backToListUrl : { type: String, default: '' , require : true },
            storeTypes: { type: Array, default: [] , require : true },
            storeProp: { type: Object, default: null  },
        },
        data: () => ({
            store: {
                id: null,
                name: '',
                store_type_id: null
            },
            storeType : null,
            storeTypeId: null
        }),
        mounted() {
            if (this.storeProp != null) {
                this.store = {
                    id: this.storeProp.id,
                    name: this.storeProp.name,
                    storeTypeId: this.storeProp.store_type_id
                };
                this.storeType = this.storeTypes.find(storeType => storeType.id === this.store.storeTypeId);
                this.storeTypeId = this.storeProp.store_type_id
            }
        },
        methods: {
            setStoreTypeSelected(storeType) {
                this.storeTypeId = storeType.id;
            },
            onSubmit(event) {
                event.preventDefault();
                const form = $('#form-store')[0];
                const formData = new FormData(form);

                axios({
                    method: this.method,
                    url: this.action,
                    data: formData,
                })
                .then(response => {
                    if (response.data.success) {
                        window.location.href = response.data.redirect;
                    } else if (response.data.error) {
                        new Noty({
                            text: response.data.error,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: "Error al realizar la operación",
                            type: 'error'
                        }).show();
                    }
                })
                .catch(error => {
                    if (error.response && error.response.data.errors) {
                        Object.values(error.response.data.errors).forEach(element => {
                            if (Array.isArray(element)) {
                                new Noty({
                                    text: element[0],
                                    type: 'error'
                                }).show();
                            }
                        });
                    } else if (error.response && error.response.data.error) {
                        new Noty({
                            text: error.response.data.error,
                            type: 'error'
                        }).show();
                    } else if (error.response && error.response.data.message) {
                        new Noty({
                            text: error.response.data.message,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: "Error al realizar la operación",
                            type: 'error'
                        }).show();
                    }
                });
            }
            
            
        }, 
    }
</script>

<style scoped>
   
</style>