<template>
    <form @submit.prevent="handleStepSubmission">
        <div>
            <PageTitle title="// Inscription entreprise" class="mb-6" />
        </div>

        <div v-if="step === 1" class="tab">
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nom de l'entreprise...</label>
                <input v-model="company.name" id="name"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required minlength="2" maxlength="50" />
            </div>
            <div class="mb-5">
                <label for="profil_image" class="block mb-2 text-sm font-medium text-gray-900">Image de profil
                    (optionnel)</label>
                <input type="file" accept=".jpg,.png" @change="handleFileUpload('profil_image', $event)"
                    class="block w-full text-sm text-gray-900 border border-blue-500 rounded-lg cursor-pointer bg-gray-50" />
                <span v-if="company.profil_image" class="text-sm">{{ company.profil_image.name }}</span>
            </div>
            <div class="mb-5">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description
                    (optionnel)</label>
                <textarea v-model="company.description" id="description"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-blue-500 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Une brève description de votre entreprise..." minlength="10" maxlength="255"
                    rows="6"></textarea>
            </div>
        </div>

        <div v-if="step === 2" class="tab">
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">E-mail...</label>
                <input v-model="company.email" id="email" type="email"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required maxlength="255" />
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Mot de passe...</label>
                <input v-model="company.password" id="password" type="password"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required minlength="8" />
            </div>
            <div class="mb-5">
                <label for="confirmPassword" class="block mb-2 text-sm font-medium text-gray-900">Confirmer le mot de
                    passe...</label>
                <input v-model="company.confirmPassword" id="confirmPassword" type="password"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required minlength="8" />
            </div>

            <div v-if="passwordsDoNotMatch" class="text-red-500 mb-5 text-xs md:text-base font-normal">
                Les mots de passe ne correspondent pas.
            </div>

            <div class="flex items-start mb-5">
                <div class="flex items-center h-5">
                    <input id="terms" type="checkbox" value=""
                        class="w-4 h-4 border border-blue-500 backdrop:rounded bg-gray-50 focus:ring-3 focus:ring-blue-300"
                        required />
                </div>
                <label for="terms" class="ms-2 text-sm font-medium text-gray-900">J'accepte les <router-link :to="{ name: 'politiqueConfidentialite' }"
                        class="text-blue-600 hover:underline">termes et conditions</router-link></label>
                        

            </div>

            <div v-if="Object.keys(company.errors).length > 0"
                class="mb-5 p-4 border border-red-500 rounded bg-red-100">
                <ul class="list-disc list-inside">
                    <li v-for="(errorMessages, field) in company.errors" :key="field" class="text-red-500 text-sm">
                        {{ errorMessages.join(', ') }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex justify-between">
            <!-- Bouton Retour -->
            <button type="button" @click="prevStep" v-if="step >= 1"
                class="border-2 border-blue-500 text-blue-500 bg-transparent text-base rounded-lg px-2 py-1 hover:bg-blue-50 transition">
                Retour
            </button>

            <!-- Bouton Suivant -->
            <button type="submit" v-if="step < 4"
                class="border-10  border-blue-500 bg-blue-500 text-white text-base rounded-lg px-2 py-1 hover:bg-blue-600 transition">
                Suivant
            </button>

            <!-- Bouton S'inscrire -->
            <button type="submit" v-if="step === 4"
                class="border border-blue-500 bg-blue-500 text-white text-base rounded-lg px-2 py-1 hover:bg-blue-600 transition">
                S'inscrire
            </button>
        </div>
    </form>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import Axios from "axios";
import { useAuthStore } from '../stores/authStore';
import PageTitle from './PageTitle.vue';

const router = useRouter();
const authStore = useAuthStore();
const emit = defineEmits(['back']);

const company = ref({
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
    profil_image: null,
    description: '',
    errors: {}
});

// Watchers pour vérifier la correspondance des mots de passe
watch(
    () => company.value.password,
    (newPassword) => {
        passwordsDoNotMatch.value = newPassword !== company.value.confirmPassword;
    }
);

watch(
    () => company.value.confirmPassword,
    (newConfirmPassword) => {
        passwordsDoNotMatch.value = company.value.password !== newConfirmPassword;
    }
);

const step = ref(1);
const passwordsDoNotMatch = ref(false);

const handleStepSubmission = () => {
    if (step.value === 2) {
        // Si les mots de passe ne correspondent pas, afficher une erreur et ne pas continuer
        if (passwordsDoNotMatch.value) {
            company.value.errors = {
                password: ["Les mots de passe ne correspondent pas."],
            };
            return;
        } else {
            // Réinitialiser les erreurs si les mots de passe correspondent
            company.value.errors = {};
        }
    }
    if (step.value < 2) {
        step.value++;
    } else {
        registerCompany();
    }
};


const prevStep = () => {
    if (step.value > 1) {
        step.value--;
    } else if (step.value === 1) {
        emit('back');
    }
};

const handleFileUpload = (field, event) => {
    company.value[field] = event.target.files[0];
};

const registerCompany = async () => {
    try {
        const formData = new FormData();

        // Ajoutez toutes les propriétés de company à formData
        Object.entries(company.value).forEach(([key, value]) => {
            formData.append(key, value);
        });

        await Axios.get('/csrf-cookie');
        const response = await Axios.post('/api/companies', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        console.log('Inscription réussie:', response.data);
        authStore.setUser(response.data.user);
        router.push('/'); // Redirection vers la page d'accueil après l'inscription réussie
    } catch (error) {
        if (error.response && error.response.status === 422) {
            company.value.errors = error.response.data.errors;
        } else {
            console.error('Erreur lors de l\'inscription:', error);
        }
    }
};
</script>

<style scoped>
textarea {
    resize: none;
}
</style>