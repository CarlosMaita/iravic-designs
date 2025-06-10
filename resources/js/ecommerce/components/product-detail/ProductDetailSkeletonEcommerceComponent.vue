<template>
  <div class="skeleton-product-detail">
    <div class="skeleton-image"></div>
    <div class="skeleton-title"></div>
    <div class="skeleton-price"></div>
    <div class="skeleton-description"></div>
    <div class="skeleton-button"></div>
  </div>
</template>

<script>
export default {
  name: 'ProductDetailSkeletonEcommerceComponent'
}
</script>

<style scoped>
.skeleton-product-detail {
  display: flex;
  flex-direction: column;
  gap: 16px;
  max-width: 400px;
  margin: 0 auto;
}
.skeleton-image,
.skeleton-title,
.skeleton-price,
.skeleton-description,
.skeleton-button {
  background: #d1d5db; /* gris más oscuro para mejor contraste */
  border-radius: 4px;
  animation: pulse 1.5s infinite;
}
.skeleton-image { height: 200px; }
.skeleton-title { height: 32px; width: 70%; }
.skeleton-price { height: 24px; width: 30%; }
.skeleton-description { height: 80px; width: 100%; }
.skeleton-button { height: 40px; width: 40%; }
@keyframes pulse {
  0% { opacity: 1; }
  50% { opacity: 0.5; }
  100% { opacity: 1; }
}
</style>
