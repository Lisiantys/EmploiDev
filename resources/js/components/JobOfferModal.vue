<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
        <div
            class="bg-white rounded-lg shadow-lg w-11/12 md:max-w-3xl lg:max-w-4xl xl:max-w-6xl md:w-full mx-4 md:mx-0">
            <!-- Vérifier si les données de l'offre d'emploi sont disponibles -->
            <div v-if="jobOffer && jobOffer.name">
                <!-- Modal Body -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row">
                        <!-- Logo de l'entreprise -->
                        <div class="hidden md:block md:w-1/3">
                            <img :src="companyLogoUrl" alt="Company Logo" class="w-full h-64 rounded-lg object-cover">
                        </div>
                        <!-- Informations de l'offre -->
                        <div class="md:w-2/3 md:pl-6 mt-4 md:mt-0">
                            <h2 class="text-xl font-semibold mt-2 break-words">// {{ jobOffer.name }}</h2>
                            <div
                                class="text-wrap break-words text-xs md:text-sm lg:text-base font-light overflow-y-auto max-h-40">
                                <p>{{ jobOffer.description }}</p>
                            </div>
                            <div class="flex flex-col mt-2 md:mt-0">
                                <div>
                                    <p v-if="jobOffer.company" class="text-xs md:text-sm mt-2">
                                        <strong>Entreprise :</strong> {{ jobOffer.company.name }}
                                    </p>
                                    <p v-if="jobOffer.types_contract" class="text-xs md:text-sm mt-2">
                                        <strong>Contrat :</strong> {{ jobOffer.types_contract.name }}
                                    </p>
                                </div>
                                <div>
                                    <p v-if="jobOffer.types_developer" class="text-xs md:text-sm mt-2">
                                        <strong>Développeur Recherché :</strong> {{ jobOffer.types_developer.name }}
                                    </p>
                                    <p v-if="jobOffer.years_experience" class="text-xs md:text-sm mt-2">
                                        <strong>Expérience Requise :</strong> {{ jobOffer.years_experience.name }}
                                    </p>
                                    <p v-if="jobOffer.location" class="text-xs md:text-sm mt-2">
                                        <strong>Lieu :</strong> {{ jobOffer.location.city }}, {{
                                            jobOffer.location.postal_code }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Langages de Programmation -->
                    <div class="my-4" v-if="jobOffer.programming_languages && jobOffer.programming_languages.length">
                        <div class="flex flex-wrap mt-2 md:max-h-none max-h-24 overflow-y-auto">
                            <span v-for="language in jobOffer.programming_languages" :key="language.id"
                                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-xs md:text-sm font-normal text-gray-700 mr-2 mb-2">
                                #{{ language.name }}
                            </span>
                        </div>
                    </div>

                    <!-- Formulaire de candidature visible uniquement pour les développeurs authentifiés -->
                    <div v-if="authStore.user && authStore.user.role_id === 1">
                        <!-- Message si déjà postulé -->
                        <div v-if="hasAlreadyApplied">
                            <p class="text-blue-600 font-normal text-center text-xs md:text-base">
                                Vous avez déjà postulé à cette offre d'emploi.
                            </p>
                        </div>
                        <!-- Formulaire de candidature -->
                        <div v-else>
                            <p class="text-blue-600 mb-4 font-normal text-center text-xs md:text-base">
                                Si vous ne fournissez pas de CV ou de lettre de motivation, ceux de votre profil seront
                                utilisés par défaut.
                            </p>
                            <form @submit.prevent="submitApplication">
                                <!-- Description -->
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-xs md:text-sm">Description
                                        (obligatoire)</label>
                                    <textarea v-model="applicationForm.description"
                                        class="w-full border rounded px-3 py-2 text-xs md:text-sm" rows="3" required
                                        minlength="20" maxlength="1024"></textarea>
                                    <p v-if="errors.description" class="text-red-500 text-xs md:text-sm mt-1">
                                        {{ errors.description[0] }}
                                    </p>
                                </div>
                                <!-- CV -->
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-xs md:text-sm">CV (optionnel)</label>
                                    <input type="file" accept=".pdf" @change="handleFileUpload($event, 'cv')"
                                        class="w-full border rounded px-3 py-2 text-xs md:text-sm" />
                                    <p v-if="errors.cv" class="text-red-500 text-xs md:text-sm mt-1">
                                        {{ errors.cv[0] }}
                                    </p>
                                </div>
                                <!-- Lettre de motivation -->
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-xs md:text-sm">Lettre de motivation
                                        (optionnel)</label>
                                    <input type="file" accept=".pdf" @change="handleFileUpload($event, 'cover_letter')"
                                        class="w-full border rounded px-3 py-2 text-xs md:text-sm" />
                                    <p v-if="errors.cover_letter" class="text-red-500 text-xs md:text-sm mt-1">
                                        {{ errors.cover_letter[0] }}
                                    </p>
                                </div>
                                <!-- Bouton de soumission -->
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-4 py-2 font-light rounded-lg text-sm md:text-base hover:bg-blue-600">
                                        Postuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Message pour les non-développeurs ou non connectés -->
                    <p v-if="!(authStore.user && authStore.user.role_id === 1)"
                        class="text-blue-500 text-xs md:text-base mt-4 text-center">
                        Connectez-vous en tant que développeur pour postuler à cette offre.
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
import { ref, watch, computed } from "vue";
import Axios from "axios";
import { useAuthStore } from "../stores/authStore";

const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true,
    },
    jobOfferId: {
        type: [Number, null],
        default: null,
    },
});

const emits = defineEmits(["close"]);
const authStore = useAuthStore();

const jobOffer = ref({});
const isLoading = ref(false);
const errorMessage = ref("");
const hasAlreadyApplied = ref(false);
const errors = ref({});

const applicationForm = ref({
    description: "",
    cv: null,
    cover_letter: null,
});

const handleFileUpload = (event, fieldName) => {
    const file = event.target.files[0];
    if (file) {
        applicationForm.value[fieldName] = file;
    }
};

const fetchJobOfferDetails = async () => {
    if (!props.jobOfferId) {
        return;
    }
    isLoading.value = true;
    try {
        const response = await Axios.get(`/api/job-offers/${props.jobOfferId}`);
        jobOffer.value = response.data.job_offer;
        console.log("Fetched job offer data:", jobOffer.value);
        errorMessage.value = "";

        // Only check existing application if user is authenticated and is a developer
        if (authStore.user && authStore.user.role_id === 1) {
            await checkExistingApplication();
        }
    } catch (error) {
        console.error("Failed to fetch job offer details:", error);
        errorMessage.value =
            error.response?.data?.message || "Une erreur est survenue.";
    } finally {
        isLoading.value = false;
    }
};

const checkExistingApplication = async () => {
    try {
        const response = await Axios.get(
            `/api/applications/check?job_id=${props.jobOfferId}`
        );
        hasAlreadyApplied.value = response.data.has_applied;
    } catch (error) {
        if (error.response && error.response.status === 403) {
            console.warn("User is not authorized to check applications.");
            hasAlreadyApplied.value = false;
        } else {
            console.error("Failed to check existing application:", error);
        }
    }
};

const companyLogoUrl = computed(() => {
    return jobOffer.value.company && jobOffer.value.company.profil_image
        ? `/storage/${jobOffer.value.company.profil_image}`
        : "/images/default-company-logo.png";
});

const submitApplication = async () => {
    const formData = new FormData();
    formData.append("description", applicationForm.value.description);
    formData.append("job_id", jobOffer.value.id);
    if (applicationForm.value.cv) {
        formData.append("cv", applicationForm.value.cv);
    }
    if (applicationForm.value.cover_letter) {
        formData.append("cover_letter", applicationForm.value.cover_letter);
    }

    try {
        const response = await Axios.post("/api/applications", formData);
        console.log("Application submitted successfully:", response.data);
        closeModal();
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error("Failed to submit application:", error);
        }
    }
};

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

const closeModal = () => {
    applicationForm.value.description = "";
    emits("close");
};
</script>

<style scoped>
/* Ajoutez vos styles ici */
</style>