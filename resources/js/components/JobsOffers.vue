<template>
    <Form @filter="fetchJobOffers" />
    <div v-if="resourcesFilteredStore.jobOffers.length">

        <div class="hidden md:block mt-10 lg:mt-0 mb-6 lg:mb-0">
            <PageTitle title="// Les offres d'emploi" />
        </div>

        <button @click="openFilterModal"
            class="bg-blue-500 hidden md:block lg:hidden w-full mx-auto text-white py-3 px-3 rounded-lg shadow-lg text-base hover:bg-blue-600 transition duration-300 ease-in-out">
            <span class="font-normal flex items-center justify-center gap-3">
                <img :src="`${baseImageUrl}magnifying-glass-solid.svg`" class="searchIcon h-5">
                Filtres de recherches
            </span>
        </button>

        <div class="grid lg:grid-cols-2 gap-4 mt-20 md:mt-10">
            <JobOfferCard v-for="job in resourcesFilteredStore.jobOffers" :key="job.id" :job="job" @click="openModal" />
        </div>
        <!-- Pagination -->
        <Pagination :currentPage="resourcesFilteredStore.currentPage" :totalPages="resourcesFilteredStore.totalPages"
            @updatePage="handlePageChange" />
    </div>
    <div v-else>
        <p>Aucune offre d'emploi trouvée.</p>
    </div>

    <JobOfferModal :isOpen="showModal" :jobOfferId="selectedJobOfferId" @close="showModal = false" />

    <FloatingButton v-if="authStore.user && authStore.user.role_id === 2" @click="openCreateModal" />

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
import PageTitle from './PageTitle.vue';
import Pagination from './Pagination.vue';

const authStore = useAuthStore();
const resourcesFilteredStore = useDeveloperAndJobStore();
const showModal = ref(false);
const selectedJobOfferId = ref(null);
const baseImageUrl = ref("/storage/images/");

//Pour la création d'une offre d'emploi uniquement les entreprises, par defaut non visible sur false
const showCreateModal = ref(false);

// Fonction pour changer la page
const handlePageChange = (newPage) => {
    resourcesFilteredStore.fetchResources({}, newPage);
};

const openModal = (id) => {
    selectedJobOfferId.value = id;
    showModal.value = true;
};

const fetchJobOffers = (filters) => {
    resourcesFilteredStore.setIsDeveloper(false);
    resourcesFilteredStore.fetchResources(filters, resourcesFilteredStore.currentPage);
};

//Filtrage sur tablette

// Fonction pour ouvrir le modal de filtrage sur tablette
const openFilterModal = () => {
    window.dispatchEvent(new Event('openFilterModal'));
};

//Création d'une offre d'emploi

// Fonction pour ouvrir le modal de création d'une offre d'emploi
const openCreateModal = () => {
    showCreateModal.value = true;
};

// Fonction pour fermer le modal
const closeCreateModal = () => {
    showCreateModal.value = false;
};

//Après la création d'une offre d'emploi
const jobCreated = (newJobOffer) => {
    closeCreateModal();
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
    resourcesFilteredStore.setIsDeveloper(false);
    resourcesFilteredStore.fetchResources();
    // Écouter l'événement globalement
    window.addEventListener('openFilterModal', openFilterModalListener);
});

onUnmounted(() => {
    // Nettoyer l'écouteur lorsque le composant est détruit
    window.removeEventListener('openFilterModal', openFilterModalListener);
});

</script>

<style scoped>
.searchIcon {
    filter: invert(100%) sepia(100%) saturate(0%);
}
</style>