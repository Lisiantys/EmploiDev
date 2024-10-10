<!-- ApplicationDetailsModal.vue -->
<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full mx-4 md:mx-0">
            <!-- Vérifier si les données de l'offre d'emploi sont disponibles -->
            <div v-if="application && application.job_offer && application.job_offer.name">
                <!-- Modal Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b">
                    <h2 class="text-xl font-semibold">{{ application.job_offer.name }}</h2>
                    <button @click="closeModal" class="text-gray-700 hover:text-gray-900">
                        <!-- Icône de fermeture -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
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
                                class="w-full h-auto rounded-lg object-cover" />
                        </div>
                        <!-- Job Information -->
                        <div class="md:w-2/3 md:pl-6 mt-4 md:mt-0">
                            <p><strong>Poste :</strong> {{ application.job_offer.name }}</p>
                            <p v-if="application.job_offer.company"><strong>Entreprise :</strong> {{
                                application.job_offer.company.name }}</p>
                            <p v-if="application.job_offer.types_contract"><strong>Type de Contrat :</strong> {{
                                application.job_offer.types_contract.name }}</p>
                            <p v-if="application.job_offer.types_developer"><strong>Type de Développeur Recherché :</strong>
                                {{ application.job_offer.types_developer.name }}</p>
                            <p v-if="application.job_offer.years_experience"><strong>Années d'Expérience Requises :</strong>
                                {{ application.job_offer.years_experience.name }}</p>
                            <p v-if="application.job_offer.location"><strong>Lieu :</strong> {{
                                application.job_offer.location.city }}, {{ application.job_offer.location.postal_code }}</p>
                            <p><strong>Description de l'offre :</strong> {{ application.job_offer.description }}</p>
                        </div>
                    </div>

                    <!-- Section CV et Lettre de motivation récupérés de la candidature -->
                    <div class="mt-4">
                        <p><strong>Description fournie :</strong> {{ application.description }}</p>
                        <div class="mt-2 flex space-x-4">
                            <a :href="cvUrl" target="_blank" class="text-blue-500 underline flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Voir CV
                            </a>
                            <a :href="coverLetterUrl" target="_blank" class="text-blue-500 underline flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Voir Lettre de motivation
                            </a>
                        </div>
                    </div>
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
    isOpen: { type: Boolean, required: true },
    applicationId: { type: [Number, null], default: null }
});

const emits = defineEmits(['close']);
const application = ref({});
const isLoading = ref(false);
const errorMessage = ref('');

const fetchApplicationDetails = async () => {
    if (!props.applicationId) return;
    isLoading.value = true;
    try {
        const response = await Axios.get(`/api/applications/${props.applicationId}`);
        application.value = response.data.application;
        errorMessage.value = '';
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Une erreur est survenue.';
    } finally {
        isLoading.value = false;
    }
};

const companyLogoUrl = computed(() => {
    return application.value.job_offer.company?.profil_image
        ? `/storage/${application.value.job_offer.company.profil_image}`
        : '/images/default-company-logo.png';
});

const cvUrl = computed(() => {
    return application.value.cv ? `/storage/${application.value.cv}` : '#';
});

const coverLetterUrl = computed(() => {
    return application.value.cover_letter ? `/storage/${application.value.cover_letter}` : '#';
});

watch(
    [() => props.isOpen, () => props.applicationId],
    ([newIsOpen, newApplicationId], [oldIsOpen, oldApplicationId]) => {
        if (newIsOpen && newApplicationId != null) {
            if (!oldIsOpen || newApplicationId !== oldApplicationId) {
                fetchApplicationDetails();
            }
        }
    }
);

const closeModal = () => {
    emits('close');
};
</script>

<style scoped>
/* Ajoutez des styles supplémentaires si nécessaire */
</style>
