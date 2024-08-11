<template>
  <div class="mt-3">
    <div>
      <h5 class="text-start">Tracks:</h5>
      <ul class="list-group list-group-flush">
        <li v-for="track in tracks.value" :key="track.id" class="list-group-item ps-0 m-0">
          <div>
            <div class="row">
              <div class="col-md-8 text-start">
                {{ track.tracknumber }}: <strong>{{ track.tracktitle }}</strong> - {{ track.artist }}
              </div>
              <div class="col text-end">
                Dauer: {{ track.tracktime }}
              </div>
              <div class="col text-end pe-0">
                <a :href="track.listenlink" target="_blank">Anhören</a>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'DisplayTracks',
  props: {
    collectionId: {
      type: Number,
      required: true
    },
    recordId: {
      type: Number,
      required: true
    }
  },
  setup(props) {
    const loading = ref(true);
    const error = ref(null);
    const tracks = reactive([]);

    const fetchTracks = async () => {
      try {
        const response = await axios.get(`/api/collection/${props.collectionId}/record/${props.recordId}/tracks`);
        tracks.value = response.data;
        console.log('Fetched tracks:', tracks.value);
      } catch (error) {
        console.error('Failed to fetch record:', error);
        error.value = 'Failed to fetch record';
      }
    };

    onMounted(fetchTracks);

    return {
      loading,
      error,
      tracks
    };
  }
};
</script>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>