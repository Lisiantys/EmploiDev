<template>
  <div class="p-6 sm:p-20 font-bold md:pl-32">
    <h2 class="text-2xl font-bold mb-4">Mes Candidatures</h2>

    <div v-if="applications.length === 0">
      <p>Aucune candidature envoyée pour le moment.</p>
    </div>

    <!-- Candidatures acceptées -->
    <div v-if="acceptedApplications.length > 0" class="mb-8">
      <h3 class="text-xl font-semibold mb-4">Candidatures acceptées</h3>
      <div v-for="application in acceptedApplications" :key="application.id" @click="openModal(application.id)"
        class="border-2 border-green-500 shadow-lg rounded-lg p-4 mb-4 cursor-pointer">
        <p><strong>Offre d'emploi :</strong> {{ application.job_offer.name }}</p>
        <p><strong>Entreprise :</strong> {{ application.job_offer.company.name }}</p>
        <p><strong>Lieu :</strong> {{ application.job_offer.location.city }}, {{
          application.job_offer.location.postal_code }}</p>
        <p><strong>Description :</strong> {{ application.description }}</p>
        <button @click.stop="deleteApplication(application.id)"
          class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">Supprimer</button>
      </div>
    </div>

    <!-- Candidatures en attente -->
    <div v-if="pendingApplications.length > 0" class="mb-8">
      <h3 class="text-xl font-semibold mb-4">Candidatures en attente</h3>
      <div v-for="application in pendingApplications" :key="application.id" @click="openModal(application.id)"
        class="border-2 border-blue-400 shadow-lg rounded-lg p-4 mb-4 cursor-pointer">
        <p><strong>Offre d'emploi :</strong> {{ application.job_offer.name }}</p>
        <p><strong>Entreprise :</strong> {{ application.job_offer.company.name }}</p>
        <p><strong>Lieu :</strong> {{ application.job_offer.location.city }}, {{
          application.job_offer.location.postal_code }}</p>
        <p><strong>Description :</strong> {{ application.description }}</p>
        <button @click.stop="deleteApplication(application.id)"
          class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">Supprimer</button>
      </div>
    </div>

    <!-- Candidatures refusées -->
    <div v-if="rejectedApplications.length > 0" class="mb-8">
      <h3 class="text-xl font-semibold mb-4">Candidatures refusées</h3>
      <div v-for="application in rejectedApplications" :key="application.id" @click="openModal(application.id)"
        class="border-2 border-red-500 shadow-lg rounded-lg p-4 mb-4 cursor-pointer">
        <p><strong>Offre d'emploi :</strong> {{ application.job_offer.name }}</p>
        <p><strong>Entreprise :</strong> {{ application.job_offer.company.name }}</p>
        <p><strong>Lieu :</strong> {{ application.job_offer.location.city }}, {{
          application.job_offer.location.postal_code }}</p>
        <p><strong>Description :</strong> {{ application.description }}</p>
        <button @click.stop="deleteApplication(application.id)"
          class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">Supprimer</button>
      </div>
    </div>

    <ApplicationDetailsModal :isOpen="isModalOpen" :applicationId="selectedApplicationId" @close="closeModal" />

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Axios from 'axios';
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

    // Filtrer les candidatures par status
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

// Méthode pour supprimer une candidature
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
/* Ajoutez des styles supplémentaires si nécessaire */
</style>
