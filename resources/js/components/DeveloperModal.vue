<!-- DeveloperModal.vue -->
<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-11/12  md:max-w-3xl lg:max-w-4xl xl:max-w-6xl md:w-full mx-4 md:mx-0">
      <!-- Vérifier si les données du développeur sont disponibles -->
      <div v-if="developer && developer.first_name">
        <!-- Modal Body -->
        <div class="p-6">
          <div class="flex flex-col md:flex-row">
            <div class="md:w-1/3">
              <img :src="profileImage" alt="Profile Image" class="w-full h-64 rounded-lg object-cover">
            </div>
            <div class="md:w-2/3 md:pl-6 mt-4 md:mt-0">
              <h2 class="text-xl font-semibold mt-2">// {{ developer.first_name }} {{ developer.surname }}</h2>
              <p class="text-wrap break-words text-xs md:text-base font-light"> {{ developer.description }}</p>
              <div class="md:flex lg:flex-col justify-between mt-2 md:mt-0">
                <div>
                  <p v-if="developer.types_contract" class="text-xs md:text-sm mt-2">Contrat recherché : {{
                    developer.types_contract.name
                    }}</p>
                  <p v-if="developer.types_developer" class="text-xs md:text-sm mt-2">Développeur : {{
                    developer.types_developer.name }}</p>
                </div>
                <div>
                  <p v-if="developer.years_experience" class="text-xs md:text-sm mt-2">Expérience : {{
                    developer.years_experience.name }}</p>
                  <p v-if="developer.location" class="text-xs md:text-sm mt-2">Ville : {{ developer.location.city }}, {{
                    developer.location.postal_code }}</p>
                </div>
              </div>
            </div>
          </div>
          <!-- Programming Languages -->
          <div class="my-4" v-if="developer.programming_languages && developer.programming_languages.length">
            <div class="flex flex-wrap mt-2 md:max-h-none max-h-24 overflow-y-auto">
              <span v-for="language in developer.programming_languages" :key="language.id"
                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-xs md:text-sm font-normal text-gray-700 mr-2 mb-2">
                #{{ language.name }}
              </span>
            </div>
          </div>

          <!-- CV and Cover Letter Links -->
          <div v-if="developer.cv && isAuthenticated">
            <ul class="flex flex-wrap gap-4">
              <li>
                <a :href="developerCvDownloadUrl" target="_blank"
                  class="flex items-center gap-2 bg-blue-500 text-white text-xs md:text-base font-light px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition duration-300 ease-in-out">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                  Télécharger le CV
                </a>
              </li>
              <li>
                <a :href="developerCoverLetterDownloadUrl" target="_blank"
                  class="flex items-center gap-2 bg-blue-500 text-white text-xs md:text-base font-light px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition duration-300 ease-in-out">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                  La Lettre de Motivation
                </a>
              </li>
            </ul>
          </div>
          <p v-if="!isAuthenticated" class="text-blue-500 text-xs md:text-base mt-4 text-center">
            Connectez-vous pour accéder au CV et à la lettre de motivation.
          </p>
        </div>
        <!-- Modal Footer -->
        <div class="flex justify-end px-6 py-4 border-t">
          <button @click="closeModal"
            class="bg-blue-500 text-white px-4 py-2 font-light rounded-lg text-sm md:text-base hover:bg-blue-600">
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
import { useAuthStore } from '../stores/authStore';

const authStore = useAuthStore();
const isAuthenticated = computed(() => !!authStore.user);

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

// Computed properties pour les images et documents
const profileImage = computed(() => {
  return developer.value.profil_image || '/images/default_profile_image.jpg';
});

const developerCvDownloadUrl = computed(() => {
  return developer.value.cv ? `/api/developers/${developer.value.id}/cv` : null;
});

const developerCoverLetterDownloadUrl = computed(() => {
  return developer.value.cover_letter ? `/api/developers/${developer.value.id}/cover-letter` : null;
});

watch(
  [() => props.isOpen, () => props.developerId],
  ([newIsOpen, newDeveloperId], [oldIsOpen, oldDeveloperId]) => {
    if (newIsOpen && newDeveloperId != null) {
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

<style scope></style>