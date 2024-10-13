<template>
    <div id="searchModal" :class="[{ active: isOpen }]"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-0 mx-auto p-5 shadow-lg rounded-md bg-white border-4 w-11/12 border-blue-400">
            <div @click="closeModal"
                class="absolute cursor-pointer bg-blue-500 rounded-bl-md h-10 w-10 flex items-center justify-center right-0 top-0 shadow-lg">
                <div class="absolute bg-white h-1 w-5 transform -rotate-45"
                    style="margin-top: -0.125rem; left: 50%; margin-left: -0.625rem; transition: all 0.3s ease;">
                </div>
                <div class="absolute bg-white h-1 w-5 transform rotate-45"
                    style="margin-top: -0.125rem; left: 50%; margin-left: -0.625rem; transition: all 0.3s ease;">
                </div>
            </div>

            <!-- Formulaire de filtrage -->
            <form @submit.prevent="applyFilters">
                <div class="flex-col flex">
                    <div class="flex flex-col justify-between">
                        <!-- Type de Contrat -->
                        <div>
                            <label class="block text-sm font-bold text-black">Type de Contrat</label>
                            <div class="flex flex-wrap justify-between py-2 text-base rounded-md">
                                <div v-for="contract in contracts" :key="contract.id" class="flex items-center mb-2">
                                    <input :id="`contract-${contract.id}`" type="checkbox"
                                        class="form-checkbox h-4 w-4 text-blue-600" v-model="selectedContracts"
                                        :value="contract.id" />
                                    <label :for="`contract-${contract.id}`"
                                        class="px-4 block text-xs font-semibold text-gray-800">
                                        {{ contract.name }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Années d'expériences -->
                        <div>
                            <label class="block text-sm font-bold text-black">Années d'expériences</label>
                            <div class="flex flex-wrap justify-between py-2 text-base rounded-md">
                                <div v-for="year in yearsExperiences" :key="year.id" class="flex items-center mb-2">
                                    <input :id="`year-${year.id}`" type="checkbox"
                                        class="form-checkbox h-4 w-4 text-blue-600" v-model="selectedYearsExperiences"
                                        :value="year.id" />
                                    <label :for="`year-${year.id}`"
                                        class="px-4 block text-xs font-semibold text-gray-800">
                                        {{ year.name }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Localisation -->
                        <div class="flex flex-col">
                            <label for="localisation" class="block text-sm font-bold text-black mb-1">Villes</label>
                            <select v-model="selectedLocalisation" id="localisation" name="localisation"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-xs rounded-md border ring-blue-500 border-blue-500">
                                <option value="" disabled selected>Sélectionnez une localisation</option>
                                <option v-for="localisation in localisations" :key="localisation.id"
                                    :value="localisation.id">
                                    {{ localisation.city }}, {{ localisation.postal_code }}
                                </option>
                            </select>
                        </div>

                        <!-- Type de développeur -->
                        <div class="flex flex-col mt-4">
                            <label for="localisation" class="block text-sm font-bold text-black mb-1">Domaines</label>
                            <select v-model="selectedDeveloperType" id="type-developpeur" name="type-developpeur"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-xs rounded-md border ring-blue-500 border-blue-500">
                                <option value="" disabled selected>Sélectionnez un type de développeur</option>
                                <option v-for="type in developerTypes" :key="type.id" :value="type.id">
                                    {{ type.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Langages de Programmation -->
                    <div class="flex flex-col justify-between w-full">
                        <label class="block text-sm font-bold text-black mb-4 pt-4">Langages de Programmations :</label>

                        <div class="overflow-y-scroll h-40">
                            <div class="grid grid-cols-2 gap-4">
                                <div v-for="language in programmingLanguages" :key="language.id"
                                    class="inline-flex items-center">
                                    <input :id="`language-${language.id}`" type="checkbox"
                                        class="form-checkbox text-blue-600" v-model="selectedProgrammingLanguages"
                                        :value="language.id" />
                                    <label :for="`language-${language.id}`" class="ml-2 text-xs font-semibold">
                                        {{ language.name }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="flex justify-center mt-2">
                            <div class="flex justify-end">
                                <button type="button" @click="resetFilters"
                                    class="bg-gray-200 hover:bg-gray-300 text-black text-sm font-bold py-1 px-2 rounded mr-2">
                                    Réinitialiser
                                </button>
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-1 px-2 rounded">
                                    Rechercher
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, defineEmits } from "vue";
import { useResourcesStore } from '../stores/resourcesStore';

const emit = defineEmits(['close', 'filter']);
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

const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true,
    },
});

const closeModal = () => {
    emit('close');
};

const applyFilters = () => {
    emit("filter", {
        contract_id: selectedContracts.value,
        year_id: selectedYearsExperiences.value,
        type_id: selectedDeveloperType.value,
        programming_languages: selectedProgrammingLanguages.value,
        location_id: selectedLocalisation.value,
    });
    closeModal();
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

<style scoped>
#searchModal {
    /* Position initiale - centré mais non visible */
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    /* Empêche les interactions lorsque le modal est caché */
    transition: opacity 0.5s ease-in-out;
}

#searchModal.active {
    /* Modal visible et centré */
    opacity: 1;
    pointer-events: auto;
    /* Permet les interactions lorsque le modal est visible */
}
</style>
