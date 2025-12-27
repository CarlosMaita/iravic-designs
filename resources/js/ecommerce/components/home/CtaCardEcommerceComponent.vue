<template>
  <div class="cta-card bg-white rounded-3 border h-100 text-center p-4 hover-lift">
    <a :href="cta.cta_url" class="text-decoration-none">
      <div class="d-flex flex-column align-items-center h-100">
        <!-- Icon -->
        <div class="icon-wrapper mb-3">
          <i :class="cta.icon" class="cta-icon text-primary" style="font-size: 3rem;"></i>
        </div>
        
        <!-- Title -->
        <h5 class="mb-2 text-dark fw-semibold">{{ cta.title }}</h5>
        
        <!-- Description -->
        <p v-if="cta.description" class="text-body-secondary mb-3 flex-grow-1 fs-sm">
          {{ cta.description }}
        </p>
        
        <!-- CTA Button -->
        <button class="btn btn-outline-primary btn-sm mt-auto">
          {{ cta.cta_text }}
        </button>
      </div>
    </a>
  </div>
</template>

<script>
export default {
  name: 'CtaCardEcommerceComponent',
  props: {
    cta: {
      type: Object,
      required: true
    }
  }
};
</script>

<style scoped>
.cta-card {
  transition: all 0.3s ease;
  min-height: 280px;
}

.hover-lift:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.cta-card a {
  display: block;
  height: 100%;
}

.cta-icon {
  transition: transform 0.3s ease;
}

.cta-card:hover .cta-icon {
  transform: scale(1.1);
}

.icon-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: rgba(var(--bs-primary-rgb), 0.1);
}
</style>
