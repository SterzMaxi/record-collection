<template>
    <!doctype html>
    <html>
<div>
  <b-navbar fixed = "top" toggleable="lg" type="dark" variant="dark">
    <b-navbar-brand><RouterLink to="/">Record Digging</RouterLink></b-navbar-brand>
      <!-- Right aligned nav items -->
      <b-navbar-nav class="mx-auto">
        <b-nav-form>
          <b-form-input style="width: 30.33vw;" size="sm" class="mr-sm-2" placeholder="Suchen"></b-form-input>
          <b-button size="sm" class="my-2 my-sm-0" type="submit">
            <i class="bi bi-search"></i>
            </b-button>
        </b-nav-form>
        </b-navbar-nav>
        <b-navbar-nav>
            <div v-if="isAuthenticated">
          <b-nav-item-dropdown right>
          <!-- Using 'button-content' slot -->
          <template #button-content>
            <em class="text-white">{{ username }}</em>
          </template>
          <b-dropdown-item><RouterLink to="/mycollections">Meine Collections</RouterLink></b-dropdown-item>
          <b-dropdown-item @click="logout">Ausloggen</b-dropdown-item>
        </b-nav-item-dropdown>
        </div>
        <div v-else>
          <button @click="login">Einloggen</button>
        </div>
        </b-navbar-nav>
  </b-navbar>
</div>
</html>
</template>

<script setup>
import { ref, computed, getCurrentInstance, onMounted } from 'vue'
import keycloakplugin from '../plugins/KeycloakPlugin.js'
import {
  BNavbar,
  BNavbarNav,
  BNavbarBrand,
  BNavbarToggle,
  BCollapse,
  BNavItem,
  BNavForm,
  BFormInput,
  BButton,
  BNavItemDropdown,
  BDropdownItem
} from 'bootstrap-vue-3';

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