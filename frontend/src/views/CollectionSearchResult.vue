<template>
  <div class="container-fluid p-0 mt-5">
    <div v-if="collections && collections.length">
      <div v-for="collection in collections" :key="collection.id" class="card">
        <h3>{{ collection.collectionname }}</h3>
        <span>von </span>
        <h5>{{ collection.username }}</h5>
        <h6>{{ collection.style }}</h6>
        <RecordCard :collectionId="collection.id" />
      </div>
    </div>
    <div v-else>
      <p>Keine Collections gefunden</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'
import RecordCard from '../components/RecordThumbnail.vue';

export default {
  name: 'CollectionSearchResult',
  components: {
    RecordCard,
  },


setup(props) {
  const route = useRoute()
  const collections = ref([])

  onMounted(async () => {
    // Get the search query from the route parameters
    const query = route.query.q || '';

    // Check if query is not empty before making a request
    if (query.trim()) {
      try {
        // Make an API call to fetch search results
        const response = await axios.get(`/api/collections/search?query=${query}`);

        // Check if response data is an array, otherwise assign an empty array
        collections.value = Array.isArray(response.data) ? response.data : [];
      } catch (error) {
        // Handle errors
        console.error('Failed to fetch search results:', error);
        // Set collections to an empty array in case of an error to prevent further issues
        collections.value = [];
      }
    }
  });
  return {
    collections
  }
}
};


</script>

<style scoped>
.card {
  width: 80vw !important;
  max-width: 100% !important;
  margin: 0 !important;
  padding: 20px !important;
  box-sizing: border-box;
}
</style>