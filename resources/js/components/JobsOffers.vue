<template>
    <div class="p-6 sm:p-10 text-2xl font-bold md:pl-32">
        <Form @filter="fetchJobOffers" />
        <div v-if="resourcesFilteredStore.jobOffers.length">
            <h3>Jobs</h3>
            <div class="grid lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-20 md:mt-10">
                <div v-for="job in resourcesFilteredStore.jobOffers" :key="job.id"
                     @click="openModal(job.id)"
                     class="bg-white mt-10 shadow-lg w-full max-w-4xl flex flex-col sm:flex-row gap-3 sm:items-center justify-between px-5 py-4 rounded-md cursor-pointer">
                    <!-- Votre code existant pour afficher les offres d'emploi -->
                    <div class="w-full">
                        <div class="flex justify-between">
                            <h3 class="font-bold mt-px text-blue-800">{{ job.name }}</h3>
                            <button class="bg-blue-500 text-white font-medium p-2 rounded-3xl flex gap-1 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-blue-500 text-white rounded-full px-3 py-1 text-sm">{{ job.types_contract.name }}</span>
                            <span class="text-blue-500 text-sm">Développeur {{ job.types_developer.name }}</span>
                        </div>
                        <hr class="mt-2">
                        <div class="pt-2 flex flex-row flex-wrap">
                            <span v-for="language in job.programming_languages" :key="language.id"
                                  class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
                                #{{ language.name }}
                            </span>
                        </div>
                        <div class="py-2 flex flex-row flex-wrap justify-between">
                            <span class="text-slate-600 text-sm flex gap-1 items-center"> 
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg> {{ job.location.city }}, {{ job.location.postal_code }}</span>
                            <p class="text-slate-600 text-sm">{{ timeAgo(job.created_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <div v-if="resourcesFilteredStore.totalPages > 1" class="mt-4 flex justify-between">
                <button
                    @click="previousPage"
                    :disabled="resourcesFilteredStore.currentPage === 1"
                    class="bg-gray-200 px-4 py-2 rounded"
                >
                    Previous
                </button>
                <span>Page {{ resourcesFilteredStore.currentPage }} of {{ resourcesFilteredStore.totalPages }}</span>
                <button
                    @click="nextPage"
                    :disabled="resourcesFilteredStore.currentPage === resourcesFilteredStore.totalPages"
                    class="bg-gray-200 px-4 py-2 rounded"
                >
                    Next
                </button>
            </div>
        </div>
        <div v-else>
            <p>Aucune offre d'emploi trouvée.</p>
        </div>

        <!-- Inclure le composant JobOfferModal -->
        <JobOfferModal
          :isOpen="showModal"
          :jobOfferId="selectedJobOfferId"
          @close="showModal = false"
        />

        
        <FloatingButton v-if="authStore.user && authStore.user.role_id === 2" @click="openCreateModal" class="floating-button ">
  + Ajouter une offre d'emploi
</FloatingButton>


    <!-- Modal de création d'offre d'emploi -->
    <CreateJobOfferModal
      :isOpen="showCreateModal"
      @close="closeCreateModal"
      @jobCreated="jobCreated"
    />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useDeveloperAndJobStore } from '../stores/developerAndJobStore';
import Form from "./FilterForm.vue";
import JobOfferModal from './JobOfferModal.vue'; // Importer le composant modal
import { useAuthStore } from '../stores/authStore';
import CreateJobOfferModal from './CreateJobOfferModal.vue'; 



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


const jobCreated = (newJobOffer) => {
  // Actualisez la liste des offres d'emploi ou affichez un message de succès
  console.log('Nouvelle offre créée:', newJobOffer);
  // Optionnel : Ajouter la nouvelle offre à la liste si nécessaire
  resourcesFilteredStore.jobOffers.unshift(newJobOffer);
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
</script>

<style scoped>
.floating-button {
  position: fixed;
  bottom: 20px; /* Ajustez selon vos besoins */
  right: 20px;  /* Ajustez selon vos besoins */
  background-color: #3b82f6; /* Bleu Tailwind (bg-blue-500) */
  color: white;
  padding: 12px 20px;
  border-radius: 50px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  z-index: 1000; /* Assurez-vous que le bouton est au-dessus des autres éléments */
}

.floating-button:hover {
  background-color: #2563eb; /* Bleu foncé Tailwind (bg-blue-600) */
}
</style>
