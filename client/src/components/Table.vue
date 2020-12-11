<template>
  <div class="py-5">
    <div class="py-3">
      Długość okresu w dniach
      <div class="row pb-3">
        <div class="col-sm-10 col-md-8 col-lg-6">
          <VueSlider v-model="period" :min="1" :max="21" :marks="true"/>
        </div>
      </div>
    </div>
    <b-table striped hover :fields="fieldsAll" :items="items" v-if="fields">
      <template #cell(country)="data">
        {{ data.value }}
      </template>
      <template #cell(I)="data">
        {{ data.value }} population {{ itemsAll[data.item.country].population }}
        <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill>
      </template>
      <template #cell(II)="data">
        {{ data.value }}
        <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill>
      </template>
      <template #cell(III)="data">
        {{ data.value }}
        <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill>
      </template>
      <template #cell(IV)="data">
        {{ data.value }}
        <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill>
      </template>
      <template #cell(V)="data">
        {{ data.value }}
        <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill>
      </template>
      <template #cell(actions)="data">
        <b-button size="sm" @click="data.toggleDetails">
          {{ data.detailsShowing ? 'Hide' : 'Show' }} Details
        </b-button>
      </template>
      <template #row-details="row">
        <b-card>
          Wykres
        </b-card>
      </template>
    </b-table>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    name: "Table",

    data() {
      return {
        period: 1,
        fieldsDataset: [
          {key: 'I', sortable: true},
          {key: 'II', sortable: true},
          {key: 'III', sortable: true},
          {key: 'IV', sortable: true},
          {key: 'V', sortable: true}
        ],
        fields: [
          {key: 'country', sortable: true}
        ],
        items: [],
        itemsAll: []
      }
    },

    methods: {
      requestData() {
        axios.get('http://localhost:3000')
            .then(({data}) => {
              console.log(data["Afghanistan"])
            })
      },
      getDataset() {
        new Promise((resolve, reject) => {
          axios.get('http://localhost:3000')
              .then(({data}) => {
                this.itemsAll = data
                resolve(Object.keys(data).map(country => {
                  return {
                    country: data[country].name,
                    I: data[country].lastDays[0],
                    II: data[country].lastDays[1],
                    III: data[country].lastDays[2],
                    IV: data[country].lastDays[3],
                    V: data[country].lastDays[4]
                  }
                }))

              })
        })
      }
    },

    created() {
      this.getDataset()
          .then(data => this.items = data)
    },

    computed: {
      fieldsAll() {
        return [...this.fields, ...this.fieldsDataset, ...['actions']]
      },
      itemsAll() {

      }
    }
  }
</script>

<style scoped>

</style>
