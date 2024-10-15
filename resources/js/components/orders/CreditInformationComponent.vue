<template>
   <div class="row">
        <div class="col-md-12">
            <hr>
            <h3 class="fs-title">Información de crédito</h3>
        </div>
        <div class="col-md-4">
            <label for="amount-quotas">Cantidad de cuotas</label>
            <input @change="calculateQuotas" v-model="amountQuotas" type="number" step="1" min="1" name="amount-quotas" id="amount-quotas" class="form-control" placeholder="Ingrese la cantidad de cuotas">
        </div>
        <div class="col-md-4">
            <label for="frequency-payment">Frecuencia de Pago</label>
            <v-select placeholder="Seleccionar frecuencia de pago"
                :options="optionsFrequency" 
                label="frequency-payment" 
                v-model="frequencyPayment"
                >
            </v-select>
            <input type="hidden" name="frequency-payment" v-model="frequencyPayment">
        </div>
      
        <div class="col-md-4">
            <label for="start-quotas">Fecha de inicio de Pago </label>
            <input v-model="startQuotas" type="date" name="start-quotas" id="start-quotas" class="form-control" placeholder="Ingrese la Fecha de inicio de Pago">
        </div>
        <div class="col-md-12">
            <p class="font-weight-bold text-dark mt-1">Monto por cuota: <span class="quotas">$ {{ replaceNumberWithCommas(Quotas) }}</span></p>
        </div>
    </div>
</template>

<script>
export default {
  components: {},
  props: {
  },

  data: () => ({
    frequencyPayment: "semanal",
    amountQuotas: 0,
    startQuotas: null,
    Quotas: 0,
    totalOrder: 0, 
    optionsFrequency : ["semanal", "quincenal", "mensual"],
  }),
  computed: {},
  async mounted() {
    this.calculateQuotas()
  },
  methods: {
    calculateQuotas() {
      this.totalOrder = Number(document.getElementById('total-order').value);
      this.Quotas = this.totalOrder / this.amountQuotas || 0 ;
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
    }
  },
  watch: {

  },
};
</script>
