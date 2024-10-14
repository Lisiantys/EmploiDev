<template>
    <div class="mt-20 mb-5 flex items-center justify-center bg-gray-100">
        <div class="flex flex-col lg:flex-row w-full max-w-6xl bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Colonne de gauche : Sélection du rôle -->
            <div class="w-full lg:w-1/2 p-8">
                <!-- Sélection du rôle -->
                <div v-if="!currentComponent">
                    <div class="flex flex-col space-y-4 items-center">
                        <button @click="selectRole('developer')"
                            class="w-full flex items-center justify-between bg-blue-500 text-white py-4 px-6 rounded-md shadow hover:bg-blue-700 transition duration-200 mt-0 md:mt-4 mx-auto">
                            <p class="text-sm md:text-lg lg:text-xl font-normal">S'inscrire en tant que Développeur</p>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                        <button @click="selectRole('company')"
                            class="w-full flex items-center justify-between bg-blue-600 text-white py-4 px-6 rounded-md shadow hover:bg-blue-700 transition duration-200 mx-auto">
                            <p class="text-sm md:text-lg lg:text-xl font-normal">S'inscrire en tant qu'Entreprise</p>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Formulaire d'inscription dynamique -->
                <component :is="currentComponent" v-if="currentComponent" @back="resetForm" />
            </div>
            <!-- Colonne de droite : Texte explicatif -->
            <div class="flex w-full lg:w-1/2 bg-gradient-to-r from-blue-500 to-blue-600 p-8 items-center">
                <div>
                    <h2 class="text-2xl lg:text-3xl font-bold text-white mb-6">Bienvenue sur EmploiDev !</h2>
                    <p class="text-white text-sm lg:text-base">
                        Créez un compte pour profiter de toutes les fonctionnalités
                        que nous offrons. Que vous soyez un développeur à la recherche de nouvelles opportunités ou une
                        entreprise à la recherche de talents, notre plateforme est là pour vous aider.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { markRaw } from 'vue'; // Import markRaw
import RegisterDeveloper from './RegisterDeveloper.vue';
import RegisterCompany from './RegisterCompany.vue';

const currentComponent = ref(null);

const selectRole = (role) => {
    if (role === 'developer') {
        currentComponent.value = markRaw(RegisterDeveloper); // Use markRaw to prevent reactivity
    } else if (role === 'company') {
        currentComponent.value = markRaw(RegisterCompany); // Use markRaw to prevent reactivity
    }
};

const resetForm = () => {
    currentComponent.value = null;
};
</script>
