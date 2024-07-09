<template>
    <!doctype html>
    <html>
<div>
  <b-navbar fixed = "top" toggleable="lg" type="dark" variant="info">
    <b-navbar-brand href="#">RECORD DIGGING</b-navbar-brand>

    <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

    <b-collapse id="nav-collapse" is-nav>
      <!-- Right aligned nav items -->
      <b-navbar-nav class="mx-auto">
        <b-nav-form>
          <b-form-input size="sm" class="mr-sm-2" placeholder="Suchen"></b-form-input>
          <b-button size="sm" class="my-2 my-sm-0" type="submit">Suchen</b-button>
        </b-nav-form>
        </b-navbar-nav>
        <b-navbar-nav>
            <div v-if="isAuthenticated">
          <b-nav-item-dropdown right>
          <!-- Using 'button-content' slot -->
          <template #button-content>
            <em>{{ username }}</em>
          </template>
          <b-dropdown-item href="#">Profile</b-dropdown-item>
          <b-dropdown-item @click="logout">Sign Out</b-dropdown-item>
        </b-nav-item-dropdown>
        </div>
        <div v-else>
          <button @click="login">Login</button>
        </div>
        </b-navbar-nav>
        
        
        

    </b-collapse>
  </b-navbar>
</div>
</html>
</template>

<script setup>
import { ref, computed, getCurrentInstance, onMounted } from 'vue'
import keycloakplugin from '../plugins/KeycloakPlugin.js'
import { BNavbar, BNavbarNav, BNavbarBrand, BNavbarToggle, BCollapse, BNavItem, BNavForm, BFormInput, BButton, BNavItemDropdown, BDropdownItem } from 'bootstrap-vue-3';

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
  keycloak.logout({ redirectUri: window.location.origin });
};

onMounted(() => {
  if(keycloak.authenticated) {
    username.value = keycloak.tokenParsed.preferred_username || keycloak.tokenParsed.email;
  }
});
</script>