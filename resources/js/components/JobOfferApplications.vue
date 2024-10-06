<template>
    <div class="p-6 sm:p-20 font-bold md:pl-32">
      <h2 class="text-2xl font-bold mb-4">
        Candidatures pour l'offre : {{ jobOffer.name }}
      </h2>
  
      <!-- Affichage du message d'erreur -->
      <div v-if="errorMessage" class="text-red-500 mb-4">{{ errorMessage }}</div>
  
      <!-- Affichage du message de succès -->
      <div v-if="successMessage" class="text-green-500 mb-4">{{ successMessage }}</div>
  
      <!-- Aucune candidature disponible -->
      <div v-if="applications.length === 0 && !errorMessage">
        <p>Aucune candidature pour cette offre d'emploi.</p>
      </div>
  
      <!-- Candidatures acceptées -->
      <div v-if="acceptedApplications.length > 0" class="mb-8">
        <h3 class="text-xl font-semibold mb-4">Candidatures acceptées</h3>
        <div
          v-for="application in acceptedApplications"
          :key="application.id"
          class="border-2 border-green-500 shadow-lg rounded-lg p-4 mb-4"
        >
          <p>
            <strong>Développeur :</strong>
            {{ application.developer.first_name }}
            {{ application.developer.surname }}
          </p>
          <p><strong>Email :</strong> {{ application.developer.user.email }}</p>
          <p><strong>Description :</strong> {{ application.description }}</p>
  
          <!-- Boutons de téléchargement -->
          <div class="mt-4">
            <a
              :href="application.cv_url"
              class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 mr-2"
              target="_blank"
              rel="noopener noreferrer"
            >
              Télécharger le CV
            </a>
            <a
              :href="application.cover_letter_url"
              class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600"
              target="_blank"
              rel="noopener noreferrer"
            >
              Télécharger la Lettre de Motivation
            </a>
          </div>
        </div>
      </div>
  
      <!-- Candidatures en attente -->
      <div v-if="pendingApplications.length > 0" class="mb-8">
        <h3 class="text-xl font-semibold mb-4">Candidatures en attente</h3>
        <div
          v-for="application in pendingApplications"
          :key="application.id"
          class="border-2 border-blue-400 shadow-lg rounded-lg p-4 mb-4"
        >
          <p>
            <strong>Développeur :</strong>
            {{ application.developer.first_name }}
            {{ application.developer.surname }}
          </p>
          <p><strong>Email :</strong> {{ application.developer.user.email }}</p>
          <p><strong>Description :</strong> {{ application.description }}</p>
  
          <!-- Boutons de téléchargement -->
          <div class="mt-4">
            <a
              :href="application.cv_url"
              class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 mr-2"
              target="_blank"
              rel="noopener noreferrer"
            >
              Télécharger le CV
            </a>
            <a
              :href="application.cover_letter_url"
              class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600"
              target="_blank"
              rel="noopener noreferrer"
            >
              Télécharger la Lettre de Motivation
            </a>
          </div>
  
          <!-- Boutons Accepter et Refuser -->
          <div class="mt-4">
            <button
              @click="acceptApplication(application.id)"
              class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 mr-2"
            >
              Accepter
            </button>
            <button
              @click="rejectApplication(application.id)"
              class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600"
            >
              Refuser
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  import axios from 'axios';
  
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
      console.log(response.data);
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
        // Après l'action, recharger les candidatures
        fetchApplications();
        successMessage.value =
          response.data.message || 'Candidature acceptée avec succès.';
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
        // Après l'action, recharger les candidatures
        fetchApplications();
        successMessage.value =
          response.data.message || 'Candidature refusée avec succès.';
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
  /* Ajoutez des styles supplémentaires si nécessaire */
  </style>
  