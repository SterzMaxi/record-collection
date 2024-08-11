<template>
  <div class="container-fluid p-0 mt-5">
    <DisplayRecord :recordId="recordId" :collectionId="collectionId" />

  </div>
</template>
<script>
import DisplayRecord from '../components/DisplayRecord.vue';


export default {
  name: 'ShowRecord',
  components: {
    DisplayRecord,
  },
  props: {
    recordId: {
      type: Number,
      required: true,
    },
    collectionId: {
      type: Number,
      required: true,
    },
  },

  mounted() {
    console.log('Collection ID:', this.collectionId);
    console.log('Record ID:', this.recordId);
  },


};
</script>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>