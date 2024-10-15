<template>
    <div class="w-full max-w-md bg-white shadow-md rounded-lg mt-16 p-6 m-auto text-center">
        <div class="block mb-6">
            <PageTitle title="// Page introuvable" />
        </div>
        
        <p class="text-gray-900 text-sm mb-6">La page que vous recherchez n'existe pas.</p>

        <div class="flex justify-center">
            <router-link :to="{ name: 'home' }"
               class="border border-blue-500 bg-blue-500 text-white font-normal text-sm md:text-base rounded-lg px-4 py-2 hover:bg-blue-600 transition">
                Retour à l'accueil
            </router-link>
        </div>
    </div>
</template>

<script setup>
import PageTitle from './PageTitle.vue';
</script>
