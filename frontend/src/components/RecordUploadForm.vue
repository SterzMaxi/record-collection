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
              <input type="number" class="form-control text-center" v-model.number="form.trackcount" @input="validateTrackcount"
                :max="50" :min="1" required />
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
              <VueDatePicker v-model="form.releasedate" :format="dateFormat" :bootstrap-styles="true" year-picker required />
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
              <input type="file" size="100" class="form-control text-center" accept=".jpg,.jpeg,.png"
                @change="openCropperModal('bookletfront', $event)">
            </label>
            <img v-if="thumbnails.bookletfront" :src="thumbnails.bookletfront" alt="Booklet Back Preview"
              class="img-thumbnail mt-2" style="max-width: 250px; max-height: 250px;">
          </div>
          <div class="col">
            <label class="h5 w-100" for="bookletback">Booklet Back:
              <input type="file" ref="bookletbackInput" size="100" class="form-control text-center"
                accept=".jpg,.jpeg,.png" @change="openCropperModal('bookletback', $event)">
            </label>
            <img v-if="thumbnails.bookletback" :src="thumbnails.bookletback" alt="Booklet Back Preview"
              class="img-thumbnail mt-2" style="max-width: 250px; max-height: 250px;"
              @click="triggerFileInput('bookletbackInput')">
          </div>
        </div>
        <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="cropperModalLabel">Bild zuschneiden</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div>
                  <img ref="image" class="img-fluid img-cropping" alt="Crop Image">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                <button type="button" class="btn btn-primary" @click="cropImage">zuschneiden</button>
              </div>
            </div>
          </div>
        </div>
        <button type="submit" style="display: none;"></button> <!-- Hide the submit button -->
      </form>
    </div>
  </div>
</template>

<script>
import { ref, watch, onMounted } from 'vue';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';
import axios from 'axios';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { useRoute } from 'vue-router';
import { Modal } from 'bootstrap';

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
      default: 1,
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
      trackcount: props.trackcount > 0 ? props.trackcount : 1,
      label: '',
      country: '',
      releasedate: '',
      genre: '',
      price: 0.0,
      bookletfront: null,
      bookletback: null,
      grade: '',
    });

    const trackcount = ref(props.trackcount > 0 ? props.trackcount : 1);

    const thumbnails = ref({
      bookletfront: null,
      bookletback: null,
    });

    const cropper = ref(null);
    const selectedField = ref('');

    const route = useRoute();
    const recordId = route.params.recordId;
    console.log('Record ID from route:', recordId);


    const dateFormat = 'yyyy';

    const triggerFileInput = (refName) => {
      const fileInput = document.querySelector(`input[ref=${refName}]`); // Get file input element by ref
      if (fileInput) {
        fileInput.click(); // Trigger click event
      }
    };

    const openCropperModal = (field, event) => {
      selectedField.value = field;
      const file = event.target.files[0];

      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          const imageElement = document.querySelector('#cropperModal img');
          imageElement.src = e.target.result;

          const cropperModalElement = document.getElementById('cropperModal');
          const cropperModal = new Modal(cropperModalElement);
          cropperModal.show();

          cropperModalElement.addEventListener('shown.bs.modal', () => {
            
            cropper.value = new Cropper(imageElement, {
              aspectRatio: 1,
              viewMode: 1,
              minCropBoxWidth: 100, // Minimum width of the crop box
              minCropBoxHeight: 100, // Minimum height of the crop box
              maxCropBoxWidth: 500, // Maximum width of the crop box
              maxCropBoxHeight: 500, // Maximum height of the crop box
              cropBoxResizable: true, // Allows user to resize the crop box
              autoCropArea: 0.5,
            });
          }, { once: true })

        };
        reader.readAsDataURL(file);
      }
    };

    const cropImage = () => {
      if (cropper.value) {
        const canvas = cropper.value.getCroppedCanvas({
          width: 600,
          height: 600,
        });

        canvas.toBlob((blob) => {
          // Create a URL for the blob and display it as a thumbnail
          thumbnails.value[selectedField.value] = URL.createObjectURL(blob);
          form.value[selectedField.value] = blob;

          // Hide the modal
          const cropperModal = Modal.getInstance(document.getElementById('cropperModal'));
          cropperModal.hide();

          // Destroy the cropper instance
          cropper.value.destroy();
          cropper.value = null;
        });
      }
    };

    const handleFileChange = (field, event) => {
      const file = event.target.files[0];
      form.value[field] = file;

      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          thumbnails.value[field] = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        thumbnails.value[field] = null; // Reset the thumbnail if no file is selected
      }
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

        if (record.bookletfront) {
          thumbnails.value.bookletfront = record.bookletfront;
        }
        if (record.bookletback) {
          thumbnails.value.bookletback = record.bookletback;
        }

        emitValue();
      } catch (error) {
        console.error("There was an error fetching the collections:", error);
      }
    };

    const validateTrackcount = () => {
      // Ensure positive track count
      if (form.value.trackcount < 1 || isNaN(form.value.trackcount)) {
        form.value.trackcount = 1; // Reset to 1 if invalid
      }
      emitValue();
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

    watch(
      () => props.isEditMode,
      (newValue) => {
        if (newValue) {
          fetchRecords();
        }
      }
    );

    watch(
      () => form.value.trackcount,
      (newValue) => {
        // Automatically correct to positive value
        if (newValue < 1 || isNaN(newValue)) {
          form.value.trackcount = 1;
        }
        console.log('Emitting trackcount:', form.value.trackcount);
        emitValue();
        //props.onChange(form.value.trackcount);
      }
    );

    watch(
  () => props.trackcount,
  (newValue) => {
    console.log('Trackcount prop updated in child:', newValue);
    form.value.trackcount = newValue; // Update local form state
  }
);

    onMounted(() => {
      if (props.isEditMode) {
        fetchRecords();
      }

    });


    return {
      emitValue,
      form,
      openCropperModal,
      cropImage,
      thumbnails,
      dateFormat,
      handleFileChange,
      submitForm,
      triggerFileInput,
      validateTrackcount,
    };
  },
};
</script>

<style scoped>
img {
  max-width: 100%;
  max-height: 100%;
}

.modal.show .modal-dialog {
  height: 100% !important;
}
</style>
