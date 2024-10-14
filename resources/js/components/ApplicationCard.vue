<template>
  <div class="application-card bg-white shadow-lg rounded-lg p-6 mb-2 hover:shadow-xl transition-shadow cursor-pointer"
    @click="handleClick">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="text-xl font-semibold text-gray-800">{{ title }}</h3>
        <p class="text-gray-600 text-sm md:text-base font-semibold">{{ subtitle }}</p>
      </div>
      <div v-if="statusBadge" :class="statusBadgeClass" class="px-3 py-1 rounded-full text-xs md:text-sm font-medium">
        {{ statusBadge }}
      </div>
    </div>

    <div class="text-wrap break-words text-xs md:text-sm xl:text-base font-light overflow-y-auto max-h-16">
      <p>{{ description }}</p>
    </div>

    <!-- Boutons de téléchargement (pour entreprise) -->
    <div v-if="showDownloadButtons" class="mt-4 flex space-x-4">
      <a :href="application.cv_url || `/storage/${application.cv}`" target="_blank" rel="noopener noreferrer"
        class="flex items-center text-base font-normal bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition-colors"
        @click.stop>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        CV
      </a>
      <a :href="application.cover_letter_url || `/storage/${application.cover_letter}`" target="_blank"
        rel="noopener noreferrer"
        class="flex items-center bg-blue-500 text-base font-normal text-white px-2 py-1 rounded hover:bg-blue-600 transition-colors"
        @click.stop>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Lettre de Motivation
      </a>
    </div>

    <!-- Actions -->
    <div class="mt-2 flex items-center space-x-4">
      <!-- Bouton Supprimer pour le développeur -->
      <button v-if="isDeveloper" @click.stop="handleDelete"
        class="flex items-center text-sm md:text-base font-normal px-2 py-1 text-red-500 hover:text-red-600 transition-colors border-2 rounded-lg border-red-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Supprimer
      </button>

      <!-- Boutons Accepter/Refuser pour l'entreprise -->
      <div v-else-if="isCompany" class="flex space-x-4">
        <button v-if="showAcceptButton" @click.stop="handleAccept"
          class="flex items-center bg-green-500 text-base font-normal text-white px-2 py-1 rounded hover:bg-green-600 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h- w-5 mr-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Accepter
        </button>
        <button v-if="showRejectButton" @click.stop="handleReject"
          class="flex items-center bg-red-500 text-base font-normal text-white px-2 py-1 rounded hover:bg-red-600 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Refuser
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';

const props = defineProps({
  application: {
    type: Object,
    required: true,
  },
  isDeveloper: {
    type: Boolean,
    default: false,
  },
  isCompany: {
    type: Boolean,
    default: false,
  },
});

const emits = defineEmits(['delete', 'accept', 'reject', 'click']);

const title = computed(() => {
  if (props.isDeveloper) {
    return props.application.job_offer.name || 'Offre sans nom';
  } else {
    const dev = props.application.developer;
    return dev ? `${dev.first_name} ${dev.surname}` : 'Développeur inconnu';
  }
});

const subtitle = computed(() => {
  if (props.isDeveloper) {
    return props.application.job_offer.company.name || 'Entreprise inconnue';
  } else {
    return props.application.developer.user.email || 'Email non disponible';
  }
});

const description = computed(() => props.application.description || 'Aucune description fournie.');

const statusBadge = computed(() => {
  switch (props.application.status) {
    case 'accepted':
      return 'Acceptée';
    case 'pending':
      return 'En attente';
    case 'rejected':
      return 'Refusée';
    default:
      return null;
  }
});

const statusBadgeClass = computed(() => {
  switch (props.application.status) {
    case 'accepted':
      return 'bg-green-100 text-green-800';
    case 'pending':
      return 'bg-yellow-100 text-yellow-800';
    case 'rejected':
      return 'bg-red-100 text-red-800';
    default:
      return '';
  }
});

const showAcceptButton = computed(() => props.isCompany && props.application.status === 'pending');
const showRejectButton = computed(() => props.isCompany && props.application.status === 'pending');

const showDownloadButtons = computed(() => props.isCompany);

const handleDelete = () => {
  emits('delete', props.application.id);
};

const handleAccept = () => {
  emits('accept', props.application.id);
};

const handleReject = () => {
  emits('reject', props.application.id);
};

const handleClick = () => {
  emits('click', props.application.id);
};
</script>

<style scoped>
.application-card {
  transition: box-shadow 0.3s ease;
}

.application-card:hover {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}
</style>