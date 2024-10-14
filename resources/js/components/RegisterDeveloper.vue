<template>
    <form @submit.prevent="handleStepSubmission">
        <div class="block">
            <PageTitle title="// Inscription développeur" class="mb-6" />
        </div>

        <div v-if="step === 1" class="tab">
            <div class="mb-5">
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">Prénom...</label>
                <input v-model="developer.first_name" id="first_name"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required minlength="2" maxlength="30" />
            </div>
            <div class="mb-5">
                <label for="surname" class="block mb-2 text-sm font-medium text-gray-900">Nom...</label>
                <input v-model="developer.surname" id="surname"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required minlength="2" maxlength="30" />
            </div>
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">E-mail...</label>
                <input v-model="developer.email" id="email" type="email"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required maxlength="255" />
            </div>
        </div>

        <div v-if="step === 2" class="tab">
            <div class="mb-5">
                <label for="profil_image" class="block mb-2 text-sm font-medium text-gray-900">Image de profil
                    (optionnel)</label>
                <input type="file" accept=".jpg,.png" @change="handleFileUpload('profil_image', $event)"
                    class="block w-full text-sm text-gray-900 border border-blue-500 rounded-lg cursor-pointer bg-gray-50" />
                <span v-if="developer.profil_image" class="text-sm">{{ developer.profil_image.name }}</span>
            </div>
            <div class="mb-5">
                <label for="cv" class="block mb-2 text-sm font-medium text-gray-900">Importer votre CV</label>
                <input type="file" accept=".pdf" @change="handleFileUpload('cv', $event)"
                    class="block w-full text-sm text-gray-900 border border-blue-500 rounded-lg cursor-pointer bg-gray-50" />
                <span v-if="developer.cv" class="text-sm">{{ developer.cv.name }}</span>
            </div>
            <div class="mb-5">
                <label for="cover_letter" class="block mb-2 text-sm font-medium text-gray-900">Importer votre lettre de
                    motivation</label>
                <input type="file" accept=".pdf" @change="handleFileUpload('cover_letter', $event)"
                    class="block w-full text-sm text-gray-900 border border-blue-500 rounded-lg cursor-pointer bg-gray-50" />
                <span v-if="developer.cover_letter" class="text-sm">{{ developer.cover_letter.name }}</span>
            </div>
        </div>

        <div v-if="step === 3" class="tab">
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900">Type de Contrat</label>
                <select v-model="developer.contract_id"
                    class="block w-full text-sm text-gray-900 border p-1 border-blue-500 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Sélectionnez un type de contrat</option>
                    <option v-for="contract in contracts" :key="contract.id" :value="contract.id">{{ contract.name }}
                    </option>
                </select>
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900">Langages de
                    Programmation</label>
                <div class="flex flex-col overflow-y-auto h-40">
                    <div v-for="language in programmingLanguages" :key="language.id" class="flex items-center mb-2">
                        <input type="checkbox" :id="`language-${language.id}`"
                            class="form-checkbox h-4 w-4 text-blue-600" v-model="developer.programming_languages"
                            :value="language.id" />
                        <label :for="`language-${language.id}`" class="ml-2 text-sm font-semibold">{{ language.name
                            }}</label>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900">Type de Développeur</label>
                <select v-model="developer.type_id"
                    class="block w-full text-sm text-gray-900 border p-1 border-blue-500 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Sélectionnez un type de développeur</option>
                    <option v-for="type in developerTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                </select>
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900">Ville</label>
                <select v-model="developer.location_id"
                    class="block w-full text-sm text-gray-900 border p-1 border-blue-500 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Sélectionnez une ville</option>
                    <option v-for="location in localisations" :key="location.id" :value="location.id">{{ location.city
                        }}, {{ location.postal_code }}</option>
                </select>
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900">Année d'Expérience</label>
                <select v-model="developer.year_id"
                    class="block w-full text-sm text-gray-900 border p-1 border-blue-500 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Sélectionnez une année d'expérience</option>
                    <option v-for="year in yearsExperiences" :key="year.id" :value="year.id">{{ year.name }}</option>
                </select>
            </div>
        </div>

        <div v-if="step === 4" class="tab">
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Mot de passe...</label>
                <input v-model="developer.password" id="password" type="password"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required minlength="8" />
            </div>
            <div class="mb-5">
                <label for="confirmPassword" class="block mb-2 text-sm font-medium text-gray-900">Confirmer le mot de
                    passe...</label>
                <input v-model="developer.confirmPassword" id="confirmPassword" type="password"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required minlength="8" />
            </div>

            <div v-if="passwordsDoNotMatch" class="text-red-500 mb-5 text-xs md:text-base font-normal">
                Les mots de passe ne correspondent pas.
            </div>

            <div class="mb-5">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description
                    (optionnel)</label>
                <textarea v-model="developer.description" id="description"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-blue-500 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Une brève description de vous-même..." minlength="8" maxlength="255"
                    rows="6"></textarea>
            </div>
            <div class="flex items-start mb-5">
                <div class="flex items-center h-5">
                    <input id="terms" type="checkbox" value=""
                        class="w-4 h-4 border border-blue-500 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300"
                        required />
                </div>
                <label for="terms" class="ms-2 text-sm font-medium text-gray-900">J'accepte les <router-link :to="{ name: 'politiqueConfidentialite' }"
                        class="text-blue-600 hover:underline">termes et conditions</router-link></label>
            </div>

            <div v-if="Object.keys(developer.errors).length > 0"
                class="mb-5 p-4 border border-red-500 rounded bg-red-100">

                <ul class="list-disc list-inside">
                    <li v-for="(errorMessages, field) in developer.errors" :key="field" class="text-red-500 text-sm">
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
import { useResourcesStore } from '../stores/resourcesStore';
import PageTitle from './PageTitle.vue';

const router = useRouter();

const authStore = useAuthStore();
const resourcesStore = useResourcesStore();

const step = ref(1);
const passwordsDoNotMatch = ref(false);
const emit = defineEmits(['back']);

const contracts = ref([]);
const programmingLanguages = ref([]);
const developerTypes = ref([]);
const localisations = ref([]);
const yearsExperiences = ref([]);

//Chargement données en async provenant du store (Types de devs, contrats, années, langages, localisations)
onMounted(async () => {
    await resourcesStore.fetchAllResources();
    contracts.value = resourcesStore.contracts;
    programmingLanguages.value = resourcesStore.programmingLanguages;
    developerTypes.value = resourcesStore.developerTypes;
    localisations.value = resourcesStore.localisations;
    yearsExperiences.value = resourcesStore.yearsExperiences;
});

const developer = ref({
    first_name: '',
    surname: '',
    email: '',
    phone: '',
    password: '',
    confirmPassword: '',
    cv: null,
    cover_letter: null,
    profil_image: null,
    description: '',
    contract_id: null,
    programming_languages: [],
    type_id: null,
    location_id: null,
    year_id: null,
    errors: {}
});

// Ajout des watchers pour vérifier la correspondance des mots de passe
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

const handleStepSubmission = () => {
    if (step.value === 4) {
        // Si les mots de passe ne correspondent pas, ne pas continuer
        if (passwordsDoNotMatch.value) {
            developer.value.errors = {
                password: ["Les mots de passe ne correspondent pas."],
            };
            return;
        }
    }
    if (step.value < 4) {
        step.value++;
    } else {
        registerDeveloper();
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
    developer.value[field] = event.target.files[0];
};

const registerDeveloper = async () => {
    try {
        const formData = new FormData();

        // Ajoutez toutes les propriétés de developer à formData
        Object.entries(developer.value).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach(item => formData.append(`${key}[]`, item)); // Pour les tableaux
            } else {
                formData.append(key, value); // Pour les autres types
            }
        });

        await Axios.get('/csrf-cookie');
        const response = await Axios.post('/api/developers', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        console.log('Inscription réussie:', response.data);
        authStore.setUser(response.data.user);
        router.push('/');
    } catch (error) {
        if (error.response && error.response.status === 422) {
            developer.value.errors = error.response.data.errors;
            console.log(developer);
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