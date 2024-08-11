<template>
  <div class="container-fluid p-0 mt-5">
    <div v-for="collection in collections" :key="collection.id" class="card">
      <h3>{{ collection.collectionname }}</h3>
      <span>von </span>
      <h5>{{ collection.username }}</h5>
      <h6>{{ collection.style }}</h6>
      <RecordCard :collectionId="collection.id" />
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import RecordCard from '../components/RecordThumbnail.vue';

export default {
  name: 'DisplayAllCollections',
  components: {
    RecordCard,
  },
  data() {
    return {
      collections: [],
    };
  },
  methods: {
    async fetchCollections() {
      try {
        const response = await axios.get('/api/allcollections'); // No authentication headers needed
        this.collections = response.data;
      } catch (error) {
        console.error('Error fetching collections:', error);
      }
    },
  },
  mounted() {
    this.fetchCollections();
  },
};
</script>

<style scoped>
.card {
  width: 80vw !important;
  /* Full viewport width */
  max-width: 100% !important;
  /* Ensure no maximum width is set */
  margin: 0 !important;
  padding: 20px !important;
  /* Adjust padding as necessary */
  box-sizing: border-box;
}
</style>