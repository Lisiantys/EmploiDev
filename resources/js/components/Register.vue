<template>
    <div class="w-full max-w-md bg-white shadow-md rounded-lg p-6 m-auto pt-48">
        <div v-if="!currentComponent">
            <div class="flex flex-col sm:flex-row mb-4">
                <button @click="selectRole('developer')"
                    class="bg-blue-500 text-white font-semibold py-2 px-6 rounded-md shadow hover:bg-blue-600 transition duration-200 mb-4 sm:mb-0 sm:mr-4">
                    S'inscrire en tant que Développeur
                </button>
                <button @click="selectRole('company')"
                    class="bg-green-500 text-white font-semibold py-2 px-6 rounded-md shadow hover:bg-green-600 transition duration-200">
                    S'inscrire en tant qu'Entreprise
                </button>
            </div>
        </div>

        <component :is="currentComponent" v-if="currentComponent" @back="resetForm" />
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

<style scoped>
/* Add styles for your buttons and the form container here */
</style>
