<template>
    <div class="p-6 sm:p-10 text-2xl font-bold md:pl-32">
        <Form @filter="fetchJobOffers" />
        <div v-if="jobOffers.length">
            <h3>Jobs</h3>
            <div class="grid  lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-20 md:mt-10">

                <div v-for="job in jobOffers" :key="job.id"
                    class="bg-white mt-10 shadow-lg w-full max-w-4xl flex flex-col sm:flex-row gap-3 sm:items-center  justify-between px-5 py-4 rounded-md cursor-pointer">
                    <div class="w-full">
                        <div class="flex justify-between">
                            <h3 class="font-bold mt-px text-blue-800">{{ job.name }}</h3>
                            <button class="bg-blue-500 text-white font-medium p-2 rounded-3xl flex gap-1 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>


                        <div class="flex items-center gap-3">
                            <span
                                class="bg-blue-500 text-white rounded-full px-3 py-1 text-sm">{{ job.types_contract.name }}</span>
                            <span class="text-blue-500 text-sm">Développeur {{ job.types_developer.name }}</span>
                        </div>
                        <hr class="mt-2">
                        <div class="pt-2 flex flex-row flex-wrap">
                            <span v-for="language in job.programming_languages" :key="language.id"
                                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 ">
                                #{{ language.name }}
                            </span>
                        </div>
                        <div class=" py-2 flex flex-row flex-wrap justify-between">


                        <span class="text-slate-600 text-sm flex gap-1 items-center"> <svg
                                xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

                            </svg> {{ job.location.city }}, {{ job.location.postal_code }}</span>
                            <p class="text-slate-600 text-sm">{{ timeAgo(job.created_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <div v-if="totalPages > 1" class="mt-4 flex justify-between">
                <button
                    @click="previousPage"
                    :disabled="currentPage === 1"
                    class="bg-gray-200 px-4 py-2 rounded"
                >
                    Previous
                </button>
                <span>Page {{ currentPage }} of {{ totalPages }}</span>
                <button
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                    class="bg-gray-200 px-4 py-2 rounded"
                >
                    Next
                </button>
            </div>
        </div>
        <div v-else>
            <p>Aucune offre d'emploi trouvée.</p>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from "vue";
import Axios from "axios";
import Form from "./FilterForm.vue";

const jobOffers = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);

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

const fetchJobOffers = async (filters = {}, page = 1) => {
    try {
        const params = { ...filters, page: page };
        const response = await Axios.get("/api/job-offers/filter", { params });
        jobOffers.value = response.data.data;
        totalPages.value = response.data.last_page;
        currentPage.value = response.data.current_page;
    } catch (error) {
        console.error("Failed to fetch jobs:", error.response ? error.response.data : error);
    }
};


onMounted(() => fetchJobOffers());

const previousPage = () => {
    if (currentPage.value > 1) {
        fetchJobOffers({}, currentPage.value - 1);
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        fetchJobOffers({}, currentPage.value + 1);
    }
};
</script>
