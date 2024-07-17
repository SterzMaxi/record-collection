<!-- src/components/UploadForm.vue -->

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
        <label for="tracknumber">Tracknumber:</label>
        <input type="text" v-model="form.tracknumber" required>
      </div>
      <div>
        <label for="tracktitle">Tracktitle:</label>
        <input type="text" v-model="form.tracktitle" required>
      </div>
      <div>
        <label for="tracktime">Tracktime:</label>
        <input type="text" v-model="form.tracktime" required>
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
        <label for="collectionname">Collectionname:</label>
        <input type="text" v-model="form.collectionname" required>
      </div>
      <div>
        <label for="price">Preis:</label>
        <input type="number" v-model="form.price" required>
      </div>
      <div>
        <label for="listenlink">Listenlink:</label>
        <input type="text" v-model="form.listenlink" required>
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
        tracknumber: '',
        tracktitle: '',
        label: '',
        country: '',
        releasedate: '',
        genre: '',
        collectionname: '',
        price: 0,
        bookletfront: null,
        bookletback: null,
        listenlink: '',
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
        const response = await axios.post('api/upload', formData, {
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
  