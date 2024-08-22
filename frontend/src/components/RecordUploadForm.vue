<template>
  <div class="container-fluid p-0">
    <div class="card w-100 p-3">
      <form @submit.prevent="submitForm">
        <div class="row mt-4">
          <div class="col">
            <label class="h5 w-100" for="title">Title:
              <input type="text" size="100" class="form-control text-center" v-model="form.title" required>
            </label>
          </div>
          <div class="col">
            <label class="h5 w-100" for="artist">Artist:
              <input type="text" size="100" class="form-control text-center" v-model="form.artist" required>
            </label>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col">
            <label class="h5 w-100" for="format">Format:
              <select class="form-select" v-model="form.format" required>
                <option value="12-Inch LP" class="text-center">12-Inch LP</option>
                <option value="12-Inch EP" class="text-center">12-Inch EP</option>
                <option value="12-Inch Single" class="text-center">12-Inch Single</option>
                <option value="7-Inch Single" class="text-center">7-Inch Single</option>
                <option value="10-Inch Record" class="text-center">10-Inch Record</option>
              </select>
            </label>
          </div>
          <div class="col">
            <label class="h5 w-100" for="trackcount">Track Anzahl:
              <input type="number" class="form-control text-center" v-model.number="form.trackcount" @input="emitValue"
                :max="50" required />
            </label>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col">
            <label class="h5 w-100" for="label">Label:
              <input type="text" size="100" class="form-control text-center" v-model="form.label" required>
            </label>
          </div>
          <div class="col">
            <label class="h5 w-100" for="country">Land:
              <input type="text" size="100" class="form-control text-center" v-model="form.country" required>
            </label>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col">
            <label class="h5 w-100" for="releasedate">Erscheinungsdatum:
              <VueDatePicker v-model="form.releasedate" :format="dateFormat" :bootstrap-styles="true" required />
            </label>
          </div>
          <div class="col">
            <label class="h5 w-100" for="genre">Genre:
              <input type="text" size="100" class="form-control text-center" v-model="form.genre" required>
            </label>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col">
            <label class="h5 w-100" for="price">Preis:
              <input type="number" size="100" class="form-control text-center" v-model.number="form.price" step="0.01"
                required>
            </label>
          </div>
          <div class="col">
            <label class="h5 w-100" for="grade">Kondition:
              <select class="form-select" v-model="form.grade" required>
                <option value="M" class="text-center">Mint (M)</option>
                <option value="M-" class="text-center">Near Mint (NM/M-)</option>
                <option value="VG+" class="text-center">Very Good Plus (VG+)</option>
                <option value="VG" class="text-center">Very Good (VG)</option>
                <option value="G+" class="text-center">Good Plus (G+)</option>
                <option value="G" class="text-center">Good (G)</option>
                <option value="P" class="text-center">Poor (P)</option>
              </select>
            </label>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col">
            <label class="h5 w-100" for="bookletfront">Booklet Front:
              <input type="file" size="100" class="form-control text-center"
                @change="handleFileChange('bookletfront', $event)">
            </label>
          </div>
          <div class="col">
            <label class="h5 w-100" for="bookletback">Booklet Back:
              <input type="file" size="100" class="form-control text-center"
                @change="handleFileChange('bookletback', $event)">
            </label>
          </div>
        </div>
        <button type="submit" style="display: none;"></button> <!-- Hide the submit button -->
      </form>
    </div>
  </div>
</template>

<script>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { useRoute } from 'vue-router';

export default {
  components: {
    VueDatePicker,
  },
  props: {
    collectionId: {
      type: Number,
      required: true,
    },
    trackcount: {
      type: Number,
      validator: (value) => !isNaN(value) && value !== null && value !== undefined && value !== "",
      required: true,
      default: 0,
    },
    onChange: {
      type: Function,
      required: true,
    },
    record: {
      type: Object,
      default: () => ({})
    },
    newRecord: {
      type: Object,
      default: () => ({})
    },
    isEditMode: {
      type: Boolean,
      default: false
    },
  },
  setup(props, { emit }) {
    const form = ref({
      title: '',
      artist: '',
      format: '',
      trackcount: props.trackcount,
      label: '',
      country: '',
      releasedate: '',
      genre: '',
      price: 0.0,
      bookletfront: null,
      bookletback: null,
      grade: '',
    });

    const route = useRoute();
    const recordId = route.params.recordId;
    console.log('Record ID from route:', recordId);


    const dateFormat = 'yyyy-mm-dd';

    const handleFileChange = (field, event) => {
      form.value[field] = event.target.files[0];
    };

    const emitValue = () => {
      props.onChange(form.value.trackcount);
    };

    const fetchRecords = async () => {
      try {
        const response = await axios.get(`/api/collection/${props.collectionId}/record/${recordId}`, {
          headers: { 'Authorization': 'Bearer ' + localStorage.getItem('vue-token') },
        });
        const record = response.data;
        form.value.title = record.title || '';
        form.value.artist = record.artist || '';
        form.value.format = record.format || '';
        form.value.trackcount = record.trackcount || 0;
        form.value.label = record.label || '';
        form.value.country = record.country || '';
        form.value.releasedate = record.releasedate || '';
        form.value.genre = record.genre || '';
        form.value.price = record.price || 0.0;
        form.value.grade = record.grade || '';
        form.value.bookletfront = record.bookletfront || null;
        form.value.bookletback = record.bookletback || null;

        emitValue();
      } catch (error) {
        console.error("There was an error fetching the collections:", error);
      }
    };


    const submitForm = async () => {
      try {


        if (props.isEditMode) {
          const jsonData = {
          title: form.value.title,
          artist: form.value.artist,
          format: form.value.format,
          trackcount: form.value.trackcount,
          label: form.value.label,
          country: form.value.country,
          releasedate: form.value.releasedate,
          genre: form.value.genre,
          price: form.value.price,
          grade: form.value.grade,
        };

        
          await axios.put(`/api/collection/${props.collectionId}/record/${recordId}`, jsonData, {
            headers: {
              'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
              'Content-Type': 'application/json',
            },
          });
          console.log('Record updated successfully');
          
          // 2. Send files via POST
          const formData = new FormData();
          if (form.value.bookletfront) {
            formData.append('bookletfront', form.value.bookletfront);
          }
          if (form.value.bookletback) {
            formData.append('bookletback', form.value.bookletback);
          }

          if (formData.has('bookletfront') || formData.has('bookletback')) {
            await axios.post(`/api/collection/${props.collectionId}/record/${recordId}/upload-files`, formData, {
              headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
                'Content-Type': 'multipart/form-data',
              },
            });
            console.log('Files uploaded successfully');
          }
        } else {

          const formData = new FormData();

          // Append non-file fields
          formData.append('collection_id', props.collectionId);
          formData.append('title', form.value.title);
          formData.append('artist', form.value.artist);
          formData.append('format', form.value.format);
          formData.append('trackcount', form.value.trackcount);
          formData.append('label', form.value.label);
          formData.append('country', form.value.country);

          const formattedDate = new Date(form.value.releasedate).toISOString().split('T')[0];
          formData.append('releasedate', formattedDate);

          formData.append('genre', form.value.genre);

          const price = form.value.price !== null && form.value.price !== undefined ? form.value.price.toString() : '0';
          formData.append('price', price);

          formData.append('grade', form.value.grade);

          if (form.value.bookletfront) {
            formData.append('bookletfront', form.value.bookletfront);
          }
          if (form.value.bookletback) {
            formData.append('bookletback', form.value.bookletback);
          }
          // POST request to create a new record
          const response = await axios.post('/api/record', formData, {
            headers: {
              'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
              'Content-Type': 'multipart/form-data',
            },
          });

          // Emit the created record ID to the parent component
          const createdRecordId = response.data?.id;
          if (createdRecordId) {
            console.log('Record created successfully with ID:', createdRecordId);
            emit('submit-record', createdRecordId);
          } else {
            console.error('Record ID is missing in the response:', response);
          }
        }
      } catch (error) {
        console.error('Form submission failed:', error);
      }
    };

    onMounted(() => {
      if (props.isEditMode) {
        fetchRecords();
      }

    });

    watch(() => props.trackcount, (newValue) => {
      form.value.trackcount = newValue;
    });

    return {
      emitValue,
      form,
      dateFormat,
      handleFileChange,
      submitForm,
    };
  },
};
</script>

<style scoped>
/* Optionally, you can add custom styles here */
</style>
