<template>
  <div class="container">
    <div class="row">
        <div class="col-md-4">
          <label for="total">Total de crédito</label>
          <input type="text" name="total" id="total" class="form-control" placeholder="Ingrese el total de credito" 
            :value="replaceNumberWithCommas(totalOrder)"  :readonly="readonly">
        </div>
        <div class="col-md-4">
            <label for="amount_quotas">Cantidad de cuotas</label>
            <input @change="calculateQuotas" v-model="amountQuotas" type="number" step="1" min="1" name="amount_quotas" 
              id="amount_quotas" class="form-control" placeholder="Ingrese la cantidad de cuotas"
             :readonly="readonly">
        </div>
        <div class="col-md-4">
            <label for="start_date">Fecha de inicio de Pago </label>
            <input v-model="startQuotas" type="date" name="start_date" id="start_date" class="form-control"
             placeholder="Ingrese la Fecha de inicio de Pago"  :readonly="readonly" >
        </div>
        <div class="col-md-12 mt-3">
            <p class="font-weight-bold text-dark mt-1">Monto por cuota: <span class="quotas">$ {{ replaceNumberWithCommas(Quotas) }}</span></p>
            <input type="hidden" name="quota" v-model="Quotas" >
        </div>
    </div>
    <div class="row">
      <div class="col-md-12">
            <h4>Cobros planificados</h4 >
        </div>
    </div>
    <div  class="row">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">N° Couta</th>
            <th scope="col">Fecha</th>
            <th scope="col">Monto</th>
            <th scope="col">Completada</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(visit , index) in visits" :key="visit.id" >
            <td scope="row">{{ visit.number }}</td>
            <td>{{ visit.date }}</td>
            <td>{{ replaceNumberWithCommas(visit.amount) }}</td>
            <td>{{ visit.is_completed == 0 ? 'No' : 'Si'  }}</td>
            <td> 
              <a :href="urlScheduleIndex + '/' + visit.schedule_id" class="btn btn-sm btn-primary btn-action-icon" title="Ver agenda" data-toggle="tooltip">
                <i class="fas fa-eye"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
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
    }, 
    visits : {
      type: Array,
      default: () => []
    }, 
    urlScheduleIndex : {
      type: String, 
      default: () => ""
    },
    readonly : {
      type: Boolean,
      default: () => false
    }
  },

  data: () => ({
    amountQuotas: 0,
    startQuotas: null,
    Quotas: 0,
    totalOrder: 0, 
  }),
  computed: {},
  async mounted() {
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
};
</script>
