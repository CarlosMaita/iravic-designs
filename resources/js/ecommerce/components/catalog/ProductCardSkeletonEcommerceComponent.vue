<template>
  <div class="skeleton-product-card card mb-4">
    <div class="skeleton-image card-img-top"></div>
    <div class="card-body">
      <div class="skeleton-title mb-2"></div>
      <div class="skeleton-price mb-2"></div>
      <div class="skeleton-button"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductCardSkeletonEcommerceComponent'
}
</script>

<style scoped>
.skeleton-product-card {
  background: #fff;
  border: 1px solid #eee;
  border-radius: 8px;
  overflow: hidden;
  min-height: 350px;
  box-shadow: 0 2px 8px 0 rgba(0,0,0,0.03);
}
.skeleton-image {
  height: 180px;
  background: #d1d5db;
  animation: pulse 1.5s infinite;
}
.skeleton-title,
.skeleton-price,
.skeleton-button {
  background: #d1d5db;
  border-radius: 4px;
  animation: pulse 1.5s infinite;
}
.skeleton-title { height: 24px; width: 70%; margin-bottom: 12px; }
.skeleton-price { height: 18px; width: 40%; margin-bottom: 12px; }
.skeleton-button { height: 36px; width: 60%; }
@keyframes pulse {
  0% { opacity: 1; }
  50% { opacity: 0.5; }
  100% { opacity: 1; }
}
</style>
