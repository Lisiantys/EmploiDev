<template>
    <div class="container max-w-screen-xl mx-auto px-4 py-6">
        <!-- Titre de la page -->

        <div class="bg-white shadow-md rounded-lg overflow-hidden mt-10">
            <form @submit.prevent="submitProfile" class="p-4 sm:p-6">
                <!-- Informations personnelles -->
                <div class="mb-6">
                    <div class="mb-6 hidden md:block">
                        <PageTitle title="// Votre profil développeur" />
                    </div>
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Vos informations</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Prénom -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Prénom</label>
                            <input v-model="developer.first_name" placeholder="Prénom..." required
                                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <!-- Nom -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Nom</label>
                            <input v-model="developer.surname" placeholder="Nom..." required
                                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <!-- E-mail -->
                        <div class="md:col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-700">E-mail</label>
                            <input v-model="developer.email" type="email" placeholder="E-mail..." required
                                class="text-sm font-normal block w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>
                </div>

                <!-- Préférences professionnelles -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Préférences professionnelles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Type de Contrat -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Type de Contrat</label>
                            <select v-model="developer.contract_id"
                                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled>Sélectionnez un type de contrat</option>
                                <option v-for="contract in contracts" :key="contract.id" :value="contract.id">
                                    {{ contract.name }}
                                </option>
                            </select>
                        </div>
                        <!-- Type de Développeur -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Type de Développeur</label>
                            <select v-model="developer.type_id"
                                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled>Sélectionnez un type de développeur</option>
                                <option v-for="type in developerTypes" :key="type.id" :value="type.id">
                                    {{ type.name }}
                                </option>
                            </select>
                        </div>
                        <!-- Année d'Expérience -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Année d'Expérience</label>
                            <select v-model="developer.year_id"
                                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled>Sélectionnez une année d'expérience</option>
                                <option v-for="year in yearsExperiences" :key="year.id" :value="year.id">
                                    {{ year.name }}
                                </option>
                            </select>
                        </div>
                        <!-- Ville -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Ville</label>
                            <select v-model="developer.location_id"
                                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled>Sélectionnez une ville</option>
                                <option v-for="location in localisations" :key="location.id" :value="location.id">
                                    {{ location.city }}, {{ location.postal_code }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Compétences et Langages -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Compétences et Langages</h2>
                    <div>
                        <label class="block mb-2 text-xs font-medium text-gray-700">Langages de Programmation</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 overflow-y-auto h-40">
                            <div v-for="language in programmingLanguages" :key="language.id" class="flex items-center ">
                                <input type="checkbox" :id="`language-${language.id}`"
                                    v-model="developer.programming_languages" :value="language.id"
                                    class="h-3.5 w-3.5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                                <label :for="`language-${language.id}`" class="ml-2 text-xs text-gray-700">
                                    {{ language.name }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents et Médias -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Documents et Médias</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Image de Profil -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Image de Profil</label>
                            <input type="file" accept=".jpg,.png" @change="handleFileUpload('profil_image', $event)"
                                class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" />
                            <span v-if="developer.profil_image" class="text-xs text-gray-500">
                                {{ getFileName(developer.profil_image) }}
                            </span>
                        </div>
                        <!-- CV -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">CV</label>
                            <input type="file" accept=".pdf" @change="handleFileUpload('cv', $event)"
                                class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" />
                            <span v-if="developer.cv" class="text-xs text-gray-500">
                                {{ getFileName(developer.cv) }}
                            </span>
                        </div>
                        <!-- Lettre de Motivation -->
                        <div class="md:col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-700">Lettre de Motivation</label>
                            <input type="file" accept=".pdf" @change="handleFileUpload('cover_letter', $event)"
                                class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" />
                            <span v-if="developer.cover_letter" class="text-xs text-gray-500">
                                {{ getFileName(developer.cover_letter) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Description</h2>
                    <div>
                        <textarea v-model="developer.description"
                            class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Une brève description de vous-même..." minlength="8"
                            maxlength="255"></textarea>
                    </div>
                </div>

                <!-- Disponibilité -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Disponibilité</h2>
                    <div class="flex items-center">
                        <input type="checkbox" v-model="developer.is_free" id="is_free"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                        <label for="is_free" class="ml-2 text-xs font-medium text-gray-700">
                            Activer votre profil pour être visible par les entreprises
                        </label>
                    </div>
                </div>

                <!-- Sécurité -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Sécurité</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nouveau mot de passe -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Nouveau mot de passe</label>
                            <input v-model="developer.password" type="password"
                                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <!-- Confirmer le mot de passe -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Confirmer le mot de
                                passe</label>
                            <input v-model="developer.confirmPassword" type="password"
                                class="block  text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <div v-if="passwordsDoNotMatch" class="text-red-500 mt-1 text-xs">
                                Les mots de passe ne correspondent pas.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Affichage des erreurs -->
                <div v-if="developer.errors && Object.keys(developer.errors).length > 0"
                    class="mb-6 p-3 border border-red-500 rounded bg-red-50">
                    <ul class="list-disc list-inside text-red-500 text-xs">
                        <li v-for="(errorMessages, field) in developer.errors" :key="field">
                            {{ errorMessages.join(", ") }}
                        </li>
                    </ul>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col md:flex-row md:justify-between items-center">
                    <button type="submit"
                        class="w-full md:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Sauvegarder les modifications
                    </button>
                    <button @click.prevent="deleteAccount"
                        class="w-full md:w-auto mt-3 md:mt-0 md:ml-4 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Supprimer mon compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useResourcesStore } from "../stores/resourcesStore";
import Axios from "axios";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/authStore";
import PageTitle from './PageTitle.vue';

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

const contracts = computed(() => resourcesStore.contracts);
const programmingLanguages = computed(() => resourcesStore.programmingLanguages);
const developerTypes = computed(() => resourcesStore.developerTypes);
const localisations = computed(() => resourcesStore.localisations);
const yearsExperiences = computed(() => resourcesStore.yearsExperiences);

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

// Fonction pour obtenir le nom du fichier à partir d'un objet File ou d'une chaîne
const getFileName = (file) => {
    if (!file) return "";
    let name;
    if (typeof file === "string") {
        // Extraire le nom du fichier du chemin
        name = file.split("/").pop();
    } else if (file instanceof File) {
        name = file.name;
    } else {
        return "";
    }
    // Retourner les 10 derniers caractères si le nom est trop long
    return name.length > 10 ? name.slice(-20) : name;
};

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
    const confirmed = confirm(
        "Êtes-vous sûr de vouloir supprimer votre compte ? Toutes vos données présentes sur ce site seront définitivement supprimées."
    );
    if (confirmed) {
        try {
            const response = await Axios.delete(
                `/api/developers/${developer.value.id}`
            );
            console.log("Account deleted:", response.data);
            // Déconnecter l'utilisateur
            authStore.logout();
            // Rediriger vers la page d'accueil
            router.push({ name: "home" });
        } catch (error) {
            console.error(
                "Failed to delete account:",
                error.response ? error.response.data : error
            );
            // Afficher un message d'erreur à l'utilisateur
            alert(
                "Une erreur s'est produite lors de la suppression de votre compte."
            );
        }
    }
};

onMounted(async () => {
    await fetchUserProfile();
});
</script>
