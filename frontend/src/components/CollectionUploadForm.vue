<template>
    <div class="container-fluid p-0">
      <b-card class="w-100 m-0 p-3">
        <b-button v-b-toggle.collection-collapse variant="outline-dark">Erstelle Collection</b-button>
        <b-collapse id ="collection-collapse" class="mt-2">
          <form @submit.prevent="submitForm">
            <div>
              <label class="h3" for="collectionname">Collection Name:</label>
              <input type="text" v-model="form.collectionname" class="form-control" required>
            </div>
            <div>
              <label class="h5" for="style">Style:</label>
              <input type="text" v-model="form.style" class="form-control" required>
            </div>

            <b-button class="mt-4" v-b-toggle.collection-collapse type="submit" variant="success">Erstellen</b-button>
          </form>
        </b-collapse>
          
        </b-card>
      
    </div>
  </template>
  
  <script setup>
  import axios from 'axios';

  </script>

  <script>
  
  export default {
    data() {
    return {
      form: {
        collectionname: '',
        style: '',
      },
    };
  },
  methods: {
    async submitForm() {
      const formData = new FormData();
      Object.keys(this.form).forEach(key => {
          if (this.form[key]) {
            formData.append(key, this.form[key]);
          }
      });

      try {
        const response = await axios.post('/api/collection', formData, {
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
            'Content-Type': 'multipart/form-data'
          },
        });
        console.log("axios post success");
        console.log(response.data);
      } catch (error) {
        console.error('Upload failed', error);
      }
    }
  }
};
</script>
  

<style scoped>
.container-fluid {
  padding: 0 !important; /* Remove any padding to make the card truly full width */
}

.card {
  width: 80vw !important; /* Full viewport width */
  max-width: 100% !important; /* Ensure no maximum width is set */
  margin: 0 !important;
  padding: 20px !important; /* Adjust padding as necessary */
  box-sizing: border-box;
}
</style>