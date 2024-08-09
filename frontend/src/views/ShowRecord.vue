<template>
    <div class="container-fluid p-0">
      <div class="row">
          <DisplayRecord 
          :recordId="recordId"
          :collectionId="collectionId"
          class="col"
          />
          <DisplayTracks 
            :recordId="recordId"
            :collectionId="collectionId"
            class="col"
            />
          
      </div>
    </div>
    </template>
      <script>
      import DisplayRecord from '../components/DisplayRecord.vue';
      import DisplayTracks from '../components/DisplayTracks.vue';
    
      export default {
        name: 'ShowRecord',
        components: {
          DisplayRecord,
          DisplayTracks,
        },
        props: {
            recordId: {
                type: Number,
                required: true,
            },
            collectionId: {
                type: Number,
                required: true,
            },
        },

  mounted() {
    console.log('Collection ID:', this.collectionId);
    console.log('Record ID:', this.recordId);
  },


      };
    </script>
    
    <style scoped>
    .read-the-docs {
      color: #888;
    }
    </style>
    