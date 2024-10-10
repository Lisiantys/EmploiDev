<template>
  <div>
    <h1 class="text-2xl font-bold mt-10 mb-4">Mes Candidatures</h1>

    <div v-if="applications.length === 0">
      <p>Aucune candidature envoyée pour le moment.</p>
    </div>

    <!-- Candidatures acceptées -->
    <div v-if="acceptedApplications.length > 0" class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Candidatures acceptées</h2>
      <div v-for="application in acceptedApplications" :key="application.id">
        <ApplicationCard
          :application="application"
          :isDeveloper="true"
          @delete="deleteApplication"
          @click="openModal"
        />
      </div>
    </div>

    <!-- Candidatures en attente -->
    <div v-if="pendingApplications.length > 0" class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Candidatures en attente</h2>
      <div v-for="application in pendingApplications" :key="application.id">
        <ApplicationCard
          :application="application"
          :isDeveloper="true"
          @delete="deleteApplication"
          @click="openModal"
        />
      </div>
    </div>

    <!-- Candidatures refusées -->
    <div v-if="rejectedApplications.length > 0" class="mb-8">
      <h3 class="text-xl font-semibold mb-4">Candidatures refusées</h3>
      <div v-for="application in rejectedApplications" :key="application.id">
        <ApplicationCard
          :application="application"
          :isDeveloper="true"
          @delete="deleteApplication"
          @click="openModal"
        />
      </div>
    </div>

    <ApplicationDetailsModal
      :isOpen="isModalOpen"
      :applicationId="selectedApplicationId"
      @close="closeModal"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Axios from 'axios';
import ApplicationCard from './ApplicationCard.vue';
import ApplicationDetailsModal from './ApplicationDetailsModal.vue';

const applications = ref([]);
const acceptedApplications = ref([]);
const pendingApplications = ref([]);
const rejectedApplications = ref([]);

const isModalOpen = ref(false);
const selectedApplicationId = ref(null);

const fetchApplications = async () => {
  try {
    const response = await Axios.get('/api/applications');
    const allApplications = response.data.data;

    applications.value = allApplications;

    // Filtrer les candidatures par statut
    acceptedApplications.value = allApplications.filter(app => app.status === 'accepted');
    pendingApplications.value = allApplications.filter(app => app.status === 'pending');
    rejectedApplications.value = allApplications.filter(app => app.status === 'rejected');
  } catch (error) {
    console.error('Erreur lors de la récupération des candidatures :', error);
  }
};

const openModal = (applicationId) => {
  selectedApplicationId.value = applicationId;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const deleteApplication = async (applicationId) => {
  if (confirm('Voulez-vous vraiment supprimer cette candidature ?')) {
    try {
      await Axios.delete(`/api/applications/${applicationId}`);
      fetchApplications(); // Recharger les candidatures après suppression
    } catch (error) {
      console.error('Erreur lors de la suppression de la candidature :', error);
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
