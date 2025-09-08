<template>
  <div
    v-if="isVisible"
    :class="{
      'toast': true,
      'border-danger': type === 'error',
      'border-warning': type === 'warning',
      'border-info': type === 'info',
      'border-success': type === 'success',
      'fade': true,
      'show': true,
    }"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
  >
    <div
      class="toast-header"
    >
      <i 
      class="fs-base me-2"
      :class="{
        'ci-bell': type === 'warning' || type === 'error',
        'ci-info-circle': type === 'info',
        'ci-check-circle': type === 'success',
      }"></i>
      <strong class="fw-semibold">{{ title }}</strong>
      <button
        type="button"
        class="btn-close ms-auto"
        @click="hideToast"
        aria-label="Close"
      ></button>
    </div>
    <div class="toast-body me-2">
      {{ message }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'ToastEcommerceComponent',
  props: {
    duration: {
      type: Number,
      default: 4000, // 4 segundos por defecto
    },
  },
  data() {
    return {
      isVisible: false,
      timeoutId: null,
      title: 'Notificación',
      message: 'Este es un mensaje de toast.',
      type: 'info',
    };
  },
  methods: {
    showToast( toast) {
      this.title = toast.title || this.title;
      this.message = toast.message || this.message;
      this.type = toast.type || 'info';

      this.isVisible = true;
      if (this.duration > 0) {
        this.timeoutId = setTimeout(() => {
          this.hideToast();
        }, this.duration);
      }
    },
    hideToast() {
      this.isVisible = false;
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
        this.timeoutId = null;
      }
    },
  },
  beforeUnmount() {
    // Limpiar el temporizador si el componente se destruye
    if (this.timeoutId) {
      clearTimeout(this.timeoutId);
    }
  },
};
</script>

<style scoped>
/* Puedes añadir estilos específicos si es necesario, aunque Bootstrap ya maneja mucho */
.toast {
  position: fixed; /* O 'absolute' dependiendo de dónde quieras que aparezca */
  top: 20px; /* Ajusta la posición vertical */
  right: 20px; /* Ajusta la posición horizontal */
  z-index: 2000 !important; /* Por encima del modal/backdrop personalizado */
}
</style>