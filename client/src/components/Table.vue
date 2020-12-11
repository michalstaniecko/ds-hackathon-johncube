<template>
  <div class="py-5">
    <div class="py-3">
      Długość okresu w dniach
      <div class="row pb-3">
        <div class="col-sm-10 col-md-8 col-lg-6">
          <VueSlider :min="1" :max="21" :marks="true"/>
        </div>
      </div>
    </div>
    <b-table striped hover :fields="fieldsAll" :items="items">
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
  export default {
    name: "Table",

    data() {
      return {
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
        items: [
          {country: 'Poland', ...{I: 4, II: 6, III: 3, IV: 8, V: 10}},
          {country: 'Germany', ...{I: 7, II: 4, III: 8, IV: 10, V: 3}}
        ]
      }
    },

    computed: {
      fieldsAll() {
        return [...this.fields, ...this.fieldsDataset, ...['actions']]
      }
    }
  }
</script>

<style scoped>

</style>
