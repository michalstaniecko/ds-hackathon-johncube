<template>
  <div class="py-5">
    <div class="py-3">
      Długość okresu w dniach
      <div class="row pb-3">
        <div class="col-sm-10 col-md-8 col-lg-6">
          <VueSlider v-model="period" :min="1" :max="21" :marks="true" @change="periodChange"/>
        </div>
      </div>
    </div>
    <b-table striped hover :fields="fieldsAll" :items="items" v-if="fields">
      <template #cell(country)="data">
        {{ data.value }}
      </template>
      <template #cell(I)="data">
        <div :class="{
          'bg-success':itemsAll[data.item.country].periods[0].diffAvg.confirmed * 1000000 < 0 ,
          'bg-warning': (itemsAll[data.item.country].periods[0].diffAvg.confirmed * 1000000 >= 0 && itemsAll[data.item.country].periods[0].diffAvg.confirmed * 1000000 < 5),
          'bg-danger': (itemsAll[data.item.country].periods[0].diffAvg.confirmed * 1000000 >= 5)
        }">
          {{ itemsAll[data.item.country].periods[0].realDiff.confirmed }}<br/>
          {{ data.value }}
          <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill>

        </div>
      </template>
      <template #cell(II)="data">

        <div :class="{
          'bg-success':itemsAll[data.item.country].periods[1].diffAvg.confirmed * 1000000 < 0 ,
          'bg-warning': (itemsAll[data.item.country].periods[1].diffAvg.confirmed * 1000000 >= 0 && itemsAll[data.item.country].periods[0].diffAvg.confirmed * 1000000 < 5),
          'bg-danger': (itemsAll[data.item.country].periods[1].diffAvg.confirmed * 1000000 >= 5)
        }">
          {{ itemsAll[data.item.country].periods[1].realDiff.confirmed }}<br/>
          {{ data.value }}
          <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill>
        </div>
      </template>
      <template #cell(III)="data">

        <div :class="{
          'bg-success':itemsAll[data.item.country].periods[2].diffAvg.confirmed * 1000000 < 0 ,
          'bg-warning': (itemsAll[data.item.country].periods[2].diffAvg.confirmed * 1000000 >= 0 && itemsAll[data.item.country].periods[0].diffAvg.confirmed * 1000000 < 5),
          'bg-danger': (itemsAll[data.item.country].periods[2].diffAvg.confirmed * 1000000 >= 5)
        }">
          {{ itemsAll[data.item.country].periods[2].realDiff.confirmed }}<br/>
          {{ data.value }}
          <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill>
        </div>
      </template>
      <template #cell(IV)="data">

        <div :class="{
          'bg-success':itemsAll[data.item.country].periods[3].diffAvg.confirmed * 1000000 < 0 ,
          'bg-warning': (itemsAll[data.item.country].periods[3].diffAvg.confirmed * 1000000 >= 0 && itemsAll[data.item.country].periods[0].diffAvg.confirmed * 1000000 < 5),
          'bg-danger': (itemsAll[data.item.country].periods[3].diffAvg.confirmed * 1000000 >= 5)
        }">
          {{ itemsAll[data.item.country].periods[3].realDiff.confirmed }}<br/>
          {{ data.value }}
          <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill>
        </div>
      </template>
      <template #cell(V)="data">
        <div :class="{
          'bg-success':itemsAll[data.item.country].periods[4].diffAvg.confirmed * 1000000 < 0 ,
          'bg-warning': (itemsAll[data.item.country].periods[4].diffAvg.confirmed * 1000000 >= 0 && itemsAll[data.item.country].periods[0].diffAvg.confirmed * 1000000 < 5),
          'bg-danger': (itemsAll[data.item.country].periods[4].diffAvg.confirmed * 1000000 >= 5)
        }">
          {{ itemsAll[data.item.country].periods[4].realDiff.confirmed }}<br/>
          {{ data.value }}
          <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill>
        </div>
      </template>
      <template #cell(actions)="data">
        <b-button size="sm" @click="data.toggleDetails">
          {{ data.detailsShowing ? 'Hide' : 'Show' }} Details
        </b-button>
      </template>
      <template #row-details="row">
        <b-card>
          Tu będzie wykres
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
        period: 5,
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
        itemsAll: [],
        tableLoading: false
      }
    },

    methods: {
      periodChange(value) {
        this.period = value
      },

      requestData() {
        axios.get('http://localhost:3000/?period=5')
            .then(({data}) => {
              console.log(data)
            })
      },
      getDataset() {
        this.tableLoading = true
        return new Promise((resolve, reject) => {
          axios.get(`http://ds-hackathon.johncube.pl/bridge.php?period=${this.period}`)
              .then(({data}) => {
                this.tableLoading = false
                this.itemsAll = data
                resolve(Object.keys(data).map(country => {
                  return {
                    country: country,
                    I: data[country].periods[0].diff.confirmed,
                    II: data[country].periods[1].diff.confirmed,
                    III: data[country].periods[2].diff.confirmed,
                    IV: data[country].periods[3].diff.confirmed,
                    V: data[country].periods[4].diff.confirmed,
                  }
                }))

              })
        })
      }
    },

    created() {
      //this.requestData()
      this.getDataset()
          .then(data => this.items = data)
    },

    computed: {
      fieldsAll() {
        return [...this.fields, ...this.fieldsDataset, ...['actions']]
      },
      itemsAll() {

      }
    },

    watch: {
      period() {
        this.getDataset()
      }
    }
  }
</script>

<style lang="scss">
  .bg-success {
    svg {
      transform: rotate(45deg);
    }
  }
  .bg-warning {
    svg {
      transform: rotate(-10deg);
    }
  }
  .bg-danger {
    svg {
      transform: rotate(-45deg);
    }
  }

</style>
