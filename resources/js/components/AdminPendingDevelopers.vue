<template>
    <div class="p-6 sm:p-10 text-2xl font-bold md:pl-32">
      <div v-if="developers.length">
        <h3>Développeurs en attente de validation</h3>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-10">
          <div
            v-for="developer in developers"
            :key="developer.id"
            @click="openModal(developer.id)"
            class="bg-white hover14 cursor-pointer rounded-lg shadow-lg flex flex-col h-full border-4 border-yellow-500"
          >
            <div class="rounded-t overflow-hidden">
              <figure class="m-0">
                <img
                  :src="developer.profil_image || 'default_profile_image_url.jpg'"
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
                    {{ developer.location?.city }}, {{ developer.location?.postal_code }}
                  </p>
                </div>
                <p class="text-black text-base">
                  Développeur {{ developer.typesDeveloper?.name }}
                </p>
              </div>
              <div class="px-6 py-2">
                <span
                  v-for="language in developer.programming_languages || []"
                  :key="language.id"
                  class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                >
                  #{{ language.name }}
                </span>
              </div>
            </div>
            <div class="w-full bg-yellow-500 text-center">
              <p class="py-2 rounded-b-full text-sm font-semibold text-white">
                En attente de validation
              </p>
            </div>
            <!-- Boutons de validation et suppression -->
            <div class="flex justify-between px-4 py-2">
              <button
                @click.stop="validateDeveloper(developer.id)"
                class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600"
              >
                Valider
              </button>
              <button
                @click.stop="deleteDeveloper(developer.id)"
                class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600"
              >
                Supprimer
              </button>
            </div>
          </div>
        </div>
        <!-- Pagination -->
        <div
          v-if="developersPagination.total > developersPagination.per_page"
          class="mt-4 flex justify-between"
        >
          <button
            @click="fetchPendingDevelopers(developersPagination.current_page - 1)"
            :disabled="!developersPagination.prev_page_url"
            class="bg-gray-200 px-4 py-2 rounded disabled:opacity-50"
          >
            Précédent
          </button>
          <span>
            Page {{ developersPagination.current_page }} sur {{ developersPagination.last_page }}
          </span>
          <button
            @click="fetchPendingDevelopers(developersPagination.current_page + 1)"
            :disabled="!developersPagination.next_page_url"
            class="bg-gray-200 px-4 py-2 rounded disabled:opacity-50"
          >
            Suivant
          </button>
        </div>
      </div>
      <div v-else>
        <p>Aucun développeur en attente de validation.</p>
      </div>
  
      <!-- Modal du développeur -->
      <DeveloperModal
        :isOpen="showModal"
        :developerId="selectedDeveloperId"
        @close="showModal = false"
      />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import DeveloperModal from './DeveloperModal.vue';
  
  const developers = ref([]);
  const developersPagination = ref({});
  const errorMessage = ref('');
  const showModal = ref(false);
  const selectedDeveloperId = ref(null);
  
  const fetchPendingDevelopers = async (page = 1) => {
    try {
      const response = await axios.get(`/api/admin/pending-developers?page=${page}`);
      developers.value = response.data.developers.data;
      developersPagination.value = response.data.developers;
    } catch (error) {
      console.error('Erreur lors de la récupération des développeurs en attente :', error);
      errorMessage.value = 'Erreur lors de la récupération des développeurs en attente.';
    }
  };
  
  const validateDeveloper = async (developerId) => {
    if (confirm('Voulez-vous vraiment valider ce développeur ?')) {
      try {
        const response = await axios.post(`/api/admin/developers/${developerId}/validate`);
        alert(response.data.message);
        fetchPendingDevelopers(developersPagination.value.current_page);
      } catch (error) {
        console.error('Erreur lors de la validation du développeur :', error);
        alert('Erreur lors de la validation du développeur.');
      }
    }
  };
  
  const deleteDeveloper = async (developerId) => {
    if (confirm('Voulez-vous vraiment supprimer ce développeur ?')) {
      try {
        await axios.delete(`/api/admin/developers/${developerId}`);
        alert('Développeur supprimé avec succès.');
        fetchPendingDevelopers(developersPagination.value.current_page);
      } catch (error) {
        console.error('Erreur lors de la suppression du développeur :', error);
        alert('Erreur lors de la suppression du développeur.');
      }
    }
  };
  
  const openModal = (id) => {
    selectedDeveloperId.value = id;
    showModal.value = true;
  };
  
  onMounted(() => {
    fetchPendingDevelopers();
  });
  </script>
  
  <style scoped>
  /* Vos styles ici */
  </style>
  