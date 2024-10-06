import { createRouter, createWebHistory } from 'vue-router';
import home from '../components/Home.vue';
import notFound from '../components/404.vue';
import login from '../components/Login.vue';
import register from '../components/Register.vue';
import job from '../components/JobsOffers.vue';
import developerProfile from '../components/DeveloperProfile.vue';
import companyProfile from '../components/CompanyProfile.vue';
import developerApplication from '../components/DeveloperApplication.vue';
import jobOfferApplications from '../components/JobOfferApplications.vue';
import companyJobOffer from '../components/CompanyJobOffer.vue';
import adminDashboard from '../components/AdminDashboard.vue';
import mentionsLegales from '../components/MentionsLegales.vue';
import politiqueConfidentialite from '../components/Politique.vue';

import { useAuthStore } from '../stores/authStore';

const routes = [
  { path: '/', name: 'home', component: home },
  { path: '/:pathMatch(.*)*', component: notFound },
  { path: '/connexion', name: 'login', component: login },
  { path: '/inscription', name: 'register', component: register },
  { path: '/offres-emploi', name: 'job', component: job },
  { path: '/profil-dev', name: 'developerProfile', component: developerProfile },
  { path: '/profil-comp', name: 'companyProfile', component: companyProfile },
  { path: '/candidatures', name: 'developerApplication', component: developerApplication },
  { path: '/vos-offres', name: 'companyJobOffer', component: companyJobOffer },
  { path: '/job-offers/:id/applications', name: 'jobOfferApplications', component: jobOfferApplications },
  { path: '/dashboard', name: 'adminDashboard', component: adminDashboard },
  { path: '/mentions-legales', name: 'mentionsLegales', component: mentionsLegales },
  { path: '/politique-de-confidentialité', name: 'politiqueConfidentialite', component: politiqueConfidentialite },
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
