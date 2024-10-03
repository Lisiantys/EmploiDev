<template>
    <div class="p-6 sm:p-10 text-2xl font-bold md:pl-32">
        <form @submit.prevent="submitProfile" class="p-6 sm:p-10">
            <div class="flex flex-col gap-4">
                <div class="flex w-full gap-4">
                    <div class="w-1/2">
                        <label
                            class="block mb-2 text-sm font-medium text-gray-900"
                            >Prénom:</label
                        >
                        <input
                            v-model="developer.first_name"
                            placeholder="Prénom..."
                            required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        />
                    </div>
                    <div class="w-1/2">
                        <label
                            class="block mb-2 text-sm font-medium text-gray-900"
                            >Nom:</label
                        >
                        <input
                            v-model="developer.surname"
                            placeholder="Nom..."
                            required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        />
                    </div>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900"
                        >E-mail:</label
                    >
                    <input
                        v-model="developer.email"
                        type="email"
                        placeholder="E-mail..."
                        required
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    />
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900"
                        >Type de Contrat:</label
                    >
                    <select
                        v-model="developer.contract_id"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="" disabled>
                            Sélectionnez un type de contrat
                        </option>
                        <option
                            v-for="contract in contracts"
                            :key="contract.id"
                            :value="contract.id"
                        >
                            {{ contract.name }}
                        </option>
                    </select>
                </div>

                <div class="overflow-y-scroll h-40">
                    <label class="block mb-2 text-sm font-medium text-gray-900"
                        >Langages de Programmation:</label
                    >
                    <div
                        class="grid grid-cols-4 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5 lg:ml-4 gap-4"
                    >
                        <div
                            v-for="language in programmingLanguages"
                            :key="language.id"
                            class="flex items-center mb-2"
                        >
                            <input
                                type="checkbox"
                                :id="`language-${language.id}`"
                                v-model="developer.programming_languages"
                                :value="language.id"
                            />
                            <label
                                :for="`language-${language.id}`"
                                class="ml-2 text-sm font-semibold"
                                >{{ language.name }}</label
                            >
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900"
                        >Type de Développeur:</label
                    >
                    <select
                        v-model="developer.type_id"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="" disabled>
                            Sélectionnez un type de développeur
                        </option>
                        <option
                            v-for="type in developerTypes"
                            :key="type.id"
                            :value="type.id"
                        >
                            {{ type.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900"
                        >Ville:</label
                    >
                    <select
                        v-model="developer.location_id"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="" disabled>
                            Sélectionnez une ville
                        </option>
                        <option
                            v-for="location in localisations"
                            :key="location.id"
                            :value="location.id"
                        >
                            {{ location.city }}, {{ location.postal_code }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900"
                        >Année d'Expérience:</label
                    >
                    <select
                        v-model="developer.year_id"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="" disabled>
                            Sélectionnez une année d'expérience
                        </option>
                        <option
                            v-for="year in yearsExperiences"
                            :key="year.id"
                            :value="year.id"
                        >
                            {{ year.name }}
                        </option>
                    </select>
                </div>

                <div class="mb-5">
                    <label
                        for="description"
                        class="block mb-2 text-sm font-medium text-gray-900"
                        >Description (optionnel)</label
                    >
                    <textarea
                        v-model="developer.description"
                        id="description"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Une brève description de vous-même..."
                        minlength="8"
                        maxlength="255"
                    ></textarea>
                </div>

                <div class="mb-5">
                    <label
                        for="profil_image"
                        class="block mb-2 text-sm font-medium text-gray-900"
                        >Image de profil (optionnel)</label
                    >
                    <input
                        type="file"
                        accept=".jpg,.png"
                        @change="handleFileUpload('profil_image', $event)"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                    />
                    <span v-if="developer.profil_image" class="text-sm">{{
                        developer.profil_image
                    }}</span>
                </div>

                <div class="mb-5">
                    <label
                        for="cv"
                        class="block mb-2 text-sm font-medium text-gray-900"
                        >Importer votre CV</label
                    >
                    <input
                        type="file"
                        accept=".pdf"
                        @change="handleFileUpload('cv', $event)"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                    />
                    <span v-if="developer.cv" class="text-sm">{{
                        developer.cv
                    }}</span>
                </div>

                <div class="mb-5">
                    <label
                        for="cover_letter"
                        class="block mb-2 text-sm font-medium text-gray-900"
                        >Importer votre lettre de motivation</label
                    >
                    <input
                        type="file"
                        accept=".pdf"
                        @change="handleFileUpload('cover_letter', $event)"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                    />
                    <span v-if="developer.cover_letter" class="text-sm">{{
                        developer.cover_letter
                    }}</span>
                </div>

                <div class="flex items-center">
                    <label class="inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            v-model="developer.is_free"
                            class="sr-only peer"
                        />
                        <div
                            class="relative w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600 transition-colors"
                        >
                            <div
                                class="absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full transition-transform duration-300 ease-in-out peer-checked:translate-x-5"
                            ></div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-900">
                            Disponible pour de nouvelles opportunités
                        </span>
                    </label>
                </div>
                <div
                    class="absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full transition-transform duration-300 ease-in-out peer-checked:translate-x-5"
                ></div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900"
                        >Nouveau mot de passe:</label
                    >
                    <input
                        v-model="developer.password"
                        type="password"
                        placeholder="Mot de passe"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    />
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900"
                        >Confirmer le mot de passe:</label
                    >
                    <input
                        v-model="developer.confirmPassword"
                        type="password"
                        placeholder="Confirmer le mot de passe"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    />
                    <div v-if="passwordsDoNotMatch" class="text-red-500 mb-2">
                        Les mots de passe ne correspondent pas.
                    </div>
                </div>

                <div
                    v-if="
                        developer.errors &&
                        Object.keys(developer.errors).length > 0
                    "
                    class="mb-5 p-4 border border-red-500 rounded bg-red-100"
                >
                    <ul class="list-disc list-inside">
                        <li
                            v-for="(errorMessages, field) in developer.errors"
                            :key="field"
                            class="text-red-500"
                        >
                            {{ errorMessages.join(", ") }}
                        </li>
                    </ul>
                </div>

                <button
                    type="submit"
                    class="bg-blue-500 text-white p-2 rounded"
                >
                    Sauvegarder
                </button>

                <!-- Bouton Supprimer mon compte -->
                <button
                    @click.prevent="deleteAccount"
                    class="bg-red-500 text-white p-2 rounded mt-4"
                >
                    Supprimer mon compte
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useResourcesStore } from "../stores/resourcesStore";
import Axios from "axios";
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/authStore';

const router = useRouter();
const authStore = useAuthStore();
const resourcesStore = useResourcesStore();

const developer = ref({
    id: null,
    first_name: "",
    surname: "",
    email: "",
    password: "",
    confirmPassword: "",
    cv: null,
    cover_letter: null,
    profil_image: null,
    description: "",
    contract_id: "",
    programming_languages: [],
    type_id: "",
    location_id: "",
    year_id: "",
    is_free: true,
    errors: {},
});

const contracts = ref([]);
const developerTypes = ref([]);
const programmingLanguages = ref([]);
const localisations = ref([]);
const yearsExperiences = ref([]);

// Vérification des mots de passe
const passwordsDoNotMatch = ref(false);

watch(
    () => developer.value.password,
    (newPassword) => {
        passwordsDoNotMatch.value =
            newPassword !== developer.value.confirmPassword;
    }
);

watch(
    () => developer.value.confirmPassword,
    (newConfirmPassword) => {
        passwordsDoNotMatch.value =
            developer.value.password !== newConfirmPassword;
    }
);

const fetchUserProfile = async () => {
    try {
        const response = await Axios.get("/api/developers/profile");
        console.log("Fetched developer profile:", response.data);

        // Mettez à jour le profil du développeur
        developer.value = {
            ...developer.value, // Conserver les propriétés existantes
            ...response.data, // Les données du serveur
            programming_languages: response.data.programming_languages.map(
                (lang) => lang.id
            ),
            email: response.data.user.email, // Récupérer l'email de l'utilisateur
            password: "", // Réinitialiser le mot de passe
            confirmPassword: "",
            errors: {},
        };

        // Assurez-vous que l'ID est défini
        developer.value.id = response.data.id;

        // S'assurer que is_free est un booléen
        developer.value.is_free = !!response.data.is_free;
    } catch (error) {
        console.error("Failed to fetch user profile:", error.response);
    }
};

const handleFileUpload = (fieldName, event) => {
    const file = event.target.files[0];
    developer.value[fieldName] = file;
};

const submitProfile = async () => {
    if (developer.value.password) {
        // Vérifier si les mots de passe correspondent
        if (passwordsDoNotMatch.value) {
            developer.value.errors = {
                password: ["Les mots de passe ne correspondent pas."],
            };
            return;
        }
    }
    try {
        console.log("Submitting Profile:", developer.value);

        const formData = new FormData();

        // Ajout du 'method spoofing' pour la méthode PUT
        formData.append("_method", "PUT");

        // Ajouter les champs au FormData
        formData.append("first_name", developer.value.first_name);
        formData.append("surname", developer.value.surname);
        formData.append("email", developer.value.email);
        formData.append("description", developer.value.description || "");
        formData.append("contract_id", developer.value.contract_id);
        formData.append("type_id", developer.value.type_id);
        formData.append("location_id", developer.value.location_id);
        formData.append("year_id", developer.value.year_id);
        formData.append("is_free", developer.value.is_free ? 1 : 0);

        // Ajouter les langages de programmation
        developer.value.programming_languages.forEach((langId) => {
            formData.append("programming_languages[]", langId);
        });

        // Ajouter les fichiers si présents
        if (developer.value.cv instanceof File) {
            formData.append("cv", developer.value.cv);
        }

        if (developer.value.cover_letter instanceof File) {
            formData.append("cover_letter", developer.value.cover_letter);
        }

        if (developer.value.profil_image instanceof File) {
            formData.append("profil_image", developer.value.profil_image);
        }

        // Ajouter le mot de passe si l'utilisateur souhaite le changer
        if (developer.value.password) {
            formData.append("password", developer.value.password);
        }

        console.log("Form Data being sent:", formData);

        const response = await Axios.post(
            `/api/developers/${developer.value.id}`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        console.log("Profile updated successfully", response.data);

        // Réinitialiser les erreurs
        developer.value.errors = {};

        // Afficher un message de succès ou rediriger l'utilisateur si nécessaire
    } catch (error) {
        if (error.response) {
            console.error("Failed to update profile:", error.response.data);
            // Mettre à jour les erreurs pour les afficher dans le formulaire
            developer.value.errors = error.response.data.errors || {};
        } else {
            console.error("Failed to update profile:", error);
        }
    }
};

const deleteAccount = async () => {
    const confirmed = confirm("Êtes-vous sûr de vouloir supprimer votre compte ? Toutes vos données présentes sur ce site seront définitivement supprimées.");
    if (confirmed) {
        try {
            const response = await Axios.delete(`/api/developers/${developer.value.id}`);
            console.log("Account deleted:", response.data);
            // Déconnecter l'utilisateur
            authStore.logout();
            // Rediriger vers la page d'accueil
            router.push({ name: 'home' });
        } catch (error) {
            console.error("Failed to delete account:", error.response ? error.response.data : error);
            // Afficher un message d'erreur à l'utilisateur
            alert("Une erreur s'est produite lors de la suppression de votre compte.");
        }
    }
};

onMounted(async () => {
    await resourcesStore.fetchAllResources();
    contracts.value = resourcesStore.contracts;
    developerTypes.value = resourcesStore.developerTypes;
    programmingLanguages.value = resourcesStore.programmingLanguages;
    localisations.value = resourcesStore.localisations;
    yearsExperiences.value = resourcesStore.yearsExperiences;
    await fetchUserProfile();
});
</script>
