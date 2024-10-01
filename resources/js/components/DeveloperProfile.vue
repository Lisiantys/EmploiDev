<template>
    <div class="p-6 sm:p-10 text-2xl font-bold md:pl-32">
        <div class="p-6 sm:p-10">
        <div class="flex flex-col gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Prénom:</label>
                <input v-model="developer.first_name" placeholder="Prénom..." required
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Nom:</label>
                <input v-model="developer.surname" placeholder="Nom..." required
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">E-mail:</label>
                <input v-model="user.email" type="email" placeholder="E-mail..." required
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
            </div>
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Type de Contrat:</label>
                <select v-model="developer.contract_id"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled>Sélectionnez un type de contrat</option>
                    <option v-for="contract in contracts" :key="contract.id" :value="contract.id">{{ contract.name }}</option>
                </select>
            </div>
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Langages de Programmation:</label>
                <div class="flex flex-col">
                    <div v-for="language in programmingLanguages" :key="language.id" class="flex items-center mb-2">
                        <input type="checkbox" :id="`language-${language.id}`"
                            v-model="developer.programming_languages" 
                            :value="language.id" />
                        <label :for="`language-${language.id}`" class="ml-2 text-sm font-semibold">{{ language.name }}</label>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Type de Développeur:</label>
                <select v-model="developer.type_id"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled>Sélectionnez un type de développeur</option>
                    <option v-for="type in developerTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                </select>
            </div>
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Ville:</label>
                <select v-model="developer.location_id"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled>Sélectionnez une ville</option>
                    <option v-for="location in localisations" :key="location.id" :value="location.id">{{ location.city }}, {{ location.postal_code }}</option>
                </select>
            </div>
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Année d'Expérience:</label>
                <select v-model="developer.year_id"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled>Sélectionnez une année d'expérience</option>
                    <option v-for="year in yearsExperiences" :key="year.id" :value="year.id">{{ year.name }}</option>
                </select>
            </div>
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Mot de passe:</label>
                <input v-model="developer.password" type="password" placeholder="Mot de passe" required
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
            </div>
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Confirmer le mot de passe:</label>
                <input v-model="developer.confirmPassword" type="password" placeholder="Confirmer le mot de passe" required
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                <div v-if="passwordsDoNotMatch" class="text-red-500 mb-2">Les mots de passe ne correspondent pas.</div>
            </div>

            <button @click="submitProfile" class="bg-blue-500 text-white p-2 rounded">Sauvegarder</button>
        </div>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useResourcesStore } from '../stores/resourcesStore'; // Assurez-vous que le chemin est correct
import Axios from 'axios';

const user = ref({ first_name: '', surname: '', email: '' });
const developer = ref({
    contract_id: '',
    programming_languages: [],
    type_id: '',
    location_id: '',
    year_id: '',
    password: '',
    confirmPassword: '',
});

// Définition des ressources
const resourcesStore = useResourcesStore();
const contracts = resourcesStore.contracts;
const programmingLanguages = resourcesStore.programmingLanguages;
const developerTypes = resourcesStore.developerTypes;
const localisations = resourcesStore.localisations;
const yearsExperiences = resourcesStore.yearsExperiences;

// Propriété pour vérifier si les mots de passe correspondent
const passwordsDoNotMatch = ref(false);

// Watcher pour vérifier les mots de passe
watch(
    () => developer.value.password,
    (newPassword) => {
        passwordsDoNotMatch.value = newPassword !== developer.value.confirmPassword;
    }
);

watch(
    () => developer.value.confirmPassword,
    (newConfirmPassword) => {
        passwordsDoNotMatch.value = developer.value.password !== newConfirmPassword;
    }
);

const fetchUserProfile = async () => {
    try {
        const response = await Axios.get('/api/profile');
        console.log('Request Headers:', response.config.headers);
        user.value = response.data.user; 
        developer.value = response.data.developer; 
    } catch (error) {
        console.error('Failed to fetch user profile:', error.response);
    }
};

const submitProfile = async () => {
    // Logique pour soumettre les données de profil
    console.log('Submitting Profile:', user.value, developer.value);
};

onMounted(() => {
    fetchUserProfile();
});
</script>

<style scoped>
/* Aucune style pour l'instant */
</style>
