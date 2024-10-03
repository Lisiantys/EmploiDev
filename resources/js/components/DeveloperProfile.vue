<template>
    <div class="p-6 sm:p-10 text-2xl font-bold md:pl-32">
        <form @submit.prevent="submitProfile" class="p-6 sm:p-10">
            <div class="flex flex-col gap-4">
                <div class="flex w-full gap-4">
                    <div class="w-1/2">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Prénom:</label>
                        <input v-model="developer.first_name" placeholder="Prénom..." required class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                    </div>
                    <div class="w-1/2">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Nom:</label>
                        <input v-model="developer.surname" placeholder="Nom..." required class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
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

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900"
                        >Nouveau mot de passe:</label
                    >
                    <input
                        v-model="developer.password"
                        type="password"
                        placeholder="Mot de passe"
                        required
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
                        required
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
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useResourcesStore } from "../stores/resourcesStore"; // Ensure the path is correct
import Axios from "axios";

const resourcesStore = useResourcesStore();

const developer = ref({
    first_name: "",
    surname: "",
    email: "",
    password: "",
    confirmPassword: "",
    cv: "",
    cover_letter: "",
    profil_image: "",
    description: "",
    contract_id: "",
    programming_languages: [],
    type_id: "",
    location_id: "",
    year_id: "",
    errors: {},
});

const contracts = ref([]); // For storing contract types
const developerTypes = ref([]); // For storing developer types
const programmingLanguages = ref([]); // For storing programming languages
const localisations = ref([]); // For storing locations
const yearsExperiences = ref([]); // For storing years of experience

// Property to check if passwords match
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
        developer.value = {
            ...response.data, // Directly assign the returned developer data
            programming_languages: response.data.programming_languages.map(
                (lang) => lang.id
            ), // Map to get only IDs if needed
        };
    } catch (error) {
        console.error("Failed to fetch user profile:", error.response);
    }
};

const handleFileUpload = (fieldName, event) => {
    const file = event.target.files[0];
    developer.value[fieldName] = file;
};

const submitProfile = async () => {
    try {
        console.log("Submitting Profile:", developer.value);

        const formData = new FormData();
        Object.entries(developer.value).forEach(([key, value]) => {
            console.log(`Appending ${key}: ${value}`); // Log each field being appended
            if (value) {
                formData.append(key, value);
            }
        });

        console.log("Form Data being sent:", Object.fromEntries(formData)); // Log the form data for debugging

        const response = await Axios.put(
            `/api/developers/${developer.value.id}`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        console.log("Profile updated successfully", response.data);
    } catch (error) {
        if (error.response) {
            console.error("Failed to update profile:", error.response.data);
        } else {
            console.error("Failed to update profile:", error);
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
    fetchUserProfile();
});
</script>
