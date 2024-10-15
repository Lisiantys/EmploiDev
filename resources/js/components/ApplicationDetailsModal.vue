<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
        <div
            class="bg-white rounded-lg shadow-lg w-11/12 md:max-w-3xl lg:max-w-4xl xl:max-w-6xl md:w-full mx-4 md:mx-0">
            <!-- Vérifier si les données de l'offre d'emploi sont disponibles -->
            <div v-if="application && application.job_offer && application.job_offer.name">
                <!-- Modal Body -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row">
                        <!-- Logo de l'entreprise -->
                        <div class="hidden md:block md:w-1/3">
                            <img :src="companyLogoUrl" alt="Company Logo" class="w-full h-64 rounded-lg object-cover">
                        </div>
                        <!-- Informations de l'offre -->
                        <div class="md:w-2/3 md:pl-6 mt-4 md:mt-0">
                            <h2 class="text-xl font-semibold mt-2 break-words">// {{ application.job_offer.name }}</h2>
                            <div
                                class="text-wrap break-words text-xs md:text-sm lg:text-base font-light overflow-y-auto max-h-40">
                                <p>{{ application.job_offer.description }}</p>
                            </div>
                            <div class="flex flex-col mt-2 md:mt-0">
                                <div>
                                    <p v-if="application.job_offer.company" class="text-xs md:text-sm mt-2">
                                        <strong>Entreprise :</strong> {{ application.job_offer.company.name }}
                                    </p>
                                    <p v-if="application.job_offer.types_contract" class="text-xs md:text-sm mt-2">
                                        <strong>Contrat :</strong> {{ application.job_offer.types_contract.name }}
                                    </p>
                                </div>
                                <div>
                                    <p v-if="application.job_offer.types_developer" class="text-xs md:text-sm mt-2">
                                        <strong>Développeur Recherché :</strong> {{
                                        application.job_offer.types_developer.name }}
                                    </p>
                                    <p v-if="application.job_offer.years_experience" class="text-xs md:text-sm mt-2">
                                        <strong>Expérience Requises :</strong> {{
                                        application.job_offer.years_experience.name }}
                                    </p>
                                    <p v-if="application.job_offer.location" class="text-xs md:text-sm mt-2">
                                        <strong>Lieu :</strong> {{ application.job_offer.location.city }}, {{
                                        application.job_offer.location.postal_code }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Langages de Programmation -->
                    <div class="my-4"
                        v-if="application.job_offer.programming_languages && application.job_offer.programming_languages.length">
                        <div class="flex flex-wrap mt-2 md:max-h-none max-h-24 overflow-y-auto">
                            <span v-for="language in application.job_offer.programming_languages" :key="language.id"
                                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-xs md:text-sm font-normal text-gray-700 mr-2 mb-2">
                                #{{ language.name }}
                            </span>
                        </div>
                    </div>
                    <!-- Section CV et Lettre de motivation récupérés de la candidature -->
                    <div class="mt-4">
                        <p
                            class="text-gray-700 mt-4 text-wrap break-words text-xs md:text-sm lg:text-base font-light overflow-y-auto max-h-20 md:max-h-40">
                            {{ application.description }}
                        </p>
                        <div class="mt-2 flex space-x-4">
                            <a :href="cvUrl" target="_blank"
                                class="bg-blue-500 text-white px-4 py-2 font-light rounded-lg text-xs md:text-base hover:bg-blue-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Voir CV
                            </a>
                            <a :href="coverLetterUrl" target="_blank"
                                class="bg-blue-500 text-white px-4 py-2 font-light rounded-lg text-xs md:text-base hover:bg-blue-600 flex items-center">
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
