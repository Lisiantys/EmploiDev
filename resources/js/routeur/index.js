import { createRouter, createWebHistory } from 'vue-router';
import home from '../components/Home.vue';
import notFound from '../components/404.vue';
import login from '../components/Login.vue';
import register from '../components/Register.vue';
import job from '../components/JobsOffers.vue';

const routes = [
  { path: '/', name: 'home', component: home },
  { path: '/:pathMatch(.*)*', component: notFound },
  { path: '/connexion', name: 'login', component: login },
  { path: '/inscription', name: 'register', component: register },
  { path: '/offres-emploi', name: 'job', component: job },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
