<template>
  <div>

    <div class="hidden md:block">
      <PageTitle :title="'// Candidatures pour l\'offre - ' + jobOffer.name" class="mt-10 mb-6" />
    </div>

    <button @click="goBack"
      class="mt-20 md:mt-0 mb-4  px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
      Retour aux offres d'emploi
    </button>

    <!-- Messages d'erreur et de succès -->
    <div v-if="errorMessage" class="text-red-500 text-base mb-4">{{ errorMessage }}</div>

    <!-- Aucune candidature disponible -->
    <div v-if="applications.length === 0 && !errorMessage">
      <p>Aucune candidature pour cette offre d'emploi.</p>
    </div>

    <!-- Candidatures acceptées -->
    <div v-if="acceptedApplications.length > 0" class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Candidatures acceptées</h2>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <ApplicationCard v-for="application in acceptedApplications" :key="application.id" :application="application"
          :isCompany="true" />
      </div>
    </div>

    <!-- Candidatures en attente -->
    <div v-if="pendingApplications.length > 0" class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Candidatures en attente</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <ApplicationCard v-for="application in pendingApplications" :key="application.id" :application="application"
          :isCompany="true" @accept="acceptApplication" @reject="rejectApplication" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import ApplicationCard from './ApplicationCard.vue';
import PageTitle from './PageTitle.vue';
import { useGlobalNotify } from '../notifications/useGlobalNotify';
const notify = useGlobalNotify();

const route = useRoute();
const router = useRouter();
const jobOfferId = route.params.id;

const jobOffer = ref({});
const applications = ref([]);
const acceptedApplications = ref([]);
const pendingApplications = ref([]);
const errorMessage = ref('');

const fetchApplications = async () => {
  try {
    const response = await axios.get(`/api/job-offers/${jobOfferId}/applications`);
    jobOffer.value = response.data.jobOffer;
    const allApplications = response.data.applications;

    // Filtrer les candidatures par statut
    acceptedApplications.value = allApplications.filter(
      (app) => app.status === 'accepted'
    );
    pendingApplications.value = allApplications.filter(
      (app) => app.status === 'pending'
    );

    applications.value = allApplications;
  } catch (error) {
    errorMessage.value = 'Erreur lors de la récupération des candidatures.';
  }
};

// Méthode pour accepter une candidature
const acceptApplication = async (applicationId) => {
  if (confirm('Voulez-vous vraiment accepter cette candidature ?')) {
    try {
      const response = await axios.post(`/api/applications/${applicationId}/accept`);
      fetchApplications();
      notify({
        group: 'success-action',
        type: 'success',
        title: 'Succès',
        text: 'Candidature acceptée avec succès !'
      });
    } catch (error) {
      errorMessage.value = "Erreur lors de l'acceptation de la candidature.";
    }
  }
};

// Méthode pour refuser une candidature
const rejectApplication = async (applicationId) => {
  if (confirm('Voulez-vous vraiment refuser cette candidature ?')) {
    try {
      const response = await axios.post(`/api/applications/${applicationId}/refuse`);
      fetchApplications();
      notify({
        group: 'success-action',
        type: 'success',
        title: 'Succès',
        text: 'Candidature refusée avec succès !'
      });
    } catch (error) {
      errorMessage.value = 'Erreur lors du refus de la candidature.';
    }
  }
};

const goBack = () => {
  router.push('/vos-offres');
};

onMounted(() => {
  fetchApplications();
});
</script>