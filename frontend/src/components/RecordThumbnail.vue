<template>
    <div id="recordCarousel" class="carousel carousel-dark slide mb-4" >
                <div v-if="records.length === 0" class="carousel-item active">
                    <div class="cards-wrapper">
                    <p>No records found.</p>
                    </div>
                </div>
                <div v-else class="carousel-item" v-for="(chunk, index) in chunkedRecords" :key="index" :class="{ active: index === 0 }">
                    <div class="cards-wrapper">
                        <div v-for="record in chunk" :key="record.id" class="card me-3" style="width: 14rem;">
                            <RouterLink :to="{ name: 'ShowRecord', params: { recordId: record.id, collectionId: collectionId } }">
                                <img :src="getRecordImage(record)" class="card-img-top rounded mx-auto d-block" :alt="record.title">
                                    <div class="card-body">
                                    <h5 class="card-title">{{ record.title }}</h5>
                                    <p class="card-text">{{ record.artist }}</p>
                                </div>
                            </RouterLink>
                        </div>
                    </div>
                </div>
            <div class="carousel-item">
                <div class="cards-wrapper">
                    <!--
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
                -->
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
      <RouterLink type="button" :to="{name: 'CreateRecord', params: { collectionId: collectionId } }" class="btn mx-auto col-1 mb-3 btn-primary">Record erstellen</RouterLink>
    </template>
    
    <script>
    import { ref, computed, onMounted } from 'vue';
    import axios from 'axios';

    export default {
      name: 'RecordThumbnail',
      props: {
      collectionId: {
        type: Number,
        required: true,
      },
    },
        setup(props) {
        const records = ref([]);
        const recordsPerPage = 5; // Number of records to show per carousel item

        const fetchRecords = async () => {
      try {
        const response = await axios.get(`/api/collection/${props.collectionId}/records`, {
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
          },
        });
        records.value = response.data;
      } catch (error) {
        console.error('Failed to fetch records:', err);
      }
    };

        const chunkedRecords = computed(() => {
        const chunks = [];
        for (let i = 0; i < records.value.length; i += recordsPerPage) {
            chunks.push(records.value.slice(i, i + recordsPerPage));
        }
        return chunks;
        });

        const getRecordImage = (record) => {
            const baseUrl = window.location.origin;
            if (record.bookletfront) {
                return `${record.bookletfront}`;
            } else if (record.bookletback) {
                return `${record.bookletback}`;
            } else {
                return '../assets/vue.svg';
            }
        };

        onMounted(() => {
        fetchRecords();
        });

        return {
        records,
        chunkedRecords,
        getRecordImage,
        };
    },
    }
  
  
  </script>
    
  
  <style scoped>


  .cards-wrapper{
    display: flex;
  }
  </style>