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
              <input
              type="number"
              class="form-control text-center"
              v-model.number="form.trackcount"
              @input="emitValue"
              required
              />
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
              <input type="date" size="100" class="form-control text-center" v-model="form.releasedate" required>
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
              <input type="number" size="100" class="form-control text-center" v-model="form.price" required>
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
              <input type="file" size="100" class="form-control text-center" @change="handleFileChange('bookletfront', $event)">
            </label>
          </div>
          <div class="col">
            <label class="h5 w-100" for="bookletback">Booklet Back:
              <input type="file" size="100" class="form-control text-center" @change="handleFileChange('bookletback', $event)">
            </label>
          </div>
        </div>
    </form>
    </div>
  </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    props: {
      trackcount: {
        type: Number,
        required: true,
      },
    },
    data() {
    return {
      form: {
        title: '',
        artist: '',
        format: '',
        trackcount: this.trackcount,
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
  watch: {
    trackcount(newValue) {
      this.form.trackcount = newValue;
    },
  },
  methods: {
    emitValue() {
      this.$emit('update-value', this.form.trackcount);
    },
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
  

<style scoped>


</style>
