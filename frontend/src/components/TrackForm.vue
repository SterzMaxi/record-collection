<template>
    <div class="container-fluid p-0">
      
        <button @click="removeTrack" class="btn-close position-absolute top-0 end-0 m-2" aria-label="Close"></button>
        <form>
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
        </form>
    </div>
    </template>
    
    <script>
    import axios from 'axios';
    
    export default {
      data() {
      return {
        form: {
          artist: '',
          title: '',
          tracknumber: '',
          time: '',
          genre: '',
          link: '',
        },
      };
    },
    methods: {
        removeTrack() {
            this.$emit("remove-track");
        },
        async submitForm() {
            const formData = new FormData();
            Object.keys(form.value).forEach(key => {
                if (form.value[key]) {
                formData.append(key, form.value[key]);
                }
            });

            try {
                const response = await axios.post('/api/track', formData, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
                    'Content-Type': 'multipart/form-data',
                },
                });
                console.log("axios post success");
                console.log(response.data);
                await fetchCollections(); // Refresh collections list after submission
            } catch (error) {
                console.error('Upload failed', error);
            }
        }
    }
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