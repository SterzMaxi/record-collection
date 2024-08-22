<template>
  <div class="container-fluid p-0">
    <div class="card w-100 m-0 p-3">
      <div class="col-6 mx-auto">

        <button @click="toggleCollapse" class="btn btn-outline-dark" type="button" data-bs-toggle="collapse"
          data-bs-target="#collapseCollectionForm" aria-expanded="false" aria-controls="collapseCollectionForm">
          <div>
            Erstelle Collection
          </div>
        </button>
        <div>
          <button @click="toggleCollapse" class="btn" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseCollectionForm" aria-expanded="false" aria-controls="collapseCollectionForm">
            <i :class="isClicked ? 'bi bi-caret-up mb-4' : 'bi bi-caret-down'" style="font-size: 20px;"></i>
          </button>

        </div>
      </div>

      <div class="collapse" id="collapseCollectionForm">
        <form @submit.prevent="submitForm">
          <div>
            <label class="h3" for="collectionname">Collection Name:</label>
            <input id="collectionname" type="text" v-model="form.collectionname" class="form-control text-center"
              required>
          </div>
          <div>
            <label class="h5" for="style">Style:</label>
            <input id="style" type="text" v-model="form.style" class="form-control text-center" required>
          </div>

          <button class="mt-4 btn btn-success" type="submit" data-bs-toggle="collapse"
            data-bs-target="#collapseCollectionForm" aria-expanded="false"
            aria-controls="collapseCollectionForm">Erstellen</button>
        </form>
      </div>
    </div>



    <div>
      <div v-for="collection in sortedCollections" :key="collection.id" class="card mb-2">
        <h3>{{ collection.collectionname }}</h3>
        <h6>{{ collection.style }}</h6>
        <div class="hover-enabled">
          <RecordCard :collectionId="collection.id" />
        </div>
        <button type="button" class="btn btn-danger position-absolute top-0 end-0 m-2" data-bs-toggle="modal"
          data-bs-target="#DeleteCollectionModal" @click="setCollectionID(collection.id)">
          <i class="bi bi-trash"></i>
        </button>
        <button type="button" class="btn btn-primary position-absolute top-0 start-0 m-2" data-bs-toggle="modal"
          data-bs-target="#EditModal" @click="setCollectionID(collection.id)">
          <i class="bi bi-pencil"></i>
        </button>

        <!-- Edit Modal -->
        <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="EditModalLabel">Bearbeite Collection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form @submit.prevent="submitEditForm">
                  <div>
                    <label for="editCollectionName">Collection Name:</label>
                    <input id="editCollectionName" type="text" v-model="editForm.collectionname" class="form-control"
                      :placeholder="collection.collectionname" required>
                  </div>
                  <div>
                    <label for="editStyle">Style:</label>
                    <input id="editStyle" type="text" v-model="editForm.style" class="form-control"
                      :placeholder="collection.style" required>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Änderungen speichern</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>


        <!-- Delete Modal -->
        <div class="modal fade" id="DeleteCollectionModal" tabindex="-1" role="dialog" aria-labelledby="DeleteCollectionModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="DeleteCollectionModalLabel">Löschen?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>möchtest du wirklich die Collection
                <h4>{{ collection.collectionname }}</h4> löschen?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                  @click="confirmDeletion(collection.id)">Löschen</button>
              </div>
            </div>
          </div>
        </div>

        <RouterLink type="button" class="routerlink-button btn mb-3 btn-primary mt-5" :to="{ name: 'CreateRecord', params: { collectionId: collection.id } }">
          Record erstellen
        </RouterLink>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'CollectionShow',
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
const currentCollection = ref({});
const editForm = ref({
  collectionname: '',
  style: '',
});

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

const setCollectionID = (collectionId) => {
  selectedCollectionId.value = collectionId;
};


const confirmDeletion = async () => {
  if (selectedCollectionId.value) {
    try {
      await axios.delete(`/api/collection/${selectedCollectionId.value}`, {
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('vue-token') },
      });
      console.log("Collection deleted successfully");
      await fetchCollections();
    } catch (error) {
      console.error("There was an error deleting the collection:", error);
      alert("Failed to delete the collection. Please try again.");
    }
  }
};

const submitEditForm = async () => {
  if (selectedCollectionId.value) {
    try {
      await axios.put(`/api/collection/${parseInt(selectedCollectionId.value)}`, {
        collectionname: editForm.value.collectionname,
        style: editForm.value.style
      }, {
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('vue-token') }
      });
      console.log("Collection updated successfully");
      await fetchCollections();
    } catch (error) {
      console.error("There was an error updating the collection:", error);
      alert("Failed to update the collection. Please try again.");
    }
  }
};

onMounted(() => {
  fetchCollections();
});

</script>


<style scoped>

.routerlink-button {
  align-self: center;
  width: 10em;
}
.container-fluid {
  padding: 0 !important;
  /* Remove any padding to make the card truly full width */
}

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