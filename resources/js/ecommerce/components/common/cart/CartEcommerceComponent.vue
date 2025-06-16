<template>
  <div>
    <!-- Header -->
    <div class="offcanvas-header flex-column align-items-start py-3 pt-lg-4">
      <div class="d-flex align-items-center justify-content-between w-100 mb-3 mb-lg-4">
        <h4 class="offcanvas-title" id="shoppingCartLabel">Carrito de compras</h4>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
      </div>
    </div>

    <!-- Shipping Info -->
    <div v-if="cart.items.length > 0" class="shipping-info-container text-center my-3 d-flex align-items-center justify-content-center">
      <i class="ci-shipping fs-2 text-primary me-2"></i>
      <img src="/img/mrw-logo.jpg" alt="MRW Logo" class="mrw-logo mx-2">
      <p class="mb-0 ms-2">¡Envíos gratis por MRW en compras superiores a $15!</p>
    </div>
    <!-- End Shipping Info -->

    <!-- Completion Bar -->
    <div v-if="cart.items.length > 0" class="progress-container my-3 px-3">
      <div class="progress">
        <div 
          class="progress-bar" 
          role="progressbar" 
          :style="{ width: `${completionPercentage}%` }" 
          :class="{ 'bg-success': total_cart >= 15, 'bg-warning': total_cart < 15 }"
          :aria-valuenow="completionPercentage" 
          aria-valuemin="0" 
          aria-valuemax="100">
          {{ completionMessage }}
        </div>
      </div>
    </div>
    <!-- End Completion Bar -->

    <!-- Items -->
    <div  v-if="cart.items.length > 0" class="offcanvas-body overflow-y-auto" style="max-height: calc(100vh - 15rem)">
      <div class="d-flex flex-column gap-4 pt-2">
        <!-- Items cart -->
        <item-cart-ecommerce-component v-for="item in cart.items" 
          :key="item.id" 
          :item="item" 
          @remove-item="removeItem"
          @update-quantity="updateQuantity"  
        ></item-cart-ecommerce-component> 
        <!-- End Items cart -->
      </div>
    </div>
    <!-- Empty cart -->
    <div v-else class="offcanvas-body flex-column gap-4 pt-2 text-center">
      <i class="ci-shopping-cart fs-1 text-dark mb-2"></i>
      <h4 class="text-light-emphasis fw-bold">Tu carrito está vacío</h4>
      <p>Agrega productos a tu carrito para seguir con la compra</p>
    </div>

    <!-- Footer -->
    <div class="offcanvas-header flex-column align-items-start">
      <div class="d-flex align-items-center justify-content-between w-100 mb-3 mb-md-4">
        <span class="text-light-emphasis">Subtotal:</span>
        <span class="h6 mb-0">$ {{ total_cart }}</span>
      </div>
      <div class="d-flex w-100 gap-3">
          <!-- <a class="btn btn-lg btn-secondary w-100" href="#!">Ver carrito</a>  -->
        <button @click="goCheckout()" type="button" class="btn btn-lg btn-dark w-100" :disabled="cart.items.length === 0">Finalizar compra</button>
      </div>
    </div>
    <!-- End Footer -->

  </div> 
</template>
<script>
    export default {
        name: 'CartEcommerceComponent',
        props: {
        },
        data() {
            return {
                cart: {
                    items: [],
                },
            }
        },
        methods: {
          getCartLocalStorage() {
            // Logic to get cart items from local storage
            let cart = JSON.parse(localStorage.getItem('cart'));
            if (!cart) {
              cart = {
                items: [],
              };
              localStorage.setItem('cart', JSON.stringify(cart));
            }
            return cart;
          },  
          setCartLocalStorage(cart) {
            // Logic to set cart items in local storage
            localStorage.setItem('cart', JSON.stringify(cart));
             // Update the cart count in the header component
            this.$root.$refs.iconHeaderCartEcommerceComponent.setCartCount(this.cart_count);
          },
          addItem(item) {
            const index = this.cart.items.findIndex(i => i.id === item.id);
            if (index !== -1) {
              // Si el item ya existe, se actualiza la cantidad
              this.cart.items[index].quantity = item.quantity;
            } else {
              // Si el item no existe, se agrega al carrito
              this.cart.items.push(item);
            }
            this.setCartLocalStorage(this.cart);
          
          },
          removeItem(item) {
            // Logic to remove item from cart
            this.cart.items = this.cart.items.filter(i => !(i.id === item.id));
            this.setCartLocalStorage(this.cart);
          }, 
          updateQuantity(item) {
            // Logic to update item quantity in cart
            const index = this.cart.items.findIndex(i => i.id === item.id );
            if (index !== -1) {
              this.cart.items[index].quantity = item.quantity;
              this.setCartLocalStorage(this.cart);
            }
          },
          goCheckout() {
              this.sendCartWhatsapp()
              this.cart.items = []; //clean cart
              this.setCartLocalStorage(this.cart); 
          },

          sendCartWhatsapp() {
            // Logic to send cart to WhatsApp
            const phoneNumber = '+584144519511'; // Replace with your phone number
            const productsList = this.cart.items.map((item, index) => {
              const color = item.color ? ` - Color: ${item.color}` : '';
              const size = item.size ? ` - Talla: ${item.size}` : '';
              return `${index + 1}. ${item.name} ${color} ${size} - $${item.price} x ${item.quantity}`;
            }).join('\n');
            const urlsList = this.cart.items.map((item, index) => `${index + 1}. ${item.url}`).join('\n');
            const message = `Hola, me gustaría comprar estos productos:\n\n${productsList}\n\nTotal: $${this.total_cart}\n\n${urlsList}`;
            const url = `https://api.whatsapp.com/send?phone=${phoneNumber}&text=${encodeURIComponent(message)}`;
            
            window.open(url, '_blank');
          },
          
        },
        computed: {
            total_cart() {
                return parseFloat(this.cart.items.reduce((total, item) => total + (item.price * item.quantity), 0).toFixed(2));
            },
            cart_count() {
                let count = this.cart.items.reduce((count, item) => count + item.quantity, 0);
                count = count > 99 ? '99+' : count;
                // Limit the count to 99+
                return  count;
            },
            completionPercentage() {
              return Math.min((this.total_cart / 15) * 100, 100);
            },
            completionMessage() {
              return this.total_cart >= 15 
                ? '¡Envío gratuito alcanzado!'
                : `Añade $${(15 - this.total_cart).toFixed(2)} más para envío gratuito`;
            }
        },
        mounted() {
            // Logic to fetch cart items and total price
            this.cart = this.getCartLocalStorage();
             // Update the cart count in the header component
            this.$root.$refs.iconHeaderCartEcommerceComponent.setCartCount(this.cart_count);
           
        }
    }

</script>

<style>
.progress-container {
  width: 100%;
}
.progress {
  height: 20px;
  background-color: #e9ecef;
  border-radius: 10px;
  overflow: hidden;
}
.progress-bar {
  height: 100%;
  text-align: center;
  line-height: 20px;
  color: #fff;
}
.shipping-info-container {
  /* Estilos adicionales si son necesarios */
}

.mrw-logo {
  width: 30px; /* Ajusta el tamaño según sea necesario */
  height: auto; /* Mantiene la proporción de la imagen */
}
</style>

