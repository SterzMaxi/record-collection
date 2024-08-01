import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router/router.js'
import createRouter from './router/router.js';
import authentication from './plugins/KeycloakPlugin.js'


import 'bootstrap/dist/css/bootstrap.css';

// Create the Vue app instance
const app = createApp(App);

// Use the authentication plugin
app.use(authentication);

const keycloak = app.config.globalProperties.$keycloak;

// Retrieve tokens from localStorage
const storedToken = localStorage.getItem("vue-token");
const storedRefreshToken = localStorage.getItem("vue-refresh-token");


// Initialize Keycloak and mount the app once initialization is complete
keycloak.init({ onLoad: 'check-sso',
  checkLoginIframe: false,
  token: storedToken,
  refreshToken: storedRefreshToken
 }).then(() => {
  if (keycloak.authenticated) {
    console.log("Authenticated");
    localStorage.setItem("vue-token", keycloak.token);
    localStorage.setItem("vue-refresh-token", keycloak.refreshToken);
}

keycloak.onTokenExpired = () => {
  keycloak.updateToken(30).then((refreshed) => {
    if (refreshed) {
      localStorage.setItem("vue-token", keycloak.token);
      localStorage.setItem("vue-refresh-token", keycloak.refreshToken);
    }
  }).catch(() => {
    console.error('Failed to refresh token');
  });
};

const router = createRouter(keycloak);


    // Use the router
    app.use(router);
    
    // Mount the app to the DOM
    app.mount('#app');
    console.log('keycloak initalization successfull');
  }).catch(error => {
    console.error('Keycloak initialization failed', error);
  });