<template>
  <div>
    <h1 class="text-2xl font-bold mt-10 mb-4">Offres d'Emploi de l'Entreprise</h1>

    <!-- Affichage du message d'erreur -->
    <div v-if="errorMessage" class="text-red-500 mb-4">{{ errorMessage }}</div>

    <!-- Offres validées -->
    <div v-if="validatedJobOffers.length > 0" class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Offres d'emploi validées</h2>
      <div v-for="offer in validatedJobOffers" :key="offer.id">
        <JobOfferCard
          :job="offer"
          :isCompanyView="true"
          @click="viewApplications"
          @delete="deleteJobOffer"
        />

        <!-- Afficher le nombre de candidatures en attente -->
        <div class="mt-2">
          <p class="text-gray-700 text-base">
            Candidatures en attente : <strong>{{ offer.pending_applications_count }}</strong>
          </p>
        </div>
      </div>
    </div>

    <!-- Offres non validées -->
    <div v-if="nonValidatedJobOffers.length > 0" class="mb-8">
      <h2 class="text-xl font-semibold mb-4">Offres d'emploi non validées</h2>
      <div v-for="offer in nonValidatedJobOffers" :key="offer.id">
        <JobOfferCard
          :job="offer"
          :isCompanyView="true"
          @delete="deleteJobOffer"
        />
      </div>
    </div>

    <!-- Aucune offre disponible -->
    <div v-if="
      validatedJobOffers.length === 0 &&
      nonValidatedJobOffers.length === 0 &&
      !errorMessage
    ">
      <p>Aucune offre d'emploi créée pour le moment.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import JobOfferCard from './JobOfferCard.vue'; // Ajustez le chemin si nécessaire

const router = useRouter();

const jobOffers = ref([]);
const validatedJobOffers = ref([]);
const nonValidatedJobOffers = ref([]);
const errorMessage = ref('');

// Fonction pour récupérer les offres d'emploi de l'entreprise
const fetchJobOffers = async () => {
  try {
    const response = await axios.get('/api/company/job-offers');
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
      "Voulez-vous vraiment supprimer cette offre d'emploi ? Cette action est irréversible."
    )
  ) {
    try {
      await axios.delete(`/api/job-offers/${jobOfferId}`);
      // Mettre à jour la liste des offres après suppression
      fetchJobOffers();
    } catch (error) {
      console.error(
        "Erreur lors de la suppression de l'offre d'emploi :",
        error
      );
      errorMessage.value =
        "Erreur lors de la suppression de l'offre d'emploi.";
    }
  }
};

// Méthode pour afficher les candidatures liées à une offre d'emploi validée
const viewApplications = (jobOfferId) => {
  // Rediriger vers le composant JobOfferApplications avec l'ID de l'offre
  router.push({ name: 'jobOfferApplications', params: { id: jobOfferId } });
};

onMounted(() => {
  fetchJobOffers();
});
</script>

<style scoped>
/* Vous pouvez ajouter des styles personnalisés ici si nécessaire */
</style>
