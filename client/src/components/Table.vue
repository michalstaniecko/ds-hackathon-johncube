<template>
  <div>
    <b-table striped hover :fields="fieldsAll" :items="items">
      <template #cell(country)="data">
        {{ data.value }}
      </template>
      <template v-for="(field, index) in fieldsDataset" >
        {{ data.value }}fdf
      </template>
    </b-table>
  </div>
</template>

<script>
  export default {
    name: "Table",

    data() {
      return {
        fieldsDataset:  ['I', 'II', 'III', 'IV'],
        fields: [
          'country'
        ],
        items: [
          {country: 'Poland', ...{I: 4, II: 6, III: 3, IV: 8}}
        ]
      }
    },

    computed: {
      fieldsAll() {
        return [...this.fields, ...this.fieldsDataset]
      }
    }
  }
</script>

<style scoped>

</style>
