<template>
  <div v-if="jobOffers.length">
    <div class="grid lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-10">
      <div v-for="job in jobOffers" :key="job.id" @click="openModal(job.id)"
        class="bg-white mt-10 shadow-lg w-full max-w-4xl flex flex-col sm:flex-row gap-3 sm:items-center justify-between px-5 py-4 rounded-md cursor-pointer border-4 border-yellow-500">
        <div class="w-full">
          <div class="flex justify-between">
            <h3 class="font-bold mt-px text-blue-800">{{ job.name }}</h3>
            <button class="bg-blue-500 text-white font-medium p-2 rounded-3xl flex gap-1 items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </button>
          </div>
          <div class="flex items-center gap-3">
            <span class="bg-blue-500 text-white rounded-full px-3 py-1 text-sm">{{ job.typesContract?.name }}</span>
            <span class="text-blue-500 text-sm">Développeur {{ job.typesDeveloper?.name }}</span>
          </div>
          <hr class="mt-2">
          <div class="pt-2 flex flex-row flex-wrap">
            <span v-for="language in job.programming_languages || []" :key="language.id"
              class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
              #{{ language.name }}
            </span>
          </div>
          <div class="py-2 flex flex-row flex-wrap justify-between">
            <span class="text-slate-600 text-sm flex gap-1 items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg> {{ job.location?.city }}, {{ job.location?.postal_code }}</span>
            <p class="text-slate-600 text-sm">{{ timeAgo(job.created_at) }}</p>
          </div>
        </div>
        <!-- Boutons de validation et suppression -->
        <div class="flex justify-between px-4 py-2">
          <button @click.stop="validateJobOffer(job.id)"
            class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">
            Valider
          </button>
          <button @click.stop="deleteJobOffer(job.id)" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">
            Supprimer
          </button>
        </div>
      </div>
    </div>
    <!-- Pagination -->
    <div v-if="jobOffersPagination.total > jobOffersPagination.per_page" class="mt-4 flex justify-between">
      <button @click="fetchPendingJobOffers(jobOffersPagination.current_page - 1)"
        :disabled="!jobOffersPagination.prev_page_url" class="bg-gray-200 px-4 py-2 rounded disabled:opacity-50">
        Précédent
      </button>
      <span>
        Page {{ jobOffersPagination.current_page }} sur {{ jobOffersPagination.last_page }}
      </span>
      <button @click="fetchPendingJobOffers(jobOffersPagination.current_page + 1)"
        :disabled="!jobOffersPagination.next_page_url" class="bg-gray-200 px-4 py-2 rounded disabled:opacity-50">
        Suivant
      </button>
    </div>
  </div>
  <div v-else>
    <p>Aucune offre d'emploi en attente de validation.</p>
  </div>

  <!-- Modal de l'offre d'emploi -->
  <JobOfferModal :isOpen="showModal" :jobOfferId="selectedJobOfferId" @close="showModal = false" />

</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import JobOfferModal from './JobOfferModal.vue';

const jobOffers = ref([]);
const jobOffersPagination = ref({});
const errorMessage = ref('');
const showModal = ref(false);
const selectedJobOfferId = ref(null);

const fetchPendingJobOffers = async (page = 1) => {
  try {
    const response = await axios.get(`/api/admin/pending-job-offers?page=${page}`);
    jobOffers.value = response.data.job_offers.data;
    jobOffersPagination.value = response.data.job_offers;
  } catch (error) {
    console.error('Erreur lors de la récupération des offres d\'emploi en attente :', error);
    errorMessage.value = 'Erreur lors de la récupération des offres d\'emploi en attente.';
  }
};

const validateJobOffer = async (jobOfferId) => {
  if (confirm('Voulez-vous vraiment valider cette offre d\'emploi ?')) {
    try {
      const response = await axios.post(`/api/admin/job-offers/${jobOfferId}/validate`);
      alert(response.data.message);
      fetchPendingJobOffers(jobOffersPagination.value.current_page);
    } catch (error) {
      console.error('Erreur lors de la validation de l\'offre d\'emploi :', error);
      alert('Erreur lors de la validation de l\'offre d\'emploi.');
    }
  }
};

const deleteJobOffer = async (jobOfferId) => {
  if (confirm('Voulez-vous vraiment supprimer cette offre d\'emploi ?')) {
    try {
      await axios.delete(`/api/job-offers/${jobOfferId}`);
      alert('Offre d\'emploi supprimée avec succès.');
      fetchPendingJobOffers(jobOffersPagination.value.current_page);
    } catch (error) {
      console.error('Erreur lors de la suppression de l\'offre d\'emploi :', error);
      alert('Erreur lors de la suppression de l\'offre d\'emploi.');
    }
  }
};

const openModal = (id) => {
  selectedJobOfferId.value = id;
  showModal.value = true;
};

const timeAgo = (date) => {
  const seconds = Math.floor((new Date() - new Date(date)) / 1000);
  let interval = Math.floor(seconds / 31536000);
  if (interval > 1) return `il y a ${interval} ans`;
  interval = Math.floor(seconds / 2592000);
  if (interval > 1) return `il y a ${interval} mois`;
  interval = Math.floor(seconds / 86400);
  if (interval > 1) return `il y a ${interval} jours`;
  interval = Math.floor(seconds / 3600);
  if (interval > 1) return `il y a ${interval} heures`;
  interval = Math.floor(seconds / 60);
  if (interval > 1) return `il y a ${interval} minutes`;
  return `il y a quelques secondes`;
};

onMounted(() => {
  fetchPendingJobOffers();
});
</script>

<style scoped>
/* Vos styles ici */
</style>