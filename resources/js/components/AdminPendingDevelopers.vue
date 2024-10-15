<template>
  <div v-if="developers.length">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 ">
      <DeveloperCard v-for="developer in developers" :key="developer.id" :developer="developer" :isAdmin="true"
        @click="openModal(developer.id)" @validate="validateDeveloper" @delete="deleteDeveloper" />
    </div>
    <!-- Pagination -->
    <div v-if="developersPagination.total > developersPagination.per_page" class="mt-4 flex justify-between">
      <button @click="fetchPendingDevelopers(developersPagination.current_page - 1)"
        :disabled="!developersPagination.prev_page_url" class="bg-gray-200 px-4 py-2 rounded disabled:opacity-50">
        Précédent
      </button>
      <span>
        Page {{ developersPagination.current_page }} sur {{ developersPagination.last_page }}
      </span>
      <button @click="fetchPendingDevelopers(developersPagination.current_page + 1)"
        :disabled="!developersPagination.next_page_url" class="bg-gray-200 px-4 py-2 rounded disabled:opacity-50">
        Suivant
      </button>
    </div>
  </div>
  <div v-else>
    <p class="text-center md:text-start text-sm md:text-base">Aucun développeur en attente de validation.</p>
  </div>

  <!-- Modal du développeur -->
  <DeveloperModal :isOpen="showModal" :developerId="selectedDeveloperId" @close="showModal = false" />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DeveloperModal from './DeveloperModal.vue';
import DeveloperCard from './DeveloperCard.vue';
import { useGlobalNotify } from '../notifications/useGlobalNotify';
const notify = useGlobalNotify();

const developers = ref([])
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
    errorMessage.value = 'Erreur lors de la récupération des développeurs en attente.';
  }
};

const validateDeveloper = async (developerId) => {
  if (confirm('Voulez-vous vraiment valider ce développeur ?')) {
    try {
      const response = await axios.post(`/api/admin/developers/${developerId}/validate`);
      fetchPendingDevelopers(developersPagination.value.current_page);
      notify({
        group: 'success-action',
        type: 'success',
        title: 'Succès',
        text: 'Développeur validé avec succès !'
      });
    } catch (error) {
      alert('Erreur lors de la validation du développeur.');
    }
  }
};

const deleteDeveloper = async (developerId) => {
  if (confirm('Voulez-vous vraiment supprimer ce développeur ?')) {
    try {
      await axios.delete(`/api/admin/developers/${developerId}`);
      fetchPendingDevelopers(developersPagination.value.current_page);
      notify({
        group: 'success-action',
        type: 'success',
        title: 'Succès',
        text: 'Développeur supprimé avec succès !'
      });
    } catch (error) {
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
