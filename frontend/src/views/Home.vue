<template>
<div>
  <DisplayAllCollections />
</div>
  
</template>

<script>
  import DisplayAllCollections from '../components/DisplayAllCollections.vue';


export default {
  name: 'HomeView',
  components: {
    DisplayAllCollections,
  },
};
</script>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>
