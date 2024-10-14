<template>
    <form @submit.prevent="applyFilters">
        <div class="hidden flex-col md:flex lg:flex-row mt-10 mb-8 p-6 border-4 border-blue-400 rounded-lg shadow-md">
            <!-- Section contrat, année et département -->
            <div class="flex flex-col justify-between w-5/6">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-black mb-1">Type de Contrat</label>
                    <div
                        class="flex justify-between w-96 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <div v-for="contract in contracts" :key="contract.id" class="flex items-center mb-2">
                            <input :id="`contract-${contract.id}`" type="checkbox"
                                class="form-checkbox h-4 w-4 text-blue-600" v-model="selectedContracts"
                                :value="contract.id" />
                            <label :for="`contract-${contract.id}`"
                                class="ml-2 block text-sm font-semibold text-gray-800">{{ contract.name }}</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-black mb-1">Années d'Expérience</label>
                    <div
                        class="flex justify-between pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <div v-for="year in yearsExperiences" :key="year.id" class="flex items-center mb-2">
                            <input :id="`year-${year.id}`" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600"
                                v-model="selectedYearsExperiences" :value="year.id" />
                            <label :for="`year-${year.id}`" class="ml-2 block text-sm text-gray-800 font-semibold">{{
                                year.name }}</label>
                        </div>
                    </div>
                </div>

                <div class="w-96">
                    <label for="localisation" class="block text-sm font-bold text-black mb-2 lg:mb-0">Ville</label>
                    <select v-model="selectedLocalisation" id="localisation" name="localisation"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="" disabled selected>
                            Sélectionnez une localisation
                        </option>
                        <option v-for="localisation in localisations" :key="localisation.id" :value="localisation.id">
                            {{ localisation.city }},
                            {{ localisation.postal_code }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Section langages -->
            <div class="flex flex-col justify-between w-full">
                <label for="languages" class="block text-sm font-bold text-black lg:ml-4 mb-4 pt-4 lg:pt-0">Langages de
                    Programmations :</label>
                <div class="overflow-y-scroll h-40">
                    <div class="grid grid-cols-4 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5 lg:ml-4 gap-4">
                        <div v-for="language in programmingLanguages" :key="language.id"
                            class="inline-flex items-center">
                            <input :id="`language-${language.id}`" type="checkbox" class="form-checkbox text-blue-600"
                                v-model="selectedProgrammingLanguages" :value="language.id" />
                            <label :for="`language-${language.id}`" class="ml-2 text-sm font-semibold">{{
                                language.name }}</label>
                        </div>
                    </div>
                </div>

                <!-- Section Type et Btn -->
                <div class="flex justify-between lg:justify-end xl:justify-between mt-2 items-center">
                    <div class="flex lg:hidden xl:block w-full mx-4">
                        <select v-model="selectedDeveloperType"
                            class="lg:mt-1 block w-full pl-3 py-3 lg:py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="" disabled selected>
                                Sélectionnez un type de développeur
                            </option>
                            <option v-for="type in developerTypes" :key="type.id" :value="type.id">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button @click="resetFilters"
                            class="bg-gray-200 hover:bg-gray-300 text-black text-base font-bold py-2 px-4 rounded mr-2">
                            Réinitialiser
                        </button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white text-base font-bold py-2 px-4 rounded">
                            Rechercher
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref, onMounted, defineEmits } from "vue";
import { useResourcesStore } from '../stores/resourcesStore';

const emit = defineEmits();
const resourcesStore = useResourcesStore();

const contracts = ref([]); // Pour stocker les types de contrat
const developerTypes = ref([]); // Pour stocker les types de développeur
const programmingLanguages = ref([]); // Pour stocker les langages de programmation
const localisations = ref([]); // Pour stocker les lieux
const yearsExperiences = ref([]); // Pour stocker les années d'expérience

const selectedContracts = ref([]); // Pour stocker les contrats sélectionnés
const selectedYearsExperiences = ref([]); // Pour stocker les années d'expérience sélectionnées
const selectedDeveloperType = ref(null); // Un seul type de développeur
const selectedProgrammingLanguages = ref([]); // Tableau pour les langages de programmation sélectionnés
const selectedLocalisation = ref(""); // Localisation

const applyFilters = () => {
    emit("filter", {
        contract_id: selectedContracts.value,
        year_id: selectedYearsExperiences.value,
        type_id: selectedDeveloperType.value,
        programming_languages: selectedProgrammingLanguages.value,
        location_id: selectedLocalisation.value,
    });
};

const resetFilters = () => {
    selectedContracts.value = [];
    selectedYearsExperiences.value = [];
    selectedDeveloperType.value = null;
    selectedProgrammingLanguages.value = [];
    selectedLocalisation.value = "";
};

onMounted(async () => {
    await resourcesStore.fetchAllResources(); // Fetch all resources
    // Assign data from the store to local references
    contracts.value = resourcesStore.contracts;
    developerTypes.value = resourcesStore.developerTypes;
    programmingLanguages.value = resourcesStore.programmingLanguages;
    localisations.value = resourcesStore.localisations;
    yearsExperiences.value = resourcesStore.yearsExperiences;
});
</script>
