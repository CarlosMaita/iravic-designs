<template>
    <div class="modal fade" :id="modalId" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ isEditing ? 'Editar Producto' : 'Crear Nuevo Producto' }}
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                
                <div class="modal-body">
                    <form @submit.prevent="submitForm" class="needs-validation" novalidate>
                        <!-- Nombre del producto -->
                        <div class="mb-3">
                            <label for="productName" class="form-label">
                                Nombre del Producto <span class="text-danger">*</span>
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="form-control"
                                id="productName"
                                :class="{ 'is-invalid': errors.name }"
                                @input="clearError('name')"
                                required
                            >
                            <div v-if="errors.name" class="invalid-feedback">
                                {{ errors.name[0] }}
                            </div>
                        </div>
                        
                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="productDescription" class="form-label">Descripción</label>
                            <textarea
                                v-model="form.description"
                                class="form-control"
                                id="productDescription"
                                rows="3"
                                :class="{ 'is-invalid': errors.description }"
                                @input="clearError('description')"
                            ></textarea>
                            <div v-if="errors.description" class="invalid-feedback">
                                {{ errors.description[0] }}
                            </div>
                        </div>
                        
                        <!-- Precio -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="productPrice" class="form-label">
                                        Precio <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input
                                            v-model="form.price"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            class="form-control"
                                            id="productPrice"
                                            :class="{ 'is-invalid': errors.price }"
                                            @input="clearError('price')"
                                            required
                                        >
                                        <div v-if="errors.price" class="invalid-feedback">
                                            {{ errors.price[0] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Categoría -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="productCategory" class="form-label">
                                        Categoría <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        v-model="form.category_id"
                                        class="form-select"
                                        id="productCategory"
                                        :class="{ 'is-invalid': errors.category_id }"
                                        @change="clearError('category_id')"
                                        required
                                    >
                                        <option value="">Seleccionar categoría...</option>
                                        <option
                                            v-for="category in categories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <div v-if="errors.category_id" class="invalid-feedback">
                                        {{ errors.category_id[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Checkboxes -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input
                                        v-model="form.is_featured"
                                        type="checkbox"
                                        class="form-check-input"
                                        id="isFeatured"
                                    >
                                    <label class="form-check-label" for="isFeatured">
                                        Producto Destacado
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input
                                        v-model="form.is_active"
                                        type="checkbox"
                                        class="form-check-input"
                                        id="isActive"
                                    >
                                    <label class="form-check-label" for="isActive">
                                        Producto Activo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                        :disabled="loading"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="submitForm"
                        :disabled="loading"
                    >
                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                        {{ loading ? 'Guardando...' : (isEditing ? 'Actualizar' : 'Crear') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ModalProductFormComponent',
    
    props: {
        modalId: {
            type: String,
            default: 'productModal'
        },
        product: {
            type: Object,
            default: () => null
        },
        categories: {
            type: Array,
            default: () => []
        },
        apiEndpoint: {
            type: String,
            required: true
        }
    },
    
    data() {
        return {
            form: {
                name: '',
                description: '',
                price: 0,
                category_id: '',
                is_featured: false,
                is_active: true
            },
            errors: {},
            loading: false
        };
    },
    
    computed: {
        isEditing() {
            return this.product && this.product.id;
        }
    },
    
    watch: {
        product: {
            handler(newProduct) {
                this.resetForm();
                if (newProduct) {
                    this.form = { ...this.form, ...newProduct };
                }
            },
            immediate: true
        }
    },
    
    methods: {
        async submitForm() {
            if (this.loading) return;
            
            this.loading = true;
            this.errors = {};
            
            try {
                const method = this.isEditing ? 'put' : 'post';
                const url = this.isEditing 
                    ? `${this.apiEndpoint}/${this.product.id}`
                    : this.apiEndpoint;
                
                const response = await this.$axios[method](url, this.form);
                
                this.$emit('product-saved', response.data);
                this.hideModal();
                this.showSuccessMessage(
                    this.isEditing 
                        ? 'Producto actualizado correctamente'
                        : 'Producto creado correctamente'
                );
                
            } catch (error) {
                this.handleSubmitError(error);
            } finally {
                this.loading = false;
            }
        },
        
        handleSubmitError(error) {
            if (error.response) {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    this.showErrorMessage('Error al procesar la solicitud');
                }
            } else {
                this.showErrorMessage('Error de conexión');
            }
        },
        
        clearError(field) {
            if (this.errors[field]) {
                this.$delete(this.errors, field);
            }
        },
        
        resetForm() {
            this.form = {
                name: '',
                description: '',
                price: 0,
                category_id: '',
                is_featured: false,
                is_active: true
            };
            this.errors = {};
        },
        
        hideModal() {
            const modal = document.getElementById(this.modalId);
            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }
            }
        },
        
        showSuccessMessage(message) {
            // Implementar sistema de notificaciones
            // Por ahora usamos alert, pero debería ser reemplazado por toast
            alert(message);
        },
        
        showErrorMessage(message) {
            // Implementar sistema de notificaciones
            // Por ahora usamos alert, pero debería ser reemplazado por toast
            alert(message);
        }
    },
    
    mounted() {
        // Limpiar formulario cuando se cierre el modal
        const modal = document.getElementById(this.modalId);
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => {
                this.resetForm();
            });
        }
    }
};
</script>

<style scoped>
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

.text-danger {
    color: #dc3545 !important;
}

.form-check {
    padding-left: 1.5em;
}

.input-group .invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
}
</style>