

<template>
    <div>
      
      <div class="row mt-4 ml-3" v-if="record && record.value">
        <h1 class="mt-5">
            {{ record.value.title }}
          </h1>
          <h2>
            {{ record.value.artist }}
          </h2>
        <div>
          <img :src="getRecordImage(record)" class=" rounded mx-auto d-block" :alt="record.title">
        <div class="card w-100 m-0 p-3">
          <button @click="toggleCollapse" class="btn btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCollectionForm" aria-expanded="false" aria-controls="collapseCollectionForm">
            <div class="row">
              <div class="text-start col-auto me-auto">
              Recorddetails
              </div>
              <div class="col-auto">
              <i :class="isClicked ? 'bi bi-caret-up mb-4' : 'bi bi-caret-down'" style="font-size: 20px;"></i>
            </div>
            </div>
            </button>
            <div class="collapse text-start mt-4" id="collapseCollectionForm">
              <p>Genre: {{ record.value.genre }}</p>
              <p>Format: {{ record.value.format }}</p>
              <p>Label: {{ record.value.label }}</p>
              <p>Land: {{ record.value.country }}</p>
              <p>Erscheinungsdatum: {{ record.value.releasedate }}</p>
              <p>Preis: {{ record.value.price }}</p>
              <p>Zustand: {{ record.value.grade }}</p>
            </div>
          
      </div>
          
      </div>
    </div>
      <div v-else>
        <p>Loading...</p>
      </div>


    </div>
  </template>
  <script>
  import { reactive, ref, onMounted, watch } from 'vue';
  import axios from 'axios';
  
  export default {
    name: 'DisplayRecord',
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
    setup(props) {
      const record = reactive({ data: null });
      const isClicked = ref(false);

      const toggleCollapse = () => {
        isClicked.value = !isClicked.value;
      };


  
      const fetchRecord = async () => {
      try {
        const response = await axios.get(`/api/collection/${props.collectionId}/record/${props.recordId}`, {
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
          },
        });
        record.value = response.data;
        console.log('Fetched record:', record.value.id);
      } catch (error) {
        console.error('Failed to fetch record:', error);
        error.value = 'Failed to fetch record';
      }
    };

      watch(record, (newVal) => {
        console.log('Record updated:', newVal);
      });
  
      const updateRecord = async () => {
        try {
          await axios.put(`/api/record/${props.recordId}`, record.value, {
            headers: {
              'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
            },
          });
          alert('Record updated successfully!');
        } catch (error) {
          console.error('Failed to update record:', error);
        }
      };
  
      const getRecordImage = (record) => {
            if (record.value.bookletfront) {
                return `${record.value.bookletfront}`;
            } else if (record.value.bookletback) {
                return `${record.value.bookletback}`;
            } else {
                return '../assets/vue.svg';
            }
        };
        
        onMounted(() => {
          fetchRecord();
          });
      
  
      return {
        record,
        isClicked,
        getRecordImage,
        toggleCollapse,
      };
    },
  };
  </script>
  
  <style scoped>
  .read-the-docs {
    color: #888;
  }
  </style>
  