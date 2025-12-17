<x-mail::message>
# 🎉 ¡Bienvenido a Iravic Designs, {{ $customer->name }}! 🎉

¡Estamos muy contentos de tenerte con nosotros! 😊 Gracias por registrarte en nuestra tienda.

## ✨ ¿Qué encontrarás en Iravic Designs?

- 👗 **Productos de alta calidad** con diseños únicos y exclusivos
- 🎨 **Estilos únicos** que destacan tu personalidad
- 💎 **Atención personalizada** para una experiencia de compra inolvidable
- 🚚 **Envíos seguros** a todo el país

<x-mail::button :url="route('ecommerce.home')">
🛍️ Explorar Catálogo
</x-mail::button>

## 💡 Consejos para comenzar:

- 📱 Explora nuestras categorías y encuentra tu estilo favorito
- ⭐ Guarda tus productos favoritos para comprarlos después
- 🔔 Activa las notificaciones para estar al tanto de ofertas especiales

---

Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos. ¡Estamos aquí para ayudarte! 💬

¡Felices compras! 🎊<br>
**El equipo de {{ config('app.name') }}**
</x-mail::message>
