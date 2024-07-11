<script setup>
import Secured from './views/Secured.vue'
import Home from './views/Home.vue';
import Upload from './views/Upload.vue';
import Header from "./components/Header.vue"
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <Header></Header>

  <RouterView />
  
</template>

<script>

export default {
  name: 'App',
  components: {
    Home,
    Upload
  }
};
</script>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
