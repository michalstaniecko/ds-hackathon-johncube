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
        {{ data.value }}
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
        {{ data.value }} {{data}}
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
        axios.get('http://localhost:3000/?period=5')
            .then(({data}) => {
              console.log(data)
            })
      },
      getDataset() {
        return new Promise((resolve, reject) => {
          axios.get('http://localhost:3000/?period=5')
              .then(({data}) => {
                this.itemsAll = data
                resolve(Object.keys(data).map(country => {
                  console.log({
                    country: country,
                    I: data[country].periods[0].diff.confirmed,
                    II: data[country].periods[1].diff.confirmed,
                    III: data[country].periods[2].diff.confirmed,
                    IV: data[country].periods[3].diff.confirmed
                  })
                  return {
                    country: country,
                    I: data[country].periods[0].diff.confirmed,
                    II: data[country].periods[1].diff.confirmed,
                    III: data[country].periods[2].diff.confirmed,
                    IV: data[country].periods[3].diff.confirmed
                  }
                }))

              })
        })
      }
    },

    created() {
      this.requestData()
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
