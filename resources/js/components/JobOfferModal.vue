<!-- JobOfferModal.vue -->
<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full mx-4 md:mx-0">
        <!-- Vérifier si les données de l'offre d'emploi sont disponibles -->
        <div v-if="jobOffer && jobOffer.name">
          <!-- Modal Header -->
          <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-xl font-semibold">{{ jobOffer.name }}</h2>
            <button @click="closeModal" class="text-gray-700 hover:text-gray-900">
              <!-- Icône de fermeture -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <!-- Modal Body -->
          <div class="p-6">
            <!-- Job Offer Details -->
            <div class="flex flex-col md:flex-row">
              <!-- Company Logo -->
              <div class="md:w-1/3">
                <img :src="companyLogoUrl" alt="Company Logo"
                     class="w-full h-auto rounded-lg object-cover">
              </div>
              <!-- Job Information -->
              <div class="md:w-2/3 md:pl-6 mt-4 md:mt-0">
                <p><strong>Poste:</strong> {{ jobOffer.name }}</p>
                <p v-if="jobOffer.company"><strong>Entreprise:</strong> {{ jobOffer.company.name }}</p>
                <p v-if="jobOffer.types_contract"><strong>Type de Contrat:</strong> {{ jobOffer.types_contract.name }}</p>
                <p v-if="jobOffer.types_developer"><strong>Type de Développeur Recherché:</strong> {{ jobOffer.types_developer.name }}</p>
                <p v-if="jobOffer.years_experience"><strong>Années d'Expérience Requises:</strong> {{ jobOffer.years_experience.name }}</p>
                <p v-if="jobOffer.location"><strong>Lieu:</strong> {{ jobOffer.location.city }}, {{ jobOffer.location.postal_code }}</p>
                <p><strong>Description:</strong> {{ jobOffer.description }}</p>
                <!-- Programming Languages -->
                <div class="mt-4" v-if="jobOffer.programming_languages && jobOffer.programming_languages.length">
                  <p><strong>Langages de Programmation Requis:</strong></p>
                  <div class="flex flex-wrap mt-2">
                    <span v-for="language in jobOffer.programming_languages" :key="language.id"
                          class="bg-gray-200 text-gray-800 text-sm font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded">
                      {{ language.name }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <!-- Additional Information -->
            <!-- Vous pouvez ajouter d'autres informations ici si nécessaire -->
          </div>
          <!-- Modal Footer -->
          <div class="flex justify-end px-6 py-4 border-t">
            <button @click="closeModal" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
              Fermer
            </button>
          </div>
        </div>
        <!-- Afficher un message de chargement ou d'erreur -->
        <div v-else class="p-6">
          <p v-if="isLoading">Chargement...</p>
          <p v-else-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, computed } from 'vue';
  import Axios from 'axios';
  
  const props = defineProps({
    isOpen: {
      type: Boolean,
      required: true
    },
    jobOfferId: {
      type: [Number, null],
      default: null
    }
  });
  
  const emits = defineEmits(['close']);
  
  const jobOffer = ref({});
  const isLoading = ref(false);
  const errorMessage = ref('');
  
  // Fonction pour récupérer les détails de l'offre d'emploi
  const fetchJobOfferDetails = async () => {
    if (!props.jobOfferId) {
      return;
    }
    isLoading.value = true;
    try {
      const response = await Axios.get(`/api/job-offers/${props.jobOfferId}`);
      jobOffer.value = response.data.job_offer;
      console.log('Fetched job offer data:', jobOffer.value);
      errorMessage.value = '';
    } catch (error) {
      console.error('Failed to fetch job offer details:', error);
      errorMessage.value = error.response?.data?.message || 'Une erreur est survenue.';
    } finally {
      isLoading.value = false;
    }
  };
  
  // Calculer l'URL du logo de l'entreprise
  const companyLogoUrl = computed(() => {
    return jobOffer.value.company && jobOffer.value.company.logo
      ? `/storage/${jobOffer.value.company.logo}`
      : '/images/default-company-logo.png'; // Chemin par défaut si pas de logo
  });
  
  // Watcher pour détecter les changements et appeler la fonction de récupération
  watch(
    [() => props.isOpen, () => props.jobOfferId],
    ([newIsOpen, newJobOfferId], [oldIsOpen, oldJobOfferId]) => {
      if (newIsOpen && newJobOfferId != null) {
        if (!oldIsOpen || newJobOfferId !== oldJobOfferId) {
          fetchJobOfferDetails();
        }
      }
    }
  );
  
  // Fonction pour fermer le modal
  const closeModal = () => {
    emits('close');
  };
  </script>
  
  <style scoped>
  /* Ajoutez des styles supplémentaires si nécessaire */
  </style>
  