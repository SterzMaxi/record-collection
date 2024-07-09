import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router/router.js'
import authentication from './plugins/KeycloakPlugin.js'
import BootstrapVue from 'bootstrap-vue-3';


import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css';

// Create the Vue app instance
const app = createApp(App);

// Use the authentication plugin
app.use(authentication);

const keycloak = app.config.globalProperties.$keycloak;


// Initialize Keycloak and mount the app once initialization is complete
keycloak.init({ checkLoginIframe: false }).then(() => {
    
    // Use the router
    app.use(router);
    
    // Mount the app to the DOM
    app.mount('#app');
    console.log('keycloak initalization successfull');
  }).catch(error => {
    console.error('Keycloak initialization failed', error);
  });