<template>
    <!doctype html>
  <html>
    <div>
      <nav class="navbar fixed-top navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid">
          <RouterLink to="/" class="navbar-brand mx-auto">Record Digging</RouterLink>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <form class="d-flex mx-auto" role="search">
                <input class="form-control me-2" type="search" placeholder="Suchen" aria-label="Search">
                <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
              </form>
              <ul class="navbar-nav mb-2 mb-lg-0">
                <div v-if="isAuthenticated">
                  <li class="nav-item dropdown">
                    <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                      {{ username }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                      <li><RouterLink to="/mycollections" class="dropdown-item">Meine Collections</RouterLink></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" type="button" @click="logout">Ausloggen</a></li>
                    </ul>
                  </li>
                </div>
                <div v-else>
                  <button @click="login">Einloggen</button>
                </div>
              </ul>
          </div>
        </div>
      </nav>
    </div>
  </html>
</template>

<script setup>
import { ref, computed, getCurrentInstance, onMounted } from 'vue'
import keycloakplugin from '../plugins/KeycloakPlugin.js'

const props = defineProps({
  msg: String,
})

const count = ref(0)
const username = ref('');

const { proxy } = getCurrentInstance();
const keycloak = proxy.$keycloak;

const isAuthenticated = computed(() => keycloak.authenticated);

const login = () => {
  keycloak.login({ redirectUri: window.location.origin });

};

const logout = () => {
  keycloak.logout({ redirectUri: window.location.origin }).then(() => {
    localStorage.removeItem("vue-token");
    localStorage.removeItem("vue-refresh-token");
  });
};

onMounted(() => {
  if(keycloak.authenticated) {
    username.value = keycloak.tokenParsed.preferred_username || keycloak.tokenParsed.email;
  }
});


</script>

<style>
/* Include Bootstrap Icons CSS */
@import "bootstrap-icons/font/bootstrap-icons.css";

.navbar-nav .dropdown-menu 
{
  position:absolute !important;
}


</style>