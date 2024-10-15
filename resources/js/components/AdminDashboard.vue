<template>
  <div>
    <div class="hidden md:block">
      <PageTitle title="// Tableau de Bord Administrateur" class="mt-10 mb-6" />
    </div>

    <!-- Onglets -->
    <div class="mt-20 md:mt-0 mb-4 border-b border-gray-200">
      <nav class="-mb-px flex flex-col md:flex-row items-center gap-6 md:space-x-8">
        <button :class="{
          'border-blue-500 text-blue-600': activeTab === 'jobOffers',
          'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300':
            activeTab !== 'jobOffers',
        }" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm" @click="activeTab = 'jobOffers'">
          Offres d'emploi en attente
        </button>
        <button :class="{
          'border-blue-500 text-blue-600': activeTab === 'developers',
          'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300':
            activeTab !== 'developers',
        }" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm" @click="activeTab = 'developers'">
          Développeurs en attente
        </button>
      </nav>
    </div>

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
import PageTitle from './PageTitle.vue';

const activeTab = ref('jobOffers');
</script>