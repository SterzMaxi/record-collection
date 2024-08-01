<template>
    <div id="recordCarousel" class="carousel carousel-dark slide mb-4" >
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="cards-wrapper">
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 1</h5>
                            <p class="card-text">Record 1</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 1</h5>
                            <p class="card-text">Record 1</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 1</h5>
                            <p class="card-text">Record 1</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 1</h5>
                            <p class="card-text">Record 1</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 1</h5>
                            <p class="card-text">Record 1</p>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="carousel-item">
                <div class="cards-wrapper">
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 2</h5>
                            <p class="card-text">Record 2</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 2</h5>
                            <p class="card-text">Record 2</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 2</h5>
                            <p class="card-text">Record 2</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 2</h5>
                            <p class="card-text">Record 2</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 2</h5>
                            <p class="card-text">Record 2</p>
                        </div>
                    </div>
                </div>
                
            </div>
          </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#recordCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#recordCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    <div>

  
  </div>
        
      </div>
      <RouterLink type="button" to="/createrecord" class="btn mx-auto col-1 mb-3 btn-primary">Record erstellen</RouterLink>
    </template>
    
    <script setup>
    import { ref, computed, onMounted } from 'vue';
    import axios from 'axios';
  
      /*
  const records = ref([]);
  const selectedCollectionId = ref(null);
  

  const fetchRecords = async () => {
    try {
      const response = await axios.get('/api/records', {
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('vue-token') },
      });
      collections.value = response.data;
    } catch (error) {
      console.error("There was an error fetching the records:", error);
    }
  };


  
  const sortedRecords = computed(() => {
    if (!Array.isArray(collections.value)) {
      return [];
    }
    return records.value.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  });
  
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
      }
    }
  };
  
  onMounted(() => {
    fetchRecords();
  });
    */
  </script>
    
  
  <style scoped>


  .cards-wrapper{
    display: flex;
  }
  </style>