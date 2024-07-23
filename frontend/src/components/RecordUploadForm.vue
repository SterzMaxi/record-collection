<template>
    <div>
      <form @submit.prevent="submitForm">
      <div>
        <label for="title">Title:</label>
        <input type="text" v-model="form.title" required>
      </div>
      <div>
        <label for="artist">Artist:</label>
        <input type="text" v-model="form.artist" required>
      </div>
      <div>
        <label for="format">Format:</label>
        <input type="text" v-model="form.format" required>
      </div>
      <div>
        <label for="trackcount">Track Anzahl:</label>
        <input type="int" v-model="form.trackcount" required>
      </div>
      <div>
        <label for="label">Label:</label>
        <input type="text" v-model="form.label" required>
      </div>
      <div>
        <label for="country">Land:</label>
        <input type="text" v-model="form.country" required>
      </div>
      <div>
        <label for="releasedate">Erscheinungsdatum:</label>
        <input type="date" v-model="form.releasedate" required>
      </div>
      <div>
        <label for="genre">Genre:</label>
        <input type="text" v-model="form.genre" required>
      </div>
      <div>
        <label for="price">Preis:</label>
        <input type="number" v-model="form.price" required>
      </div>
      <div>
        <label for="grade">Kondition:</label>
        <input type="text" v-model="form.grade" required>
      </div>

      <div>
        <label for="bookletfront">Booklet Front:</label>
        <input type="file" @change="handleFileChange('bookletfront', $event)">
      </div>
      <div>
        <label for="bookletback">Booklet Back:</label>
        <input type="file" @change="handleFileChange('bookletback', $event)">
      </div>

      <button type="submit">Submit</button>
    </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
    return {
      form: {
        title: '',
        artist: '',
        format: '',
        trackcount: 0,
        label: '',
        country: '',
        releasedate: '',
        genre: '',
        price: 0,
        bookletfront: null,
        bookletback: null,
        grade: '',
      },
    };
  },
  methods: {
    handleFileChange(field, event) {
      this.form[field] = event.target.files[0];
    },
    async submitForm() {
      const formData = new FormData();
      Object.keys(this.form).forEach(key => {
        if (key === 'bookletfront' || key === 'bookletback') {
          if (this.form[key]) {
            formData.append(key, this.form[key]);
          }
        } else {
          formData.append(key, this.form[key]);
        }
      });

      try {
        const response = await axios.post('api/record', formData, {
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
  