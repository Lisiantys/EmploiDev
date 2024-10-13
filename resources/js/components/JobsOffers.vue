<template>
    <Form @filter="fetchJobOffers" />
    <div v-if="resourcesFilteredStore.jobOffers.length">
        <h1>Les offres d'emploi</h1>
        <div class="grid lg:grid-cols-2 gap-4 mt-20 md:mt-10">
            <JobOfferCard v-for="job in resourcesFilteredStore.jobOffers" :key="job.id" :job="job" @click="openModal" />
        </div>
        <!-- Pagination -->
        <div v-if="resourcesFilteredStore.totalPages > 1" class="mt-4 flex justify-between">
            <button @click="previousPage" :disabled="resourcesFilteredStore.currentPage === 1"
                class="bg-gray-200 px-4 py-2 rounded">
                Previous
            </button>
            <span>Page {{ resourcesFilteredStore.currentPage }} of {{ resourcesFilteredStore.totalPages }}</span>
            <button @click="nextPage"
                :disabled="resourcesFilteredStore.currentPage === resourcesFilteredStore.totalPages"
                class="bg-gray-200 px-4 py-2 rounded">
                Next
            </button>
        </div>
    </div>
    <div v-else>
        <p>Aucune offre d'emploi trouvée.</p>
    </div>

    <!-- Inclure le composant JobOfferModal -->
    <JobOfferModal :isOpen="showModal" :jobOfferId="selectedJobOfferId" @close="showModal = false" />

    <FloatingButton v-if="authStore.user && authStore.user.role_id === 2" @click="openCreateModal"
        class="floating-button ">
        + Ajouter une offre d'emploi
    </FloatingButton>

    <!-- Modal de création d'offre d'emploi -->
    <CreateJobOfferModal :isOpen="showCreateModal" @close="closeCreateModal" @jobCreated="jobCreated" />

    <FilterModal :isOpen="showFilterModal" @close="closeFilterModal" @filter="fetchJobOffers" />
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useDeveloperAndJobStore } from '../stores/developerAndJobStore';
import Form from "./FilterForm.vue";
import JobOfferModal from './JobOfferModal.vue';
import { useAuthStore } from '../stores/authStore';
import FloatingButton from './FloatingButton.vue';
import CreateJobOfferModal from './CreateJobOfferModal.vue';
import JobOfferCard from './JobOfferCard.vue';
import FilterModal from './FilterModal.vue';

const authStore = useAuthStore();
const resourcesFilteredStore = useDeveloperAndJobStore();

const showModal = ref(false);
const selectedJobOfferId = ref(null);

//Pour la création d'une offre d'emploi uniquement les entreprises
const showCreateModal = ref(false);

const openModal = (id) => {
    selectedJobOfferId.value = id;
    showModal.value = true;
};

// Fonction pour ouvrir le modal
const openCreateModal = () => {
    showCreateModal.value = true;
};

// Fonction pour fermer le modal
const closeCreateModal = () => {
    showCreateModal.value = false;
};

//Après la création d'une offre d'emploi
const jobCreated = (newJobOffer) => {
    console.log('Nouvelle offre créée:', newJobOffer);
    closeCreateModal();
};

const fetchJobOffers = (filters) => {
    resourcesFilteredStore.setIsDeveloper(false);
    resourcesFilteredStore.fetchResources(filters, resourcesFilteredStore.currentPage);
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
    console.log('Component mounted, fetching job offers');
    resourcesFilteredStore.setIsDeveloper(false);
    resourcesFilteredStore.fetchResources();
});

const previousPage = () => {
    if (resourcesFilteredStore.currentPage > 1) {
        resourcesFilteredStore.fetchResources({}, resourcesFilteredStore.currentPage - 1);
    }
};

const nextPage = () => {
    if (resourcesFilteredStore.currentPage < resourcesFilteredStore.totalPages) {
        resourcesFilteredStore.fetchResources({}, resourcesFilteredStore.currentPage + 1);
    }
};

//POUR LE FILTER MODAL SUR MOBILE

const showFilterModal = ref(false);

const openFilterModalListener = () => {
    showFilterModal.value = true;
};

const closeFilterModal = () => {
    showFilterModal.value = false;
};
onMounted(() => {
    // Écouter l'événement globalement
    window.addEventListener('openFilterModal', openFilterModalListener);
});

onUnmounted(() => {
    // Nettoyer l'écouteur lorsque le composant est détruit
    window.removeEventListener('openFilterModal', openFilterModalListener);
});
</script>
