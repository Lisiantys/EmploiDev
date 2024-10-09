<template>
    <div>
      <h2 class="text-2xl font-bold mt-14 mb-8">Tableau de Bord Administrateur</h2>
  
      <!-- Onglets -->
      <div class="mb-4 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button
            :class="{
              'border-indigo-500 text-indigo-600': activeTab === 'jobOffers',
              'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300':
                activeTab !== 'jobOffers',
            }"
            class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
            @click="activeTab = 'jobOffers'"
          >
            Offres d'emploi en attente
          </button>
          <button
            :class="{
              'border-indigo-500 text-indigo-600': activeTab === 'developers',
              'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300':
                activeTab !== 'developers',
            }"
            class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
            @click="activeTab = 'developers'"
          >
            Développeurs en attente
          </button>
        </nav>
      </div>
  
      <!-- Contenu des onglets -->
      <div>
        <!-- Onglet des offres d'emploi -->
        <div v-if="activeTab === 'jobOffers'">
          <AdminPendingJobOffers />
        </div>
        <!-- Onglet des développeurs -->
        <div v-else-if="activeTab === 'developers'">
          <AdminPendingDevelopers />
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import AdminPendingJobOffers from './AdminPendingJobOffers.vue';
  import AdminPendingDevelopers from './AdminPendingDevelopers.vue';
  
  const activeTab = ref('jobOffers');
  </script>
  
  <style scoped>
  /* Vous pouvez ajouter des styles personnalisés ici */
  </style>
  