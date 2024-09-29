<template>
    <form @submit.prevent="nextStep">
        <h2>Inscription Développeur</h2>

        <div v-if="step === 1" class="tab">
            <div class="mb-5">
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prénom...</label>
                <input v-model="developer.first_name" id="first_name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Prénom..." required />
            </div>
            <div class="mb-5">
                <label for="surname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom...</label>
                <input v-model="developer.surname" id="surname" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Nom..." required />
            </div>
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-mail...</label>
                <input v-model="developer.email" id="email" type="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="E-mail..." required />
            </div>
        </div>

        <div v-if="step === 2" class="tab">
            <div class="mb-5">
                <label for="profil_image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image de profil (optionnel)</label>
                <input type="file" @change="handleFileUpload('profil_image', $event)" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" />
            </div>
            <div class="mb-5">
                <label for="cv" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Importer votre CV</label>
                <input type="file" @change="handleFileUpload('cv', $event)" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" />
            </div>
            <div class="mb-5">
                <label for="coverLetter" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Importer votre lettre de motivation</label>
                <input type="file" @change="handleFileUpload('coverLetter', $event)" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" />
            </div>
        </div>

        <div v-if="step === 3" class="tab">
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type de Contrat</label>
                <select v-model="developer.contract_id" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Sélectionnez un type de contrat</option>
                    <option v-for="contract in contracts" :key="contract.id" :value="contract.id">{{ contract.name }}</option>
                </select>
            </div>
            <div class="mb-5">
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Langages de Programmation</label>
    <div class="flex flex-col overflow-y-auto h-40"> <!-- Ajout d'un défilement et d'une hauteur fixe -->
        <div v-for="language in programmingLanguages" :key="language.id" class="flex items-center mb-2">
            <input type="checkbox" :id="`language-${language.id}`" class="form-checkbox h-4 w-4 text-blue-600" v-model="developer.programming_languages" :value="language.id" />
            <label :for="`language-${language.id}`" class="ml-2 text-sm font-semibold">{{ language.name }}</label>
        </div>
    </div>
</div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type de Développeur</label>
                <select v-model="developer.type_id" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Sélectionnez un type de développeur</option>
                    <option v-for="type in developerTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                </select>
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ville</label>
                <select v-model="developer.location_id" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Sélectionnez une ville</option>
                    <option v-for="location in localisations" :key="location.id" :value="location.id">{{ location.city }}, {{ location.postal_code }}</option>
                </select>
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Année d'Expérience</label>
                <select v-model="developer.year_id" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Sélectionnez une année d'expérience</option>
                    <option v-for="year in yearsExperiences" :key="year.id" :value="year.id">{{ year.name }}</option>
                </select>
            </div>
        </div>

        <div v-if="step === 4" class="tab">
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mot de passe...</label>
                <input v-model="developer.password" id="password" type="password" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-5">
                <label for="confirmPassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmer le mot de passe...</label>
                <input v-model="developer.confirmPassword" id="confirmPassword" type="password" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-5">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description (optionnel)</label>
                <textarea v-model="developer.description" id="description" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Une brève description de vous-même..."></textarea>
            </div>
            <div class="flex items-start mb-5">
                <div class="flex items-center h-5">
                    <input id="terms" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300" required />
                </div>
                <label for="terms" class="ms-2 text-sm font-medium text-gray-900">J'accepte les <a href="#" class="text-blue-600 hover:underline">termes et conditions</a></label>
            </div>
        </div>

        <div class="flex justify-between">
            <button type="button" @click="prevStep" v-if="step > 1">Précédent</button>
            <button type="submit" v-if="step < 4">Suivant</button>
            <button type="submit" v-if="step === 4">S'inscrire</button>
        </div>
    </form>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Axios from "axios";


const step = ref(1);
const developer = ref({
    first_name: '',
    surname: '',
    email: '',
    phone: '',
    password: '',
    confirmPassword: '',
    cv: null,
    coverLetter: null,
    profil_image: null,
    description: '',
    contract_id: null,
    programming_languages: [],
    type_id: null,
    location_id: null,
    year_id: null
});

// Ajouter ici vos références pour les contrats, langages, types de développeurs, localisations et années d'expérience
const contracts = ref([]);
const programmingLanguages = ref([]);
const developerTypes = ref([]);
const localisations = ref([]);
const yearsExperiences = ref([]);

const nextStep = () => {
    if (step.value < 4) {
        step.value++;
    } else {
        // Handle the final submission for developer registration
        console.log('Inscription Développeur:', developer.value);
        // Here you would typically send the data to your API
    }
};

const prevStep = () => {
    if (step.value > 1) {
        step.value--;
    }
};

const handleFileUpload = (field, event) => {
    developer.value[field] = event.target.files[0];
};

const fetchContracts = async () => {
    try {
        const response = await Axios.get("/api/types-contracts");
        contracts.value = response.data;
    } catch (error) {
        console.error("Failed to fetch contracts:", error);
    }
};

const fetchDeveloperTypes = async () => {
    try {
        const response = await Axios.get("/api/types-developers");
        developerTypes.value = response.data;
    } catch (error) {
        console.error("Failed to fetch developer types:", error);
    }
};

const fetchProgrammingLanguages = async () => {
    try {
        const response = await Axios.get("/api/programming-languages");
        programmingLanguages.value = response.data;
    } catch (error) {
        console.error("Failed to fetch programming languages:", error);
    }
};

const fetchLocations = async () => {
    try {
        const response = await Axios.get("/api/locations");
        localisations.value = response.data;
    } catch (error) {
        console.error("Failed to fetch locations:", error);
    }
};

const fetchYearsExperiences = async () => {
    try {
        const response = await Axios.get("/api/years-experiences");
        yearsExperiences.value = response.data;
    } catch (error) {
        console.error("Failed to fetch years of experience:", error);
    }
};

onMounted(() => {
    fetchContracts();
    fetchDeveloperTypes();
    fetchProgrammingLanguages();
    fetchLocations();
    fetchYearsExperiences();
});
</script>

