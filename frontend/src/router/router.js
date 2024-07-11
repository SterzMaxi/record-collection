import { createMemoryHistory, createRouter } from 'vue-router'
import { getCurrentInstance } from 'vue';
import Home from '../views/Home.vue'
import { keycloak } from '../plugins/KeycloakPlugin';


const router = createRouter({

  history: createMemoryHistory(),

  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
      meta: {
        isAuthenticated: false
      }
    },
    {
      path: '/secured',
      name: 'Secured',
      meta: {
        isAuthenticated: true
      },
      component: () => import('../views/Secured.vue')
    },
    {
      path: '/upload',
      name: 'Upload',
      meta: {
        isAuthenticated: true
      },
      component: () => import('../views/Upload.vue')
    },
    {
      path: '/unauthorized',
      name: 'Unauthorized',
      meta: {
        isAuthenticated: false
      },
      component: () => import('../views/Unauthorized.vue')
    }
  ]

})

router.beforeEach((to, from, next) => {
  if (to.meta.isAuthenticated) {
    // Get the actual url of the app, it's needed for Keycloak
    const basePath = window.location.toString();

    if (keycloak.authenticated) {
      next(); // The user is authenticated, proceed to the route
    } else {
      // The page is protected and the user is not authenticated. Force a login.
      keycloak.login({ redirectUri: basePath.slice(0, -1) + to.path });
    }
  } else {
    // This page did not require authentication
    next();
  }
});

export default router;