<template>
  <h1>{{ msg }}</h1>

  <div class="card">
    <button type="button" @click="count++">You clicked {{ count }} times</button>
    <p>
      Edit <code>components/LandingPage.vue</code> to test HMR
    </p>
  </div>

  <div v-if="isAuthenticated">
    <p>You are authenticated</p>
    <button @click="logout">Logout</button>
  </div>
  <div v-else>
    <p>You are not authenticated</p>
    <button @click="login">Login</button>
  </div>

  <p>
    Check out
    <a href="https://vuejs.org/guide/quick-start.html#local" target="_blank">
      create-vue
    </a>
    , the official Vue + Vite starter
  </p>
  <p>
    Install
    <a href="https://github.com/vuejs/language-tools" target="_blank">Volar</a>
    in your IDE for a better DX
  </p>
  <p class="read-the-docs">Click on the Vite and Vue logos to learn more</p>
</template>

<script setup>
import { ref, computed, getCurrentInstance } from 'vue'
import keycloakplugin from '../plugins/KeycloakPlugin.js'

const props = defineProps({
  msg: String,
})

const count = ref(0)

const { proxy } = getCurrentInstance();
const keycloak = proxy.$keycloak;

const isAuthenticated = computed(() => keycloak.authenticated);

const login = () => {
  
  console.log('Keycloak instance:', keycloak);
  console.log('Keycloak login method:', typeof keycloak.login);
  keycloak.login({ redirectUri: window.location.origin });

};

const logout = () => {
  console.log('Keycloak login method:', typeof keycloak.logout);
  keycloak.logout({ redirectUri: window.location.origin });
};
</script>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>
