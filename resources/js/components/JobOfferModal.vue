<!-- JobOfferModal.vue -->
<template>
    <div v-if="isOpen"
        class="fixed text-sm inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full mx-4 md:mx-0">
            <!-- Vérifier si les données de l'offre d'emploi sont disponibles -->
            <div v-if="jobOffer && jobOffer.name">
                <!-- Modal Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b">
                    <h2 class="text-xl font-semibold">{{ jobOffer.name }}</h2>
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
                            <p><strong>Poste:</strong> {{ jobOffer.name }}</p>
                            <p v-if="jobOffer.company">
                                <strong>Entreprise:</strong>
                                {{ jobOffer.company.name }}
                            </p>
                            <p v-if="jobOffer.types_contract">
                                <strong>Type de Contrat:</strong>
                                {{ jobOffer.types_contract.name }}
                            </p>
                            <p v-if="jobOffer.types_developer">
                                <strong>Type de Développeur Recherché:</strong>
                                {{ jobOffer.types_developer.name }}
                            </p>
                            <p v-if="jobOffer.years_experience">
                                <strong>Années d'Expérience Requises:</strong>
                                {{ jobOffer.years_experience.name }}
                            </p>
                            <p v-if="jobOffer.location">
                                <strong>Lieu:</strong>
                                {{ jobOffer.location.city }},
                                {{ jobOffer.location.postal_code }}
                            </p>
                            <p>
                                <strong>Description:</strong>
                                {{ jobOffer.description }}
                            </p>
                            <!-- Programming Languages -->
                            <div class="mt-4" v-if="
                                jobOffer.programming_languages &&
                                jobOffer.programming_languages.length
                            ">
                                <p>
                                    <strong>Langages de Programmation
                                        Requis:</strong>
                                </p>
                                <div class="flex flex-wrap mt-2">
                                    <span v-for="language in jobOffer.programming_languages" :key="language.id"
                                        class="bg-gray-200 text-gray-800 text-sm font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded">
                                        {{ language.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de candidature visible uniquement pour les utilisateurs avec role_id = 1 -->
                    <div v-if="authStore.user && authStore.user.role_id === 1">
                        <!-- Afficher le message si l'utilisateur a déjà postulé -->
                        <div v-if="hasAlreadyApplied">
                            <p class="text-red-500 font-bold">
                                Vous avez déjà postulé à cette offre d'emploi.
                            </p>
                        </div>
                        <!-- Afficher le formulaire si l'utilisateur n'a pas encore postulé -->
                        <div v-else>
                            <p class="text-gray-600 mb-4">
                                Si vous ne fournissez pas de CV ou de lettre de
                                motivation, ceux de votre profil seront utilisés
                                par défaut.
                            </p>
                            <form @submit.prevent="submitApplication">
                                <!-- Description -->
                                <div class="mb-4">
                                    <label class="block text-gray-700">Description (obligatoire)</label>
                                    <textarea v-model="applicationForm.description"
                                        class="w-full border rounded px-3 py-2" rows="3" required></textarea>
                                    <p v-if="errors.description" class="text-red-500 text-sm mt-1">
                                        {{ errors.description[0] }}
                                    </p>
                                </div>
                                <!-- CV -->
                                <div class="mb-4">
                                    <label class="block text-gray-700">CV (optionnel)</label>
                                    <input type="file" @change="handleFileUpload($event, 'cv')"
                                        class="w-full border rounded px-3 py-2" />
                                    <p v-if="errors.cv" class="text-red-500 text-sm mt-1">
                                        {{ errors.cv[0] }}
                                    </p>
                                </div>
                                <!-- Lettre de motivation -->
                                <div class="mb-4">
                                    <label class="block text-gray-700">Lettre de motivation (optionnel)</label>
                                    <input type="file" @change="
                                        handleFileUpload(
                                            $event,
                                            'cover_letter'
                                        )
                                        " class="w-full border rounded px-3 py-2" />
                                    <p v-if="errors.cover_letter" class="text-red-500 text-sm mt-1">
                                        {{ errors.cover_letter[0] }}
                                    </p>
                                </div>
                                <!-- Bouton de soumission -->
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        Postuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Afficher un message de chargement ou d'erreur -->
            <div v-else class="p-6">
                <p v-if="isLoading">Chargement...</p>
                <p v-else-if="errorMessage" class="text-red-500">
                    {{ errorMessage }}
                </p>
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

// Fonction pour récupérer les détails de l'offre d'emploi
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

        // Vérifier si l'utilisateur a déjà candidaté pour cette offre
        checkExistingApplication();
    } catch (error) {
        console.error("Failed to fetch job offer details:", error);
        errorMessage.value =
            error.response?.data?.message || "Une erreur est survenue.";
    } finally {
        isLoading.value = false;
    }
};

// Fonction pour vérifier si l'utilisateur a déjà candidaté
const checkExistingApplication = async () => {
    try {
        const response = await Axios.get(
            `/api/applications/check?job_id=${props.jobOfferId}`
        );
        hasAlreadyApplied.value = response.data.has_applied; // Met à jour hasAlreadyApplied
    } catch (error) {
        console.error("Failed to check existing application:", error);
    }
};

// Calculer l'URL du logo de l'entreprise
const companyLogoUrl = computed(() => {
    return jobOffer.value.company && jobOffer.value.company.logo
        ? `/storage/${jobOffer.value.company.logo}`
        : "/images/default-company-logo.png"; // Chemin par défaut si pas de logo
});

// Fonction pour soumettre la candidature
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

    if (applicationForm.value.cv) {
        formData.append('cv', applicationForm.value.cv);
    }
    if (applicationForm.value.cover_letter) {
        formData.append('cover_letter', applicationForm.value.cover_letter);
    }

    try {
        const response = await Axios.post("/api/applications", formData);
        console.log("Application submitted successfully:", response.data);
        closeModal(); // Fermer le modal après soumission
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error("Failed to submit application:", error);
        }
    }
};

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
    applicationForm.value.description = '';
    emits("close");
};
</script>

<style scoped>
/* Ajoutez des styles supplémentaires si nécessaire */
</style>
