<template>
    <div
        class="fixed top-0 left-0 w-full h-20 md:hidden flex items-center justify-between bg-gradient-to-r from-blue-400 to-blue-600 z-50 px-4">
        <router-link :to="{ name: 'home' }" class="flex items-center h-full">
            <img class="w-14" :src="`${baseImageUrl}logo.webp`" alt="Logo" />
        </router-link>

        <div class="flex items-center">
            <h1 class="text-white text-xl font-bold">{{ pageTitle }}</h1>
        </div>

        <!-- Conteneur toujours présent -->
        <div class="flex items-center">
            <!-- Afficher le bouton ou un espace réservé -->
            <button v-if="showFilterButton" @click="openFilterModal" class="text-white p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>
            <!-- Espace réservé lorsque le bouton n'est pas affiché -->
            <div v-else class="p-2" style="width: 40px;"></div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute } from 'vue-router';

const baseImageUrl = ref('/storage/images/');
const route = useRoute();

const pageTitle = computed(() => route.meta.title || 'EmploiDev');

const showFilterButton = computed(() => {
    const currentRouteName = route.name;
    return ['home', 'job'].includes(currentRouteName);
});

// Émettre un événement pour ouvrir le modal
const openFilterModal = () => {
    window.dispatchEvent(new Event('openFilterModal'));
};
</script>