<template>
  <div>
    <h1 class="mt-5">
      Bearbeite Record
    </h1>
  </div>

  <RecordUploadForm :collectionId="collectionId" :recordId="recordId" @submit-record="handleRecordSubmit"
    ref="recordUploadForm" :trackcount="trackcount || 0" :onChange="updateTrackCount" :isEditMode="true" />

  <h1 class="mt-5">Tracks</h1>
  <div class="card w-100 p-3">
    <button class="w-0 btn btn-outline-dark mt-3 mb-3" @click="addTrack"><i class="bi bi-plus-circle-fill"
        style="font-size: 30px;"></i></button>

    <div v-if="tracks.length > 0">
      <div v-for="(track, index) in tracks" :key="track.id">
        <div class="card w-100 p-3 mt-3">
          <h3>Track {{ index + 1 }}</h3>
          <!---->
          {{ track.id }}
          <TrackForm :collectionId="collectionId" :recordId="recordId" :trackId="track.id" ref="trackForms"
            :trackIndex="index" :removeTrack="removeTrack" :isEditMode="true" />

        </div>
      </div>
    </div>
  </div>
  <button type="submit" class="btn btn-primary mt-5" @click="submitAllForms">Bearbeiten</button>
</template>
<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router'
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
    },
    recordId: {
      type: Number,
      required: true,
      default: 0,
    }
  },
  setup(props) {
    const router = useRouter();


    const recordUploadForm = ref(null);
    const recordId = ref(0);
    const trackForms = ref([]);

    const trackcount = ref(0);
    const tracks = ref([]);
    const recordFormData = ref(null);
    const trackFormsData = ref([]);

    const fetchTracks = async () => {
      try {
        const response = await axios.get(`/api/collection/${props.collectionId}/record/${props.recordId}/tracks`, {
          headers: { 'Authorization': 'Bearer ' + localStorage.getItem('vue-token') },
        });
        tracks.value = response.data;
        trackcount.value = tracks.value.length;
      } catch (error) {
        console.error("There was an error fetching the tracks:", error);
      }
    };

    const handleRecordSubmit = (id) => {
      recordId.value = id;
    };

    const updateTrackCount = (newCount) => {
      if (newCount > 50) newCount = 50;
      trackcount.value = newCount;
      adjustTrackList();
    };

    const addTrack = () => {
      if (tracks.value.length < 50) {
        tracks.value.push({ id: Date.now() });
        trackcount.value = tracks.value.length;
      }
    };

    const adjustTrackList = () => {
      while (tracks.value.length > trackcount.value) {
        tracks.value.pop();
      }
    };

    const removeTrack = async (index) => {
      const trackId = tracks.value[index].id;
      try {
        await axios.delete(`/api/collection/${props.collectionId}/record/${recordId.value}/track/${trackId}`, {
          headers: { 'Authorization': 'Bearer ' + localStorage.getItem('vue-token') },
        });
        tracks.value.splice(index, 1);
        trackcount.value = tracks.value.length;
      } catch (error) {
        console.error("There was an error removing the track:", error);
      }
    };

    const submitAllForms = async () => {
      try {
        if (recordUploadForm.value) {
          await recordUploadForm.value.submitForm(); // Submit the record form
        }

        await Promise.all(trackForms.value.map(async (trackForm, index) => {
          if (trackForm) {
            await trackForm.submitForm(); // Submit each track form
          }
        }));

        router.push({ name: 'MyCollections' }); // Navigate to MyCollections after submission
      } catch (error) {
        console.error('Error submitting forms:', error);
      }
    };

    onMounted(fetchTracks);

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