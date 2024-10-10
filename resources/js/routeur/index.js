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
  { path: '/profil-dev', name: 'developerProfile', component: developerProfile, meta: { requiresAuth: true, role: 1 } }, // Dev uniquement
  { path: '/profil-comp', name: 'companyProfile', component: companyProfile, meta: { requiresAuth: true, role: 2 } }, // Company uniquement
  { path: '/candidatures', name: 'developerApplication', component: developerApplication, meta: { requiresAuth: true, role: 1 } }, // Dev uniquement
  { path: '/vos-offres', name: 'companyJobOffer', component: companyJobOffer, meta: { requiresAuth: true, role: 2 } }, // Company uniquement
  { path: '/job-offers/:id/applications', name: 'jobOfferApplications', component: jobOfferApplications, meta: { requiresAuth: true } },
  { path: '/dashboard', name: 'adminDashboard', component: adminDashboard, meta: { requiresAuth: true, role: 3 } }, // Admin uniquement
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

// Garde de navigation pour protéger les routes selon le rôle
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const userRole = authStore.user?.role_id;

  if (requiresAuth && !authStore.isAuthenticated) {
    // Si la route nécessite une authentification et que l'utilisateur n'est pas connecté
    next({ name: 'login' });
  } else if (to.meta.role && userRole !== to.meta.role) {
    // Si l'utilisateur n'a pas le rôle requis pour accéder à la route
    next({ name: 'home' });
  } else {
    next();
  }
});

export default router;
