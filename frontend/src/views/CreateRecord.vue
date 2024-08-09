<template>
      <div>
        <h1 class="mt-5">
        Erstelle Record
      </h1>
      </div>
      
      <RecordUploadForm 
        :collectionId="collectionId"
        @submit-record="handleRecordSubmit"
        ref="recordUploadForm"
        :trackcount="trackcount || 0" 
        :onChange="updateTrackCount"
      />
      
      <h1 class="mt-5">Tracks</h1>
      <div class="card w-100 p-3">
        <button class="w-0 btn btn-outline-dark mt-3 mb-3" @click="addTrack"><i class="bi bi-plus-circle-fill" style="font-size: 30px;"></i></button>
        
        <div v-if="tracks.length > 0">
            <div v-for="(track, index) in tracks" :key="track.id">
                <div class="card w-100 p-3 mt-3">
                    <h3>Track {{ index + 1 }}</h3>
                    <TrackForm 
                      :recordId="recordId"
                      ref="trackForms"
                      :trackIndex="index"
                      :removeTrack="removeTrack" 
                    />
                </div>
            </div>
        </div>
    </div>
      <button type="submit" class="btn btn-primary mt-5" @click="submitAllForms">Erstellen</button>
    </template>
<script>
import { ref } from 'vue';
import axios from 'axios';   
import RecordUploadForm from '../components/RecordUploadForm.vue';
import TrackForm from '../components/TrackForm.vue';    
    
export default {
  name: 'CreateRecord',
  components: {
    RecordUploadForm,
    TrackForm,
  },
  props: {
    collectionId: {
      type: Number,
      required: true
    }
  },
  setup(props) {
    const recordUploadForm = ref(null);
    const recordId = ref(0);
    const trackForms = ref([]);

    const trackcount = ref(0); // Initialize trackcount
    const tracks = ref([]);
    const recordFormData = ref(null);
    const trackFormsData = ref([]);

    const handleRecordSubmit = (id) => {
      console.log('Record ID received from child:', id);
      recordId.value = id; // Store the recordId received from the child component
    };

    const updateTrackCount = (newCount) => {
        if(newCount > 50)
        {
            newCount = 50;
        }
      trackcount.value = newCount;
      adjustTrackList();
    };

    const addTrack = () => {
        if(tracks.value.length < 50) {
            tracks.value.push({ id: Date.now() });
            trackcount.value = tracks.value.length;
        }
    };

    const adjustTrackList = () => {
      while (tracks.value.length < trackcount.value) {
        if(tracks.value.length < 50) {
            tracks.value.push({ id: Date.now() });
        }
      }
      while (tracks.value.length > trackcount.value) {
        tracks.value.pop();
      }
    };

    const removeTrack = (index) => {
      tracks.value.splice(index, 1);
      trackcount.value = tracks.value.length;
      trackFormsData.value.splice(index, 1);
    };

    /*
    const handleRecordSubmit = (response) => {
      console.log('Record form data received:', formData);
      recordId = response.data.id;
    };

    const handleTrackSubmit = (formData, trackIndex) => {
      trackFormsData.value[trackIndex] = formData;
      console.log(`Track form data received for track ${trackIndex + 1}:`, formData);
    };
*/
    const submitAllForms = async () => {
      // Submit record form
      if (recordUploadForm.value) {
        await recordUploadForm.value.submitForm(); // Call submitForm method on RecordUploadForm
      }

      // Submit track forms
      trackForms.value.forEach((trackForm, index) => {
        if (trackForm) {
          trackForm.submitForm(recordId.value); // Call submitForm method on TrackForm
        }
      });
    };

    return {
      recordUploadForm,
      trackForms,
      trackcount,
      tracks,
      updateTrackCount,
      addTrack,
      adjustTrackList,
      removeTrack,
      submitAllForms,
      handleRecordSubmit,
      recordId,
    };
  },
};
    </script>
    
    <style scoped>
    .read-the-docs {
      color: #888;
    }
    </style>
    