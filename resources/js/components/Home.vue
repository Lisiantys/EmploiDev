<template>
    <div class="p-6 sm:p-10 text-2xl font-bold md:pl-32">
        <Form @filter="fetchResources" />
        <div v-if="resourcesFilteredStore.developers.length">
            <h3>Developers</h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-20 md:mt-10">
                <div
                    v-for="developer in resourcesFilteredStore.developers"
                    :key="developer.id"
                              @click="openModal(developer.id)"

                    :class="
                        developer.is_free
                            ? 'bg-white hover14 cursor-pointer rounded-lg shadow-lg flex flex-col h-full border-4 border-green-500'
                            : 'bg-white hover14 cursor-pointer rounded-lg shadow-lg flex flex-col h-full border-4 border-red-500'
                    "
                >
                    <div class="rounded-t overflow-hidden">
                        <figure class="m-0">
                            <img
                                :src="developer.profil_image"
                                class="w-full h-48 object-cover object-center"
                                alt="Profile picture"
                            />
                        </figure>
                    </div>
                    <div class="flex-grow">
                        <div class="px-6 py-4 bg-gray-100 border-b border-gray-300">
                            <p class="font-bold text-xl text-gray-800">
                                {{ developer.first_name }} {{ developer.surname }}
                            </p>
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-location-dot fa-2xs"></i>
                                <p class="text-black text-base">
                                    {{ developer.location.city }}, {{ developer.location.postal_code }}
                                </p>
                            </div>
                            <p class="text-black text-base">
                                Développeur {{ developer.types_developer.name }}
                            </p>
                        </div>
                        <div class="px-6 py-2">
                            <span
                                v-for="language in developer.programming_languages"
                                :key="language.id"
                                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                            >
                                #{{ language.name }}
                            </span>
                        </div>
                    </div>
                    <div
                        :class="
                            developer.is_free
                                ? 'w-full bg-green-500 text-center'
                                : 'w-full bg-red-500 text-center'
                        "
                    >
                        <p class="py-2 rounded-b-full text-sm font-semibold text-white">
                            {{"En recherche de " + developer.types_contract.name}}
                        </p>
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
            <p>Aucun développeur trouvé.</p>
        </div>

        <DeveloperModal
      :isOpen="showModal"
      :developerId="selectedDeveloperId"
      @close="showModal = false"
    />

  
       <!-- Bouton flottant pour ajouter une offre d'emploi -->
       <FloatingButton v-if="authStore.user && authStore.user.role_id === 2" @click="openCreateModal">
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
import { ref, onMounted } from 'vue';
import { useDeveloperAndJobStore } from '../stores/developerAndJobStore';
import { useAuthStore } from '../stores/authStore';
import Form from './FilterForm.vue';
import DeveloperModal from './DeveloperModal.vue';
import FloatingButton from './FloatingButton.vue';
import CreateJobOfferModal from './CreateJobOfferModal.vue'; 

const authStore = useAuthStore();
const resourcesFilteredStore = useDeveloperAndJobStore();

const showModal = ref(false);
const selectedDeveloperId = ref(null);
const showCreateModal = ref(false);

const openModal = (id) => {
  selectedDeveloperId.value = id;
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

const fetchResources = (filters) => {
  resourcesFilteredStore.setIsDeveloper(true);
  resourcesFilteredStore.fetchResources(filters, resourcesFilteredStore.currentPage);
};

onMounted(() => {
  resourcesFilteredStore.setIsDeveloper(true);
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
.animate-charcter {
    text-transform: uppercase;
    background-image: linear-gradient(
        to right,
        rgb(183, 211, 255) 0%,
        rgb(231, 240, 254) 50%,
        rgb(183, 211, 255) 100%
    );
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
    background: linear-gradient(
        to right,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.3) 100%
    );
    transform: skewX(-25deg);
}

@keyframes shine {
    100% {
        left: 125%;
    }
}
</style>
