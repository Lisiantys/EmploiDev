<template>
    <Form @filter="fetchResources" />
    <div v-if="resourcesFilteredStore.developers.length">
        <h1 class="text-2xl font-bold mb-4">Développeurs</h1>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-20 md:mt-10">
            <DeveloperCard v-for="developer in resourcesFilteredStore.developers" :key="developer.id"
                :developer="developer" @click="openModal(developer.id)" />
        </div>
        <!-- Pagination -->
        <div v-if="resourcesFilteredStore.totalPages > 1" class="mt-4 flex justify-between">
            <button @click="previousPage" :disabled="resourcesFilteredStore.currentPage === 1"
                class="bg-gray-200 px-4 py-2 rounded disabled:opacity-50">
                Précédent
            </button>
            <span>Page {{ resourcesFilteredStore.currentPage }} de {{ resourcesFilteredStore.totalPages }}</span>
            <button @click="nextPage"
                :disabled="resourcesFilteredStore.currentPage === resourcesFilteredStore.totalPages"
                class="bg-gray-200 px-4 py-2 rounded disabled:opacity-50">
                Suivant
            </button>
        </div>
    </div>
    <div v-else>
        <p class="text-center text-gray-600">Aucun développeur trouvé.</p>
    </div>

    <DeveloperModal :isOpen="showModal" :developerId="selectedDeveloperId" @close="showModal = false" />

    <!-- Bouton flottant pour ajouter une offre d'emploi -->
    <FloatingButton v-if="authStore.user && authStore.user.role_id === 2" @click="openCreateModal">
        + Ajouter une offre d'emploi
    </FloatingButton>

    <!-- Modal de création d'offre d'emploi -->
    <CreateJobOfferModal :isOpen="showCreateModal" @close="closeCreateModal" @jobCreated="jobCreated" />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useDeveloperAndJobStore } from '../stores/developerAndJobStore';
import { useAuthStore } from '../stores/authStore';
import Form from './FilterForm.vue';
import DeveloperModal from './DeveloperModal.vue';
import FloatingButton from './FloatingButton.vue';
import CreateJobOfferModal from './CreateJobOfferModal.vue';
import DeveloperCard from './DeveloperCard.vue';

const authStore = useAuthStore();
const resourcesFilteredStore = useDeveloperAndJobStore();

const showModal = ref(false);
const selectedDeveloperId = ref(null);
const showCreateModal = ref(false);

const openModal = (id) => {
    selectedDeveloperId.value = id;
    showModal.value = true;
};

// Fonction pour ouvrir le modal de création d'offre d'emploi
const openCreateModal = () => {
    showCreateModal.value = true;
};

// Fonction pour fermer le modal de création d'offre d'emploi
const closeCreateModal = () => {
    showCreateModal.value = false;
};

// Après la création d'une offre d'emploi
const jobCreated = (newJobOffer) => {
    console.log('Nouvelle offre créée:', newJobOffer);
    closeCreateModal();
};

// Fonction pour récupérer les développeurs avec les filtres appliqués
const fetchResources = (filters) => {
    resourcesFilteredStore.setIsDeveloper(true);
    resourcesFilteredStore.fetchResources(filters, resourcesFilteredStore.currentPage);
};

// Chargement initial des développeurs lors du montage du composant
onMounted(() => {
    resourcesFilteredStore.setIsDeveloper(true);
    resourcesFilteredStore.fetchResources({}, resourcesFilteredStore.currentPage);
});

// Pagination : Aller à la page précédente
const previousPage = () => {
    if (resourcesFilteredStore.currentPage > 1) {
        resourcesFilteredStore.fetchResources({}, resourcesFilteredStore.currentPage - 1);
    }
};

// Pagination : Aller à la page suivante
const nextPage = () => {
    if (resourcesFilteredStore.currentPage < resourcesFilteredStore.totalPages) {
        resourcesFilteredStore.fetchResources({}, resourcesFilteredStore.currentPage + 1);
    }
};
</script>

<style scoped>
.animate-charcter {
    text-transform: uppercase;
    background-image: linear-gradient(to right,
            rgb(183, 211, 255) 0%,
            rgb(231, 240, 254) 50%,
            rgb(183, 211, 255) 100%);
    background-size: 200% auto;
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: textclip 6s ease-in-out infinite;
    display: inline-block;
    font-size: 190px;
}

@keyframes textclip {

    0%,
    100% {
        background-position: 100% center;
    }

    50% {
        background-position: 0% center;
    }
}

.wave-effect {
    display: inline-block;
    animation: wave 6s ease-in-out infinite;
}

@keyframes wave {

    0%,
    100% {
        transform: translateY(0);
    }

    25% {
        transform: translateY(-5px);
    }

    50% {
        transform: translateY(0);
    }

    75% {
        transform: translateY(5px);
    }
}

.hover14:hover figure::before {
    animation: shine 0.75s;
}

.hover14 figure {
    position: relative;
}

.hover14 figure::before {
    position: absolute;
    top: 0;
    left: -75%;
    z-index: 2;
    display: block;
    content: "";
    width: 50%;
    height: 100%;
    background: linear-gradient(to right,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.3) 100%);
    transform: skewX(-25deg);
}

@keyframes shine {
    100% {
        left: 125%;
    }
}
</style>
