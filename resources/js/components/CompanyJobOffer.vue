<template>
    <div class="p-6 sm:p-20 font-bold md:pl-32">
      <h2 class="text-2xl font-bold mb-4">Offres d'Emploi de l'Entreprise</h2>
  
      <!-- Affichage du message d'erreur -->
      <div v-if="errorMessage" class="text-red-500 mb-4">{{ errorMessage }}</div>
  
      <!-- Offres validées -->
      <div v-if="validatedJobOffers.length > 0" class="mb-8">
        <h3 class="text-xl font-semibold mb-4">Offres d'emploi validées</h3>
        <div
          v-for="offer in validatedJobOffers"
          :key="offer.id"
          class="border-2 border-green-500 shadow-lg rounded-lg p-4 mb-4"
        >
          <p><strong>Nom de l'offre :</strong> {{ offer.name }}</p>
          <p><strong>Description :</strong> {{ offer.description }}</p>
          <p><strong>Ville :</strong> {{ offer.location.city }}</p>
          <!-- Bouton Supprimer -->
          <button
            @click="deleteJobOffer(offer.id)"
            class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
          >
            Supprimer
          </button>
        </div>
      </div>
  
      <!-- Offres non validées -->
      <div v-if="nonValidatedJobOffers.length > 0" class="mb-8">
        <h3 class="text-xl font-semibold mb-4">Offres d'emploi non validées</h3>
        <div
          v-for="offer in nonValidatedJobOffers"
          :key="offer.id"
          class="border-2 border-red-500 shadow-lg rounded-lg p-4 mb-4"
        >
          <p><strong>Nom de l'offre :</strong> {{ offer.name }}</p>
          <p><strong>Description :</strong> {{ offer.description }}</p>
          <p><strong>Ville :</strong> {{ offer.location.city }}</p>
          <!-- Bouton Supprimer -->
          <button
            @click="deleteJobOffer(offer.id)"
            class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
          >
            Supprimer
          </button>
        </div>
      </div>
  
      <!-- Aucune offre disponible -->
      <div
        v-if="
          validatedJobOffers.length === 0 &&
          nonValidatedJobOffers.length === 0 &&
          !errorMessage
        "
      >
        <p>Aucune offre d'emploi créée pour le moment.</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  
  const jobOffers = ref([]);
  const validatedJobOffers = ref([]);
  const nonValidatedJobOffers = ref([]);
  const errorMessage = ref('');
  
  const fetchJobOffers = async () => {
    try {
      const response = await axios.get('/api/company/job-offers');
      console.log(response.data);
      const allJobOffers = response.data;
  
      if (allJobOffers && Array.isArray(allJobOffers)) {
        jobOffers.value = allJobOffers;
  
        // Filtrer les offres validées et non validées
        validatedJobOffers.value = allJobOffers.filter(
          (offer) => offer.is_validated == 1
        );
        nonValidatedJobOffers.value = allJobOffers.filter(
          (offer) => offer.is_validated == 0
        );
      } else {
        errorMessage.value = "Aucune offre d'emploi trouvée.";
      }
    } catch (error) {
      console.error(
        "Erreur lors de la récupération des offres d'emploi :",
        error
      );
      errorMessage.value =
        "Erreur lors de la récupération des offres d'emploi.";
    }
  };
  
  // Méthode pour supprimer une offre d'emploi
  const deleteJobOffer = async (jobOfferId) => {
    if (
      confirm(
        'Voulez-vous vraiment supprimer cette offre d\'emploi ? Cette action est irréversible.'
      )
    ) {
      try {
        await axios.delete(`/api/job-offers/${jobOfferId}`);
        // Mettre à jour la liste des offres après suppression
        fetchJobOffers();
      } catch (error) {
        console.error("Erreur lors de la suppression de l'offre d'emploi :", error);
        errorMessage.value =
          "Erreur lors de la suppression de l'offre d'emploi.";
      }
    }
  };
  
  onMounted(() => {
    fetchJobOffers();
  });
  </script>
  
  <style scoped>
  /* Vous pouvez ajouter des styles personnalisés ici si nécessaire */
  </style>
  