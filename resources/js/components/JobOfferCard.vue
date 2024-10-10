<template>
    <div @click="handleClick"
        class="bg-white mt-10 shadow-lg w-full max-w-4xl flex flex-col sm:flex-row gap-3 sm:items-center justify-between px-5 py-4 rounded-md cursor-pointer">
        <div class="w-full">
            <div class="flex justify-between">
                <h2 class="font-bold mt-px text-blue-800">{{ job.name }}</h2>
                <button class="bg-blue-500 text-white font-medium p-2 rounded-3xl flex gap-1 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
            <div class="flex items-center gap-3">
                <span class="bg-blue-500 text-white rounded-full px-3 py-1 text-sm">{{
                    job.types_contract?.name || job.typesContract?.name
                }}</span>
                <span class="text-blue-500 text-sm">Développeur {{
                    job.types_developer?.name || job.typesDeveloper?.name
                }}</span>
            </div>
            <hr class="mt-2" />
            <div class="pt-2 flex flex-row flex-wrap">
                <span v-for="language in job.programming_languages || []" :key="language.id"
                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
                    #{{ language.name }}
                </span>
            </div>
            <div class="py-2 flex flex-row flex-wrap justify-between">
                <span class="text-slate-600 text-sm flex gap-1 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ job.location?.city }}, {{ job.location?.postal_code }}
                </span>
                <p class="text-slate-600 text-sm">{{ timeAgo(job.created_at) }}</p>
            </div>
        </div>
        <!-- Boutons pour Admin -->
        <div v-if="isAdmin" class="flex justify-between px-4 py-2">
            <button @click.stop="handleValidate" class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">
                Valider
            </button>
            <button @click.stop="handleDelete" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">
                Supprimer
            </button>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
    isAdmin: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(["click", "validate", "delete"]);

const handleClick = () => {
    emits("click", props.job.id);
};

const handleValidate = (event) => {
    event.stopPropagation();
    emits("validate", props.job.id);
};

const handleDelete = (event) => {
    event.stopPropagation();
    emits("delete", props.job.id);
};

const timeAgo = (date) => {
    const seconds = Math.floor((new Date() - new Date(date)) / 1000);
    let interval = Math.floor(seconds / 31536000);
    if (interval > 1) return `il y a ${interval} ans`;
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) return `il y a ${interval} mois`;
    interval = Math.floor(seconds / 86400);
    if (interval > 1) return `il y a ${interval} jours`;
    interval = Math.floor(seconds / 3600);
    if (interval > 1) return `il y a ${interval} heures`;
    interval = Math.floor(seconds / 60);
    if (interval > 1) return `il y a ${interval} minutes`;
    return `il y a quelques secondes`;
};
</script>

<style scoped>
/* Vos styles ici */
</style>