<template>
  <div>

    <div class="hidden md:block">
      <PageTitle :title="'// Candidatures pour l\'offre - ' + jobOffer.name" class="mt-10 mb-6"/>
    </div>

    <!-- Messages d'erreur et de succès -->
    <div v-if="errorMessage" class="text-red-500 mb-4">{{ errorMessage }}</div>
    <div v-if="successMessage" class="text-green-500 mb-4">{{ successMessage }}</div>

    <!-- Aucune candidature disponible -->
    <div v-if="applications.length === 0 && !errorMessage">
      <p>Aucune candidature pour cette offre d'emploi.</p>
    </div>

    <!-- Candidatures acceptées -->
    <div v-if="acceptedApplications.length > 0" class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Candidatures acceptées</h2>
      <div v-for="application in acceptedApplications" :key="application.id">
        <ApplicationCard
          :application="application"
          :isCompany="true"
        />
      </div>
    </div>

    <!-- Candidatures en attente -->
    <div v-if="pendingApplications.length > 0" class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Candidatures en attente</h2>
      <div v-for="application in pendingApplications" :key="application.id">
        <ApplicationCard
          :application="application"
          :isCompany="true"
          @accept="acceptApplication"
          @reject="rejectApplication"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import ApplicationCard from './ApplicationCard.vue';
import PageTitle from './PageTitle.vue';

const route = useRoute();
const jobOfferId = route.params.id;

const jobOffer = ref({});
const applications = ref([]);
const acceptedApplications = ref([]);
const pendingApplications = ref([]);
const errorMessage = ref('');
const successMessage = ref('');

const fetchApplications = async () => {
  successMessage.value = '';
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
    console.error('Erreur lors de la récupération des candidatures :', error);
    errorMessage.value = 'Erreur lors de la récupération des candidatures.';
  }
};

// Méthode pour accepter une candidature
const acceptApplication = async (applicationId) => {
  if (confirm('Voulez-vous vraiment accepter cette candidature ?')) {
    try {
      const response = await axios.post(`/api/applications/${applicationId}/accept`);
      fetchApplications();
      successMessage.value = response.data.message || 'Candidature acceptée avec succès.';
    } catch (error) {
      console.error("Erreur lors de l'acceptation de la candidature :", error);
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
      successMessage.value = response.data.message || 'Candidature refusée avec succès.';
    } catch (error) {
      console.error('Erreur lors du refus de la candidature :', error);
      errorMessage.value = 'Erreur lors du refus de la candidature.';
    }
  }
};

onMounted(() => {
  fetchApplications();
});
</script>

<style scoped>
/* Ajoutez des styles spécifiques si nécessaire */
</style>
