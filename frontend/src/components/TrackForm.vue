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
    import { ref } from 'vue';
    import axios from 'axios';
    
    export default {
  props: {
    recordId: {
      type: Number,
      required: true,
    },
    removeTrack: {
      type: Function,
      required: true,
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

    const handleRemoveTrack = () => {
      props.removeTrack(props.trackIndex);
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
      const response = await axios.post('/api/track', formData, {
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
          'Content-Type': 'multipart/form-data',
        },
      });
      console.log(`Track form submitted successfully for track:`, response.data);
      emit('submit-track', formData);
    } catch (error) {
      console.error(`Track form submission failed for track`, error);
    }
};

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