<template>
    <div class="p-6 sm:p-20 font-bold md:pl-32">
      <h2 class="text-2xl font-bold mb-4">
        Candidatures pour l'offre : {{ jobOffer.name }}
      </h2>
  
      <!-- Affichage du message d'erreur -->
      <div v-if="errorMessage" class="text-red-500 mb-4">{{ errorMessage }}</div>
  
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
          <!-- Ajouter d'autres informations si nécessaire -->
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
          <!-- Ajouter d'autres informations si nécessaire -->
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
  
  const fetchApplications = async () => {
    try {
      const response = await axios.get(`/api/job-offers/${jobOfferId}/applications`);
      console.log(response.data);
      jobOffer.value = response.data.jobOffer;
      const allApplications = response.data.applications;
  
      // Filtrer les candidatures par status
      acceptedApplications.value = allApplications.filter(app => app.status === 'accepted');
      pendingApplications.value = allApplications.filter(app => app.status === 'pending');
  
      applications.value = allApplications;
    } catch (error) {
      console.error('Erreur lors de la récupération des candidatures :', error);
      errorMessage.value = 'Erreur lors de la récupération des candidatures.';
    }
  };
  
  onMounted(() => {
    fetchApplications();
  });
  </script>
  
  <style scoped>
  /* Ajoutez des styles supplémentaires si nécessaire */
  </style>
  