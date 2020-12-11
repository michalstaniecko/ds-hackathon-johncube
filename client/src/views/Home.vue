<template>
  <div class="home">
    <Table/>
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue'
import Table from "@/components/Table";

export default {
  name: 'Home',
  components: {
    Table,
    HelloWorld
  }
}
</script>
