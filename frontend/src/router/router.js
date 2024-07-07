import { createMemoryHistory, createRouter } from 'vue-router'
import { getCurrentInstance } from 'vue';
import Home from '../views/Home.vue'

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

    if (!app.$keycloak.authenticated) {
      // The page is protected and the user is not authenticated. Force a login.
      app.$keycloak.login({ redirectUri: basePath.slice(0, -1) + to.path });
    
    } else {
      // The user was authenticated, but did not have the correct role
      // Redirect to an error page
      next({ name: 'Unauthorized' });
    }
  } else {
    // This page did not require authentication
    next();
  }
});

export default router;