<template>
    <div class="row mb-3">
       <!-- day of the week -->
       <div class="col-md-6 day-week-collection">
          <label for="day-week">Día de cobro</label>
          <v-select placeholder="Seleccionar día de la semana"
          :options="optionsDayWeek"
                label="day-week" 
                v-model="collectionDay"
                :disabled="readonly"
                >
            </v-select>
            <input type="hidden" name="collection_day" v-model="collectionDay">
        </div>

        <!-- collection frequency -->
        <div class="col-md-6 frequency-collection">
          <label for="frequency-collection">Frecuencia de Cobro</label>
          <v-select placeholder="Seleccionar frecuencia de cobro"
          :options="optionsFrequency" 
                label="frequency-collection" 
                v-model="frequencyCollection"
                :disabled="readonly"
                >
            </v-select>
            <input type="hidden" name="collection_frequency" v-model="frequencyCollection">
        </div>
    </div>
</template>

<script>


export default {
  components: {},
  props: {
    customer: {
      type: Object,
      default: "",
    },
    readonly: {
      type: Boolean,
      default: false,
    }
  },

  data: () => ({
    frequencyCollection: "",
    collectionDay: "",
    canChangeFrequency: true, 
    optionsFrequency : ["Cada semana", "Cada dos semanas", "Cada mes - primera semana" , "Cada mes - segunda semana", "Cada mes - tercera semana", "Cada mes - cuarta semana"],
    optionsDayWeek : ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"],
  }),
  async mounted() {
    // console.log(this.customer);
    if(this.customer){
      this.frequencyCollection = this.customer.collection_frequency;
      this.collectionDay = this.customer.collection_day;
    }
  },
  methods: {
  
  }
};
</script>
