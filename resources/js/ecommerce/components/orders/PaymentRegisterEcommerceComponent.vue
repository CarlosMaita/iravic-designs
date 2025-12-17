<template>
  <div>
    <p class="mb-3">Monto pendiente: <strong>${{ remaining.toFixed(2) }}</strong></p>
    <button class="btn btn-primary w-100" @click="open">Registrar Pago</button>

    <div class="modal fade" tabindex="-1" ref="modal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Registrar Pago</h5>
            <button type="button" class="btn-close" @click="close" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- USD Amount Display (read-only) -->
            <div class="alert alert-info mb-3">
              <h6 class="mb-2"><i class="fas fa-dollar-sign"></i> Monto a Pagar</h6>
              <p class="mb-1 h5">${{ remaining.toFixed(2) }} USD</p>
              <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Este es el monto pendiente en dólares americanos. Si realiza el pago en bolívares, 
                debe utilizar la tasa de cambio oficial del Banco Central de Venezuela (BCV).
              </small>
            </div>

            <!-- Exchange Rate Info (for VES payments) -->
            <div class="alert alert-warning mb-3">
              <h6 class="mb-2"><i class="fas fa-exchange-alt"></i> Tasa de Cambio Referencial</h6>
              <p class="mb-1"><strong>{{ exchangeRateFormatted }} Bs/$</strong></p>
              <p class="mb-1"><small>Equivalente aproximado: <strong>Bs. {{ (remaining * exchangeRate).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</strong></small></p>
              <small class="text-muted">
                <i class="fas fa-info-circle"></i>
                Esta es una tasa referencial. Para pagos en bolívares, use la tasa oficial del BCV del día del pago.
              </small>
            </div>

            <div class="mb-3">
              <label class="form-label">Método de Pago <span class="text-danger">*</span></label>
              <select class="form-select" v-model="form.payment_method" required @change="onPaymentMethodChange">
                <option disabled value="">Seleccione un método</option>
                <option v-for="method in paymentMethods" :key="method.code" :value="method.code">
                  {{ method.name }}
                </option>
              </select>
            </div>

            <!-- Display payment method instructions -->
            <div v-if="selectedMethodInstructions" class="alert alert-light mb-3">
              <h6 class="mb-2"><i class="fas fa-info-circle"></i> Instrucciones de Pago</h6>
              <p class="mb-0" style="white-space: pre-line;">{{ selectedMethodInstructions }}</p>
            </div>

            <div class="mb-3" v-show="needsReference">
              <label class="form-label">Número de Referencia <span v-if="needsReference" class="text-danger">*</span></label>
              <input type="text" class="form-control" v-model="form.reference_number" :required="needsReference" placeholder="Ej: 1234567890">
            </div>

            <div class="mb-3">
              <label class="form-label">Fecha del Pago <span class="text-danger">*</span></label>
              <input type="datetime-local" class="form-control" v-model="form.date" required>
            </div>

            <div class="mb-3" v-show="needsMobileDate">
              <label class="form-label">Fecha del Pago Móvil <span v-if="needsMobileDate" class="text-danger">*</span></label>
              <input type="datetime-local" class="form-control" v-model="form.mobile_payment_date" :required="needsMobileDate">
              <small class="form-text text-muted">Fecha que aparece en el comprobante del pago móvil</small>
            </div>

            <div class="mb-3">
              <label class="form-label">Comentarios (Opcional)</label>
              <textarea class="form-control" rows="3" v-model="form.comment" placeholder="Detalles adicionales sobre el pago"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="close">Cancelar</button>
            <button type="button" class="btn btn-primary" :disabled="submitting" @click="submit">
              <span v-if="!submitting">Registrar Pago</span>
              <span v-else class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PaymentRegisterEcommerceComponent',
  props: {
    orderId: { type: Number, required: true },
    remaining: { type: Number, required: true },
    exchangeRate: { type: Number, required: true },
    endpoint: { type: String, required: false, default: '' }
  },
  data() {
    return {
      submitting: false,
      paymentMethods: [],
      form: {
        currency: 'USD',
        amount: '',
        payment_method: '',
        reference_number: '',
        date: new Date().toISOString().slice(0, 16),
        mobile_payment_date: '',
        comment: ''
      }
    };
  },
  computed: {
    amountLabel() {
      return 'Monto a Pagar (USD)';
    },
    needsReference() {
      return this.form.payment_method === 'pago_movil' || this.form.payment_method === 'transferencia';
    },
    needsMobileDate() {
      return this.form.payment_method === 'pago_movil';
    },
    exchangeRateFormatted() {
      try {
        return this.exchangeRate.toLocaleString('es-VE', { minimumFractionDigits: 4, maximumFractionDigits: 4 });
      } catch (e) { return this.exchangeRate; }
    },
    selectedMethodInstructions() {
      const method = this.paymentMethods.find(m => m.code === this.form.payment_method);
      return method ? method.instructions : '';
    }
  },
  mounted() {
    this.fetchPaymentMethods();
  },
  methods: {
    async fetchPaymentMethods() {
      try {
        const response = await fetch('/api/payment-methods/active');
        const data = await response.json();
        this.paymentMethods = data;
      } catch (error) {
        console.error('Error fetching payment methods:', error);
        this.toast('error', 'No se pudieron cargar los métodos de pago. Por favor, recargue la página.');
      }
    },
    onPaymentMethodChange() {
      // Reset fields when payment method changes
      this.form.reference_number = '';
      this.form.mobile_payment_date = '';
    },
    open() {
      this.$nextTick(() => {
        this.ensureModal();
      });
    },
    ensureModal() {
      const el = this.$refs.modal;
      if (!el || !window.bootstrap) return;
      const inst = window.bootstrap.Modal.getInstance(el) || new window.bootstrap.Modal(el);
      inst.show();
    },
    close() {
      const el = this.$refs.modal;
      if (!el || !window.bootstrap) return;
      const inst = window.bootstrap.Modal.getInstance(el);
      if (inst) inst.hide();
    },
    submit() {
      if (this.submitting) return;
      
      // Set amount to remaining balance
      this.form.amount = this.remaining;
      
      this.submitting = true;
      const payload = { ...this.form };
      fetch(this.resolveEndpoint(), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(res => {
        if (res && res.success) {
          this.toast('success', res.message || 'Pago registrado exitosamente');
          this.close();
          setTimeout(() => window.location.reload(), 600);
        } else {
          this.toast('error', (res && res.message) || 'No se pudo registrar el pago');
        }
      })
      .catch(() => this.toast('error', 'Error al procesar el pago'))
      .finally(() => { this.submitting = false; });
    },
    resolveEndpoint() {
      return this.endpoint || (this.$options.endpoint || (window.paymentRegisterEndpoint || ''));
    },
    toast(type, message) {
      try {
        const root = document.getElementById('app');
        if (root && root.__vue__ && root.__vue__.$refs && root.__vue__.$refs.toastEcommerceComponent) {
          root.__vue__.$refs.toastEcommerceComponent.showToast({ type, message, title: type==='success'?'Listo':'Aviso' });
          return;
        }
      } catch (e) {}
      try {
        window.dispatchEvent(new CustomEvent('app:toast', { detail: { title: type==='success'?'Listo':'Aviso', message, type } }));
      } catch (e2) {
        alert(message);
      }
    }
  }
}
</script>

<style scoped>
.alert-info {
  background-color: #e7f3ff;
  border-color: #b3d9ff;
}
.alert-warning {
  background-color: #fff4e5;
  border-color: #ffd699;
}
</style>
