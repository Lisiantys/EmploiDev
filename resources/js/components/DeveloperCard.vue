<!-- src/components/DeveloperCard.vue -->
<template>
  <div @click="handleClick" :class="cardClasses"
    class="hover14 cursor-pointer rounded-lg shadow-lg flex flex-col h-full">
    <div class="rounded-t overflow-hidden">
      <figure class="m-0">
        <img :src="profileImage" class="w-full h-48 object-cover object-center" alt="Profile picture" />
      </figure>
    </div>
    <div class="flex-grow">
      <div class="px-6 py-4 bg-gray-100 border-b border-gray-300">
        <h2 class="font-bold text-xl text-gray-800">
          {{ developer.first_name }} {{ developer.surname }}
        </h2>
        <div class="flex items-center space-x-2">
          <img :src="locationImage" class="svg-icon" alt="Location" />
          <p class="text-black text-sm md:text-base">
            {{ developer.location.city }}, {{ developer.location.postal_code }}
          </p>
        </div>
        <p class="text-black text-xs md:text-sm">
          Développeur {{ developer.types_developer.name }}
        </p>
      </div>
      <div class="px-6 py-2 max-h-20 overflow-y-auto">
        <span v-for="language in developer.programming_languages" :key="language.id"
          class="inline-block bg-gray-200 rounded-full px-3 py-1 text-xs md:text-sm font-semibold text-gray-700 mr-2 mb-2">
          #{{ language.name }}
        </span>
      </div>
    </div>
    <!-- Boutons pour Admin -->
    <div v-if="isAdmin" class="flex w-full justify-center ">
      <button @click.stop="validateDeveloper" class="bg-blue-500 w-full text-base text-white px-3 py-2  hover:bg-blue-600">
        Valider
      </button>
      <button @click.stop="deleteDeveloper" class="bg-blue-700 w-full text-base text-white px-3 py-2  hover:bg-blue-800">
        Supprimer
      </button>
    </div>
    <div :class="statusClasses">
      <p class="py-2 rounded-b-full text-sm font-semibold text-white">
        {{ statusText }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, computed, ref } from 'vue';

const props = defineProps({
  developer: {
    type: Object,
    required: true,
  },
  isAdmin: {
    type: Boolean,
    default: false,
  },
});

const emits = defineEmits(['validate', 'delete', 'click']);

const handleClick = () => {
  emits('click', props.developer.id);
};

const validateDeveloper = () => {
  emits('validate', props.developer.id);
};

const deleteDeveloper = () => {
  emits('delete', props.developer.id);
};

const cardClasses = computed(() => {
  return props.developer.is_free
    ? 'border-4 border-green-500'
    : 'border-4 border-red-500';
});

const statusClasses = computed(() => {
  return props.developer.is_free
    ? 'w-full bg-green-500 text-center'
    : 'w-full bg-red-500 text-center';
});

const statusText = computed(() => {
  return props.developer.is_free
    ? `En recherche de ${props.developer.types_contract.name}`
    : `Non disponible`;
});

// Utiliser l'URL générée par le backend
const profileImage = computed(() => {
  return props.developer.profil_image;
});

// Chemin de l'image de localisation
const locationImage = ref('/storage/images/location-solid.svg');
</script>

<style scoped>
.svg-icon {
  width: 16px;
  height: 16px;
  filter: invert(0%) sepia(0%) saturate(0%);
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

.max-h-20 {
  max-height: 100px; /* Vous pouvez ajuster cette valeur selon votre mise en page */
  overflow-y: auto; /* Activer le défilement si nécessaire */
}
</style>
