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
                    <input type="text" class="form-control" v-model="form.title" required>
                </label>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col">
                <label class="h5 w-100" for="grade">Tracknummer:
                    <input type="text" class="form-control" v-model="form.number" required>
                </label>
            </div>
            <div class="col">
                <label class="h5 w-100" for="grade">Zeit:
                    <input type="text" class="form-control" v-model="form.time" required>
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
                     <input type="text" class="form-control" v-model="form.link" required>
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
    removeTrack: {
      type: Function,
      required: true,
    },
  },
  setup(props, { emit }) {
    const form = ref({
      artist: '',
      title: '',
      tracknumber: '',
      time: '',
      genre: '',
      link: '',
    });

    const handleRemoveTrack = () => {
      props.removeTrack(props.trackIndex);
    };

    const submitForm = async () => {
  const formData = new FormData();
  formData.append('artist', form.value.artist);
  formData.append('title', form.value.title);
  formData.append('tracknumber', form.value.tracknumber);
  formData.append('time', form.value.time);
  formData.append('genre', form.value.genre);
  formData.append('link', form.value.link);

  try {
    const response = await axios.post('/api/track', formData, {
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
        'Content-Type': 'multipart/form-data',
      },
    });
    console.log(`Track form submitted successfully for track ${props.trackIndex + 1}:`, response.data);
    emit('submit-track', formData, props.trackIndex);
  } catch (error) {
    console.error(`Track form submission failed for track ${props.trackIndex + 1}:`, error);
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