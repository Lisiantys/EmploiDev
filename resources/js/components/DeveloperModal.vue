<!-- DeveloperModal.vue -->
<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full mx-4 md:mx-0">
        <!-- Vérifier si les données du développeur sont disponibles -->
        <div v-if="developer && developer.first_name">
          <!-- Modal Header -->
          <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-xl font-semibold">{{ developer.first_name }} {{ developer.surname }}</h2>
            <button @click="closeModal" class="text-gray-700 hover:text-gray-900">
              <!-- SVG de fermeture -->
            </button>
          </div>
          <!-- Modal Body -->
          <div class="p-6">
            <!-- Developer Details -->
            <div class="flex flex-col md:flex-row">
              <!-- Profile Image -->
              <div class="md:w-1/3">
                <img :src="developer.profil_image" alt="Profile Image"
                     class="w-full h-auto rounded-lg object-cover">
              </div>
              <!-- Developer Information -->
              <div class="md:w-2/3 md:pl-6 mt-4 md:mt-0">
                <p><strong>Nom:</strong> {{ developer.first_name }} {{ developer.surname }}</p>
                <p v-if="developer.types_contract"><strong>Type de Contrat:</strong> {{ developer.types_contract.name }}</p>
                <p v-if="developer.types_developer"><strong>Type de Développeur:</strong> {{ developer.types_developer.name }}</p>
                <p v-if="developer.years_experience"><strong>Années d'Expérience:</strong> {{ developer.years_experience.name }}</p>
                <p v-if="developer.location"><strong>Ville:</strong> {{ developer.location.city }}, {{ developer.location.postal_code }}</p>
                <p><strong>Description:</strong> {{ developer.description }}</p>
                <!-- Programming Languages -->
                <div class="mt-4" v-if="developer.programming_languages && developer.programming_languages.length">
                  <p><strong>Langages de Programmation:</strong></p>
                  <div class="flex flex-wrap mt-2">
                    <span v-for="language in developer.programming_languages" :key="language.id"
                          class="bg-gray-200 text-gray-800 text-sm font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded">
                      {{ language.name }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <!-- CV and Cover Letter Links -->
            <div class="mt-6">
              <p><strong>Documents:</strong></p>
              <ul class="list-disc list-inside">
                <li v-if="developer.cv">
                  <a :href="developerCvUrl" target="_blank" class="text-blue-600 hover:underline">
                    Télécharger le CV
                  </a>
                </li>
                <li v-if="developer.cover_letter">
                  <a :href="developerCoverLetterUrl" target="_blank" class="text-blue-600 hover:underline">
                    Télécharger la Lettre de Motivation
                  </a>
                </li>
              </ul>
            </div>
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
  import { ref, watch, onMounted, computed } from 'vue';
  import Axios from 'axios';
  
  const props = defineProps({
    isOpen: {
      type: Boolean,
      required: true
    },
    developerId: {
      type: [Number, null],
      default: null
    }
  });
  
  const emits = defineEmits(['close']);
  
  const developer = ref({});
  const isLoading = ref(false);
  const errorMessage = ref('');
  
  const fetchDeveloperDetails = async () => {
    if (!props.developerId) {
      return;
    }
    isLoading.value = true;
    try {
      const response = await Axios.get(`/api/developers/${props.developerId}`);
      developer.value = response.data.developer;
      console.log('Fetched developer data:', developer.value);
      errorMessage.value = '';
    } catch (error) {
      console.error('Failed to fetch developer details:', error);
      errorMessage.value = error.response?.data?.message || 'Une erreur est survenue.';
    } finally {
      isLoading.value = false;
    }
  };
  
  // Compute URLs for CV and Cover Letter
  const developerCvUrl = computed(() => {
  return developer.value.cv ? `/api/developers/${developer.value.id}/cv` : null;
});

const developerCoverLetterUrl = computed(() => {
  return developer.value.cover_letter ? `/api/developers/${developer.value.id}/cover-letter` : null;
});
  
watch(
  [() => props.isOpen, () => props.developerId],
  ([newIsOpen, newDeveloperId], [oldIsOpen, oldDeveloperId]) => {
    if (newIsOpen && newDeveloperId != null) {
      // Appeler fetchDeveloperDetails seulement si isOpen est true et developerId est défini
      // et si isOpen ou developerId a changé
      if (!oldIsOpen || newDeveloperId !== oldDeveloperId) {
        fetchDeveloperDetails();
      }
    }
  }
);
  const closeModal = () => {
    emits('close');
  };
  </script>
  