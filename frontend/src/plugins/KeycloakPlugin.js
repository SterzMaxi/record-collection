import { createApp } from 'vue'
import Keycloak from 'keycloak-js';

const keycloakInitOptions = {
    url: import.meta.env.VITE_KEYCLOAK_URL,
    clientId: import.meta.env.VITE_KEYCLOAK_CLIENT_ID,
    realm: import.meta.env.VITE_KEYCLOAK_REALM,
    onLoad: 'check-sso'
}

const keycloak = new Keycloak(keycloakInitOptions);

const Plugin = {
  install(app) {
    app.config.globalProperties.$keycloak = keycloak
  }
}

export default Plugin