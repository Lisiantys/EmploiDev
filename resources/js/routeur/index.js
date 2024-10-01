import { createRouter, createWebHistory } from 'vue-router';
import home from '../components/Home.vue';
import notFound from '../components/404.vue';
import login from '../components/Login.vue';
import register from '../components/Register.vue';
import job from '../components/JobsOffers.vue';
import developerProfile from '../components/DeveloperProfile.vue';
import { useAuthStore } from '../stores/authStore';


const routes = [
  { path: '/', name: 'home', component: home },
  { path: '/:pathMatch(.*)*', component: notFound },
  { path: '/connexion', name: 'login', component: login },
  { path: '/inscription', name: 'register', component: register },
  { path: '/offres-emploi', name: 'job', component: job },
  { path: '/profil', name: 'developerProfile', component: developerProfile },
  { 
    path: '/logout', 
    name: 'logout', 
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      authStore.logout();
      next({ name: 'home' }); // Redirige vers la page d'accueil après déconnexion
    },
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
