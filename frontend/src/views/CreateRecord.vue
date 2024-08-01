

<template>
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      </head>
      <div>
        <h1 class="mt-5">
        Erstelle Record
      </h1>
      </div>
      
      <RecordUploadForm :trackcount="trackcount" @update-value="updateTrackCount" />
      
      <h1 class="mt-5">Tracks</h1>
      <div class="card w-100 p-3">
        <button class="w-0 btn btn-outline-dark mt-3 mb-3" @click="addTrack"><i class="bi bi-plus-circle-fill" style="font-size: 30px;"></i></button>
        
        <div v-if="tracks.length > 0">
            <div v-for="(track, index) in tracks" :key="track.id">
                <div class="card w-100 p-3 mt-3">
                    <h3>Track {{ index + 1 }}</h3>
                    <TrackForm @remove-track="removeTrack(index)"/>
                </div>
            </div>
        </div>
    </div>
      <button type="submit" class="btn btn-primary mt-5" @click="submitForm">Erstellen</button>
    </template>
      <script>
        import RecordUploadForm from '../components/RecordUploadForm.vue';
        import TrackForm from '../components/TrackForm.vue';    
    
      export default {
        name: 'App',
        components: {
            RecordUploadForm,
            TrackForm,
        },
        data() {
            return {
                trackcount:0,
                tracks: [],
            };
        },
        methods: {
            updateTrackCount(newCount) {
                this.trackcount = newCount;
                this.adjustTrackList();
            },
            addTrack() {
                this.tracks.push({id: Date.now()});
                this.trackcount = this.tracks.length;
            },
            adjustTrackList() {
                while (this.tracks.length < this.trackcount) {
                    this.addTrack();
                }
                while (this.tracks.length > this.trackcount) {
                    this.tracks.pop();
                }
            },
            removeTrack(index) {
                this.tracks.splice(index, 1);
                this.trackcount = this.tracks.length;
            },
            submitForm() {
                this.$refs.RecordUploadForm.submitForm();
            },
        },
        watch: {
            trackcount(newValue){
                this.adjustTrackList();
            },
        },
      };
    </script>
    
    <style scoped>
    .read-the-docs {
      color: #888;
    }
    </style>
    