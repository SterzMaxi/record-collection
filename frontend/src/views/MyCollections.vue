

<template>
  <!doctype html>
  <html>
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
    <div>
      <h1 class="mt-5">
      Meine Collections
    </h1>
    </div>
    
    <div>
      <CollectionShow />
    </div>
    </html>
  </template>
    <script>
    import CollectionUploadForm from '../components/CollectionUploadForm.vue';
    import CollectionShow from '../components/CollectionShow.vue';
  
    export default {
      name: 'App',
      components: {
        CollectionUploadForm,
        CollectionShow,
      },
    };
  </script>
  
  <style scoped>
  .read-the-docs {
    color: #888;
  }
  </style>
  