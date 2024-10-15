<template>
  <div v-if="jobOffers.length">
    <div class="grid lg:grid-cols-2 gap-4 mt-10">
      <JobOfferCard v-for="job in jobOffers" :key="job.id" :job="job" :isAdmin="true" @click="openModal"
        @validate="validateJobOffer" @delete="deleteJobOffer" />
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
    <p class="text-center md:text-start text-sm md:text-base">Aucune offre d'emploi en attente de validation.</p>
  </div>

  <!-- Modal de l'offre d'emploi -->
  <JobOfferModal :isOpen="showModal" :jobOfferId="selectedJobOfferId" @close="showModal = false" />

</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import JobOfferModal from './JobOfferModal.vue';
import JobOfferCard from './JobOfferCard.vue';
import { useGlobalNotify } from '../notifications/useGlobalNotify';
const notify = useGlobalNotify();

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
    errorMessage.value = 'Erreur lors de la récupération des offres d\'emploi en attente.';
  }
};

const validateJobOffer = async (jobOfferId) => {
  if (confirm('Voulez-vous vraiment valider cette offre d\'emploi ?')) {
    try {
      const response = await axios.post(`/api/admin/job-offers/${jobOfferId}/validate`);
      fetchPendingJobOffers(jobOffersPagination.value.current_page);
      notify({
        group: 'success-action',
        type: 'success',
        title: 'Succès',
        text: 'Offre d\'emploi acceptée avec succès !'
      });
    } catch (error) {
      alert('Erreur lors de la validation de l\'offre d\'emploi.');
    }
  }
};

const deleteJobOffer = async (jobOfferId) => {
  if (confirm('Voulez-vous vraiment supprimer cette offre d\'emploi ?')) {
    try {
      await axios.delete(`/api/job-offers/${jobOfferId}`);
      fetchPendingJobOffers(jobOffersPagination.value.current_page);
      notify({
        group: 'success-action',
        type: 'success',
        title: 'Succès',
        text: 'Offre d\'emploi supprimée avec succès !'
      });
    } catch (error) {
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