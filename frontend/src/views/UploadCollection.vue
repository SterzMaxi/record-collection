

<template>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>

  <h1>
    Upload Collections
  </h1>
  <CollectionUploadForm />
</template>

<script>
  import CollectionUploadForm from '../components/CollectionUploadForm.vue';
  
  export default {
    name: 'App',
    components: {
      CollectionUploadForm,
    },
  };
  </script>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>
