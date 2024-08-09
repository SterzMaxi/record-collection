<template>
  <div class="container-fluid p-0">
      <div class="card w-100 m-0 p-3">
        <div class="col-6 mx-auto">
          
          <button @click="toggleCollapse" class="btn btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCollectionForm" aria-expanded="false" aria-controls="collapseCollectionForm">
            <div>
            Erstelle Collection
            </div>
          </button>
          <div>
            <button @click="toggleCollapse" class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCollectionForm" aria-expanded="false" aria-controls="collapseCollectionForm">
              <i :class="isClicked ? 'bi bi-caret-up mb-4' : 'bi bi-caret-down'" style="font-size: 20px;"></i>
            </button>
            
          </div>
        </div>
        
        <div class="collapse" id="collapseCollectionForm">
          <form @submit.prevent="submitForm">
            <div>
              <label class="h3" for="collectionname">Collection Name:</label>
              <input id="collectionname" type="text" v-model="form.collectionname" class="form-control text-center" required>
            </div>
            <div>
              <label class="h5" for="style">Style:</label>
              <input id="style" type="text" v-model="form.style" class="form-control text-center" required>
            </div>

            <button class="mt-4 btn btn-success" type="submit" data-bs-toggle="collapse" data-bs-target="#collapseCollectionForm" aria-expanded="false" aria-controls="collapseCollectionForm">Erstellen</button>
          </form>
        </div>
      </div>
      
        
    
    <div>
      <div v-for="collection in sortedCollections" :key="collection.id" class="card mb-2">
        <h3>{{ collection.collectionname }}</h3>
        <h6>{{ collection.style }}</h6>
        <RecordCard :collectionId="collection.id" />
        <button type="button" class="btn btn-danger col-1 mx-auto"  data-bs-toggle="modal" data-bs-target="#DeleteModal" @click="setCollectionToDelete(collection.id)">Collection Löschen</button>

<!-- Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="DeleteModalLabel">Löschen?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>möchtest du wirklich <h4>{{ collection.collectionname }}</h4> löschen?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" @click="confirmDeletion(collection.id)">Löschen</button>
      </div>
    </div>
  </div>
</div>

</div>
      
    </div>
  </div>
  </template>

  <script>
    export default {
      name: 'App',
      components: {
        RecordCard
      },
    };
  </script>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import axios from 'axios';
  import RecordCard from '../components/RecordThumbnail.vue';

  const form = ref({
  collectionname: '',
  style: '',
});

const collections = ref([]);
const selectedCollectionId = ref(null);

const isClicked = ref(false);
const toggleCollapse = () => {
  isClicked.value = !isClicked.value;
};

const fetchCollections = async () => {
  try {
    const response = await axios.get('/api/collections', {
      headers: { 'Authorization': 'Bearer ' + localStorage.getItem('vue-token') },
    });
    collections.value = response.data;
  } catch (error) {
    console.error("There was an error fetching the collections:", error);
  }
};

const sortedCollections = computed(() => {
  if (!Array.isArray(collections.value)) {
    return [];
  }
  return collections.value.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

const submitForm = async () => {
  const formData = new FormData();
  Object.keys(form.value).forEach(key => {
    if (form.value[key]) {
      formData.append(key, form.value[key]);
    }
  });

  try {
    const response = await axios.post('/api/collection', formData, {
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
        'Content-Type': 'multipart/form-data',
      },
    });
    console.log("axios post success");
    console.log(response.data);
    await fetchCollections(); // Refresh collections list after submission
    // Clear the form
    form.value.collectionname = '';
    form.value.style = '';
  } catch (error) {
    console.error('Upload failed', error);
  }
};

const setCollectionToDelete = (collectionId) => {
  selectedCollectionId.value = collectionId;
};

// Confirm deletion of the collection
const confirmDeletion = async () => {
  if (selectedCollectionId.value) {
    try {
      await axios.delete(`/api/collection/${selectedCollectionId.value}`, {
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('vue-token') },
      });
      console.log("Collection deleted successfully");
      await fetchCollections(); // Refresh collections list after deletion
    } catch (error) {
      console.error("There was an error deleting the collection:", error);
      alert("Failed to delete the collection. Please try again.");
    }
  }
};

onMounted(() => {
  fetchCollections();
});

</script>
  

<style scoped>
.container-fluid {
  padding: 0 !important; /* Remove any padding to make the card truly full width */
}

.card {
  width: 80vw !important; /* Full viewport width */
  max-width: 100% !important; /* Ensure no maximum width is set */
  margin: 0 !important;
  padding: 20px !important; /* Adjust padding as necessary */
  box-sizing: border-box;
}
</style>