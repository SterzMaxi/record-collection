

<template>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>

  <h1>
    Upload
  </h1>
  <UploadForm />
</template>

<script>
  import UploadForm from '../components/UploadForm.vue';
  
  export default {
    name: 'App',
    components: {
      UploadForm,
    },
  };
  </script>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>
