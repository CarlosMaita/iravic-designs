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
            <div class="mb-3">
              <label class="form-label">Moneda</label>
              <select class="form-select" v-model="form.currency" required>
                <option value="USD">USD</option>
                <option value="VES">Bs (VES)</option>
              </select>
              <small class="text-muted">Tasa actual: {{ exchangeRateFormatted }} Bs/$</small>
            </div>

            <div class="mb-3">
              <label class="form-label">{{ amountLabel }}</label>
              <input type="number" class="form-control" v-model.number="form.amount" min="0.01" step="0.01" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Método de Pago</label>
              <select class="form-select" v-model="form.payment_method" required>
                <option disabled value="">Seleccione un método</option>
                <option value="pago_movil">Pago Móvil</option>
                <option value="transferencia">Transferencia</option>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
              </select>
            </div>

            <div class="mb-3" v-show="needsReference">
              <label class="form-label">Número de Referencia</label>
              <input type="text" class="form-control" v-model="form.reference_number" :required="needsReference">
            </div>

            <div class="mb-3">
              <label class="form-label">Fecha del Pago</label>
              <input type="datetime-local" class="form-control" v-model="form.date" required>
            </div>

            <div class="mb-3" v-show="needsMobileDate">
              <label class="form-label">Fecha (Pago Móvil)</label>
              <input type="datetime-local" class="form-control" v-model="form.mobile_payment_date" :required="needsMobileDate">
            </div>

            <div class="mb-3">
              <label class="form-label">Comentarios (Opcional)</label>
              <textarea class="form-control" rows="3" v-model="form.comment"></textarea>
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
      return this.form.currency === 'VES' ? 'Monto a Pagar (Bs)' : 'Monto a Pagar (USD)';
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
    }
  },
  methods: {
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
</style>
