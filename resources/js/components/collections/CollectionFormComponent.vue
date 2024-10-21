<template>
   <div class="row">
        <div class="col-md-4">
            <label for="amount_quotas">Cantidad de cuotas</label>
            <input @change="calculateQuotas" v-model="amountQuotas" type="number" step="1" min="1" name="amount_quotas" id="amount_quotas" class="form-control" placeholder="Ingrese la cantidad de cuotas">
        </div>
        <div class="col-md-4">
            <label for="frequency-payment">Frecuencia de Pago</label>
            <v-select placeholder="Seleccionar frecuencia de pago"
                :options="optionsFrequency" 
                label="frequency-payment" 
                v-model="frequencyPayment"
                >
            </v-select>
            <input type="hidden" name="frequency" v-model="frequencyPayment">
        </div>
      
        <div class="col-md-4">
            <label for="start_date">Fecha de inicio de Pago </label>
            <input v-model="startQuotas" type="date" name="start_date" id="start_date" class="form-control" placeholder="Ingrese la Fecha de inicio de Pago">
        </div>
        <div class="col-md-12">
            <p class="font-weight-bold text-dark mt-1">Monto por cuota: <span class="quotas">$ {{ replaceNumberWithCommas(Quotas) }}</span></p>
            <input type="hidden" name="quota" v-model="Quotas">
        </div>
    </div>
</template>

<script>
export default {
  components: {
    
  },
  props: {
    collection : {
      type: Object,
      default: () => {}
    }
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
    this.frequencyPayment = this.collection.frequency
    this.amountQuotas = this.collection.amount_quotas
    this.startQuotas = this.collection.start_date
    this.totalOrder = this.collection.total
    this.calculateQuotas();
  },
  methods: {
    calculateQuotas() {
        this.Quotas = this.totalOrder / this.amountQuotas || 0
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
