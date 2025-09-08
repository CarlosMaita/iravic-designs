<template>
  <div id="shipping-modal" ref="shippingModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="shippingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="shippingModalLabel">Información de Envío</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitForm">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="shipping-name" class="form-label">Nombre completo</label>
                  <input 
                    v-model="shippingData.name" 
                    id="shipping-name" 
                    class="form-control" 
                    type="text" 
                    required 
                    placeholder="Ingrese su nombre completo"
                  >
                  <div v-if="errors.name" class="text-danger small mt-1">{{ errors.name }}</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="shipping-dni" class="form-label">Cédula</label>
                  <input 
                    v-model="shippingData.dni" 
                    id="shipping-dni" 
                    class="form-control" 
                    type="text" 
                    required 
                    placeholder="V-12345678"
                  >
                  <div v-if="errors.dni" class="text-danger small mt-1">{{ errors.dni }}</div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="shipping-phone" class="form-label">Teléfono</label>
                  <input 
                    v-model="shippingData.phone" 
                    id="shipping-phone" 
                    class="form-control" 
                    type="tel" 
                    required 
                    placeholder="0412-1234567"
                  >
                  <div v-if="errors.phone" class="text-danger small mt-1">{{ errors.phone }}</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="shipping-agency" class="form-label">Agencia de envío</label>
                  <select 
                    v-model="shippingData.agency" 
                    id="shipping-agency" 
                    class="form-select" 
                    required
                  >
                    <option value="">Seleccione una agencia</option>
                    <option value="MRW">MRW</option>
                    <option value="ZOOM">ZOOM</option>
                    <option value="Domesa">Domesa</option>
                  </select>
                  <div v-if="errors.agency" class="text-danger small mt-1">{{ errors.agency }}</div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <label for="shipping-address" class="form-label">Dirección de la agencia</label>
                  <textarea 
                    v-model="shippingData.address" 
                    id="shipping-address" 
                    class="form-control" 
                    rows="3" 
                    required 
                    placeholder="Ingrese la dirección completa de la agencia donde desea recibir el envío"
                  ></textarea>
                  <div v-if="errors.address" class="text-danger small mt-1">{{ errors.address }}</div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button @click="submitForm" :disabled="isLoading" class="btn btn-primary" type="button">
            <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            {{ isLoading ? 'Procesando...' : 'Confirmar Compra' }}
          </button>
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ShippingModalComponent',
  props: {
    cartItems: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      shippingData: {
        name: '',
        dni: '',
        phone: '',
        agency: 'MRW',
        address: ''
      },
      errors: {},
      isLoading: false
    }
  },
  methods: {
      showModal() {
        // Reset form and errors
        this.resetForm();
        // Asegura que el modal esté montado directamente en <body> para evitar stacking-context del offcanvas
        const el = this.$refs.shippingModal;
        if (el && el.parentNode !== document.body) {
          document.body.appendChild(el);
        }
        // Evita doble inicialización del modal Bootstrap
        if (!this._modalInstance) {
          this._modalInstance = new bootstrap.Modal(el);
        }
        this._modalInstance.show();
      },

    closeModal() {
      // Hide the modal using Bootstrap's modal API
      const modal = bootstrap.Modal.getInstance(this.$refs.shippingModal);
      if (modal) {
        modal.hide();
      }
    },

    resetForm() {
      this.shippingData = {
        name: '',
        dni: '',
        phone: '',
        agency: 'MRW',
        address: ''
      };
      this.errors = {};
      this.isLoading = false;
    },

    validateForm() {
      this.errors = {};

      if (!this.shippingData.name.trim()) {
        this.errors.name = 'El nombre es requerido';
      }

      if (!this.shippingData.dni.trim()) {
        this.errors.dni = 'La cédula es requerida';
      }

      if (!this.shippingData.phone.trim()) {
        this.errors.phone = 'El teléfono es requerido';
      }

      if (!this.shippingData.agency) {
        this.errors.agency = 'La agencia de envío es requerida';
      }

      if (!this.shippingData.address.trim()) {
        this.errors.address = 'La dirección es requerida';
      }

      return Object.keys(this.errors).length === 0;
    },

    async submitForm() {
      if (!this.validateForm()) {
        return;
      }

      this.isLoading = true;

      try {
        // Prepare order data
        const orderData = {
          items: this.cartItems.map(item => ({
            product_id: item.product_id || item.id,
            quantity: item.quantity,
            price: item.price,
            color_id: item.color_id || null,
            size_id: item.size_id || null
          })),
          shipping_data: { ...this.shippingData }
        };

        // Send order creation request
        const response = await fetch('/api/orders/create', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(orderData)
        });

        const result = await response.json();

        if (result.success) {
          // Emit success event to parent component
          this.$emit('order-created', result);
          this.closeModal();
        } else {
          if (result.redirect) {
            // Redirect to login if not authenticated
            window.location.href = result.redirect;
          } else {
            // Show error message
            this.$emit('order-error', result.message || 'Error al crear la orden');
          }
        }
      } catch (error) {
        console.error('Error creating order:', error);
        this.$emit('order-error', 'Error al procesar la orden. Intente nuevamente.');
      } finally {
        this.isLoading = false;
      }
    }
  }
}
</script>

<style scoped>
/* Fix z-index issues for modal interaction */
.modal {
  z-index: 1055 !important;
}

.modal-dialog {
  z-index: 1056 !important;
}

.modal-content {
  z-index: 1057 !important;
}

.modal-header,
.modal-body,
.modal-footer {
  z-index: 1058 !important;
  position: relative;
}

.form-label {
  font-weight: 600;
  color: #333;
  position: relative;
  z-index: 1059;
}

.form-control, .form-select {
  border-radius: 0.5rem;
  border: 1px solid #e1e5e9;
  position: relative;
  z-index: 1059 !important;
}

.form-control:focus, .form-select:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  z-index: 1060 !important;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 0.5rem;
  position: relative;
  z-index: 1059;
}

.btn-primary:hover {
  background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

.btn-secondary {
  position: relative;
  z-index: 1059;
}

.text-danger {
  font-size: 0.875rem;
  position: relative;
  z-index: 1059;
}

.modal-body {
  padding: 1.5rem;
}

#shipping-modal.modal {
  z-index: 1080 !important;
}

/* Ensure form elements are clickable */
input, select, textarea, button {
  pointer-events: auto !important;
}
</style>