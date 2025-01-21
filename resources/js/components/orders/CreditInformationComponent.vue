<template>
    <div class="row">
        <!-- title  -->
        <div class="col-md-12">
            <hr>
            <h4 class="fs-title">Información de pago del crédito</h4>
        </div>
       <!-- collection frequency -->
        <div class="col-md-4">
            <label  for="amount-quotas">Cantidad de cuotas</label>
            <input @change="calculateQuotas" v-model="amountQuotas" type="number" step="1" min="1" name="amount-quotas" id="amount-quotas" class="form-control" placeholder="Ingrese la cantidad de cuotas">
        </div>
        <!-- start date -->
        <div class="col-md-4">
            <label for="start-quotas">Fecha de inicio de Pago </label>
            <input v-model="startQuotas" type="date" name="start-quotas" id="start-quotas" class="form-control" placeholder="Ingrese la Fecha de inicio de Pago">
        </div>
        <!-- Quotas -->
        <div class="col-md-12">
            <p class="font-weight-bold text-dark mt-1">Monto por cuota: <span class="quotas">$ {{ replaceNumberWithCommas(Quotas) }}</span></p>
            <input type="hidden" name="quotas" v-model="Quotas">
        </div>

    </div>
</template>

<script>

export default {
  components: {},
  props: {
    CollectionFrequency: {
      type: String,
      default: "",
    }
  },

  data: () => ({
    amountQuotas: 0,
    startQuotas: null,
    Quotas: 0, 
    totalOrder: 0, 
  }),
  async mounted() {

  },
  methods: {
    calculateQuotas() {
      this.totalOrder = Number(document.getElementById('total-order').value);
      this.Quotas = this.totalOrder / this.amountQuotas ;
    },
    replaceNumberWithCommas(number) {
            var n = number.toFixed(2); // Limita el resultado a dos decimales
            //Seperates the components of the number
            var n= number.toString().split(".");
            //Comma-fies the first part
            n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            n[1] = n[1] ? n[1].slice(0, 2) : "00";
            //Combines the two sections
            return n.join(",");
    },
  },
 };
</script>
