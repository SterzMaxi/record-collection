<template>
  <div class="container-fluid p-0">

    <button @click="handleRemoveTrack" class="btn-close position-absolute top-0 end-0 m-2" aria-label="Close"></button>
    <form @submit.prevent="submitForm">
      <div class="row mt-4">
        <div class="col">
          <label class="h5 w-100" for="price">Artist:
            <input type="text" class="form-control" v-model="form.artist" required>
          </label>
        </div>
        <div class="col">
          <label class="h5 w-100" for="price">Titel:
            <input type="text" class="form-control" v-model="form.tracktitle" required>
          </label>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col">
          <label class="h5 w-100" for="grade">Tracknummer:
            <input type="text" class="form-control" v-model="form.tracknumber" required>
          </label>
        </div>
        <div class="col">
          <label class="h5 w-100" for="grade">Zeit:
            <input type="text" class="form-control" v-model="form.tracktime" required>
          </label>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col">
          <label class="h5 w-100" for="price">Genre:
            <input type="text" class="form-control" v-model="form.genre" required>
          </label>
        </div>
        <div class="col">
          <label class="h5 w-100" for="grade">Link:
            <input type="text" class="form-control" v-model="form.listenlink" required>
          </label>
        </div>
      </div>
      <button type="submit" style="display: none;"></button> <!-- Hide the submit button -->
    </form>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

export default {
  props: {
    recordId: {
      type: Number,
      required: true,
    },
    collectionId: {
      type: Number,
      required: true,
    },
    trackId: {
      type: Number,
      required: true,
    },
    removeTrack: {
      type: Function,
      required: true,
    },
    isEditMode: {
      type: Boolean,
      default: false
    },
  },
  setup(props, { emit }) {
    const form = ref({
      artist: '',
      tracktitle: '',
      tracknumber: '',
      tracktime: '',
      genre: '',
      listenlink: '',
    });

    const route = useRoute();
    const recordId = route.params.recordId;

    const handleRemoveTrack = () => {
      props.removeTrack(props.trackIndex);
    };

    const fetchTracks = async () => {
      try {
        const response = await axios.get(`/api/collection/${props.collectionId}/record/${recordId}/track/${props.trackId}`, {
          headers: { 'Authorization': 'Bearer ' + localStorage.getItem('vue-token') },
        });
        const track = response.data;
        form.value.artist = track.artist || '';
        form.value.tracktitle = track.tracktitle || '';
        form.value.tracknumber = track.tracknumber || 0;
        form.value.tracktime = track.tracktime || 0;
        form.value.genre = track.genre || '';
        form.value.listenlink = track.listenlink || '';
      } catch (error) {
        console.error("There was an error fetching the tracks:", error);
      }
    };

    const submitForm = async () => {
      const formData = new FormData();
      formData.append('record_id', props.recordId);
      formData.append('artist', form.value.artist);
      formData.append('tracktitle', form.value.tracktitle);
      formData.append('tracknumber', form.value.tracknumber);
      formData.append('tracktime', form.value.tracktime);
      formData.append('genre', form.value.genre);
      formData.append('listenlink', form.value.listenlink);

      console.log('Form Data:', {
        record_id: props.recordId,
        artist: form.value.artist,
        tracktitle: form.value.tracktitle,
        tracknumber: form.value.tracknumber,
        tracktime: form.value.tracktime,
        genre: form.value.genre,
        listenlink: form.value.listenlink
      });

      try {
        if (props.isEditMode) {
          const response = await axios.put(`/api/collection/${props.collectionId}/record/${recordId}/track/${props.trackId}`, {

            artist: form.value.artist,
            tracktitle: form.value.tracktitle,
            tracknumber: form.value.tracknumber,
            tracktime: form.value.tracktime,
            genre: form.value.genre,
            listenlink: form.value.listenlink
          },
            {
              headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
              },

            });
          console.log(`Track updated successfully for track:`, response.data);
          emit('submit-track', formData);
        }
        else {
          const response = await axios.post('/api/track', formData, {
            headers: {
              'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
              'Content-Type': 'multipart/form-data',
            },
          });
          console.log(`Track form submitted successfully for track:`, response.data);
          emit('submit-track', formData);
        }

      } catch (error) {
        console.error(`Track form submission failed for track`, error);
      }
    };

    onMounted(() => {
      if (props.isEditMode) {
        fetchTracks();
      }

    });

    return {
      form,
      handleRemoveTrack,
      submitForm,
    };
  },
};
</script>

<style scoped>
.btn-close {
  position: absolute;
  top: 0;
  right: 0;
  margin: 1rem;
}
</style>