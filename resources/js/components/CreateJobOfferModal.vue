<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 text-sm">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 md:mx-0 max-h-[90vh] flex flex-col">
      <!-- Modal Header -->
      <div class="flex justify-between items-center px-6 py-4 border-b">
        <h2 class="text-xl font-semibold">Créer une nouvelle offre d'emploi</h2>
        <button @click="closeModal" class="text-gray-700 hover:text-gray-900">
          <!-- Icône de fermeture -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <!-- Modal Body -->
      <div class="p-6 overflow-y-auto flex-grow">
        <!-- Formulaire de création d'offre d'emploi -->
        <form @submit.prevent="submitForm">
          <!-- Champs du formulaire -->
          <!-- Nom de l'offre -->
          <div class="mb-4">
            <label class="block text-gray-700">Nom de l'offre : </label>
            <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" minlength="10" maxlength="40" required>
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name[0] }}</p>
          </div>
          <!-- Description -->
          <div class="mb-4">
            <label class="block text-gray-700">Description :</label>
            <textarea v-model="form.description" class="w-full border rounded px-3 py-2" rows="4" minlength="20" maxlength="1024" required></textarea>
            <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description[0] }}</p>
          </div>
          <!-- Type de contrat -->
          <div class="mb-4">
            <label class="block text-gray-700">Type de contrat :</label>
            <select v-model="form.contract_id" class="w-full border rounded px-3 py-2" required>
              <option value="" disabled>Choisir un type de contrat</option>
              <option v-for="contract in contracts" :key="contract.id" :value="contract.id">
                {{ contract.name }}
              </option>
            </select>
            <p v-if="errors.contract_id" class="text-red-500 text-sm mt-1">{{ errors.contract_id[0] }}</p>
          </div>
          <!-- Années d'expérience -->
          <div class="mb-4">
            <label class="block text-gray-700">Années d'expérience requises :</label>
            <select v-model="form.year_id" class="w-full border rounded px-3 py-2" required>
              <option value="" disabled>Choisir une expérience</option>
              <option v-for="year in yearsExperiences" :key="year.id" :value="year.id">
                {{ year.name }}
              </option>
            </select>
            <p v-if="errors.year_id" class="text-red-500 text-sm mt-1">{{ errors.year_id[0] }}</p>
          </div>
          <!-- Localisation -->
          <div class="mb-4">
            <label class="block text-gray-700">Localisation :</label>
            <select v-model="form.location_id" class="w-full border rounded px-3 py-2" required>
              <option value="" disabled>Choisir une localisation</option>
              <option v-for="location in localisations" :key="location.id" :value="location.id">
                {{ location.city }}, {{ location.postal_code }}
              </option>
            </select>
            <p v-if="errors.location_id" class="text-red-500 text-sm mt-1">{{ errors.location_id[0] }}</p>
          </div>
          <!-- Type de développeur -->
          <div class="mb-4">
            <label class="block text-gray-700">Type de développeur recherché : </label>
            <select v-model="form.type_id" class="w-full border rounded px-3 py-2" required>
              <option value="" disabled>Choisir un type de développeur</option>
              <option v-for="type in developerTypes" :key="type.id" :value="type.id">
                {{ type.name }}
              </option>
            </select>
            <p v-if="errors.type_id" class="text-red-500 text-sm mt-1">{{ errors.type_id[0] }}</p>
          </div>
          <!-- Langages de programmation avec cases à cocher -->
          <div class="mb-4">
            <label class="block text-gray-700">Langages de programmation requis : </label>
            <div class="flex flex-col justify-between w-full">
              <div class="overflow-y-scroll h-40">
                <div class="grid grid-cols md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 lg:ml-4 gap-4">
                  <div v-for="language in programmingLanguages" :key="language.id" class="inline-flex items-center">
                    <input :id="`language-${language.id}`" type="checkbox" class="form-checkbox text-blue-600"
                      v-model="form.programming_languages" :value="language.id" />
                    <label :for="`language-${language.id}`" class="ml-2 text-sm font-semibold">
                      {{ language.name }}
                    </label>
                  </div>
                </div>
              </div>
              <p v-if="errors.programming_languages" class="text-red-500 text-sm mt-1">{{
                errors.programming_languages[0] }}</p>
            </div>
          </div>
          <!-- Bouton de soumission -->
          <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
              Créer l'offre
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import Axios from 'axios';
import { useResourcesStore } from '../stores/resourcesStore';

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
});

const emits = defineEmits(['close', 'jobCreated']);

const resourcesStore = useResourcesStore();

const contracts = computed(() => resourcesStore.contracts);
const developerTypes = computed(() => resourcesStore.developerTypes);
const programmingLanguages = computed(() => resourcesStore.programmingLanguages);
const localisations = computed(() => resourcesStore.localisations);
const yearsExperiences = computed(() => resourcesStore.yearsExperiences);

const form = ref({
  name: '',
  description: '',
  contract_id: '',
  year_id: '',
  location_id: '',
  type_id: '',
  programming_languages: [],
});

const errors = ref({});

watch(
  () => props.isOpen,
  (newVal) => {
    if (newVal) {
      resetForm();
    }
  }
);

const resetForm = () => {
  form.value = {
    name: '',
    description: '',
    contract_id: '',
    year_id: '',
    location_id: '',
    type_id: '',
    programming_languages: [],
  };
  errors.value = {};
};

const submitForm = async () => {
  try {
    // Envoyer les données au backend
    const response = await Axios.post('/api/job-offers', form.value);
    console.log('Job offer created:', response.data);
    // Émettre un événement pour informer le parent
    emits('jobCreated', response.data.job_offer);
    // Fermer le modal
    closeModal();
  } catch (error) {
    if (error.response && error.response.status === 422) {
      // Gestion des erreurs de validation
      errors.value = error.response.data.errors;
    } else {
      console.error('Failed to create job offer:', error);
    }
  }
};

const closeModal = () => {
  emits('close');
};
</script>

<style scoped>
/* Ajoutez des styles supplémentaires si nécessaire */
</style>
