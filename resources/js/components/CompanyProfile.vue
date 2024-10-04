<template>
    <div class="p-6 sm:p-10 text-2xl font-bold md:pl-32">
        <form @submit.prevent="submitProfile" class="p-6 sm:p-10">
            <div class="flex flex-col gap-4">
                <!-- Company Name -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Nom de l'entreprise:
                    </label>
                    <input
                        v-model="company.name"
                        placeholder="Nom de l'entreprise..."
                        required
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    />
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        E-mail:
                    </label>
                    <input
                        v-model="company.email"
                        type="email"
                        placeholder="E-mail..."
                        required
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    />
                </div>

                <!-- Description -->
                <div class="mb-5">
                    <label
                        for="description"
                        class="block mb-2 text-sm font-medium text-gray-900"
                    >
                        Description (optionnel)
                    </label>
                    <textarea
                        v-model="company.description"
                        id="description"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Une brève description de votre entreprise..."
                        minlength="8"
                        maxlength="255"
                    ></textarea>
                </div>

                <!-- Profile Image -->
                <div class="mb-5">
                    <label
                        for="profil_image"
                        class="block mb-2 text-sm font-medium text-gray-900"
                    >
                        Logo de l'entreprise (optionnel)
                    </label>
                    <input
                        type="file"
                        accept=".jpg,.png"
                        @change="handleFileUpload('profil_image', $event)"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                    />
                    <span v-if="company.profil_image" class="text-sm">
                        {{ getFileName(company.profil_image) }}
                    </span>
                </div>

                <!-- Password -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Nouveau mot de passe:
                    </label>
                    <input
                        v-model="company.password"
                        type="password"
                        placeholder="Mot de passe"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Confirmer le mot de passe:
                    </label>
                    <input
                        v-model="company.confirmPassword"
                        type="password"
                        placeholder="Confirmer le mot de passe"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    />
                    <div v-if="passwordsDoNotMatch" class="text-red-500 mb-2">
                        Les mots de passe ne correspondent pas.
                    </div>
                </div>

                <!-- Display Errors -->
                <div
                    v-if="company.errors && Object.keys(company.errors).length > 0"
                    class="mb-5 p-4 border border-red-500 rounded bg-red-100"
                >
                    <ul class="list-disc list-inside">
                        <li
                            v-for="(errorMessages, field) in company.errors"
                            :key="field"
                            class="text-red-500"
                        >
                            {{ errorMessages.join(", ") }}
                        </li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">
                    Sauvegarder
                </button>

                <!-- Delete Account Button -->
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
import Axios from "axios";
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/authStore';

const router = useRouter();
const authStore = useAuthStore();

const company = ref({
    id: null,
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
    profil_image: null,
    description: "",
    errors: {},
});

// Vérification des mots de passe
const passwordsDoNotMatch = ref(false);

watch(
    () => company.value.password,
    (newPassword) => {
        passwordsDoNotMatch.value =
            newPassword !== company.value.confirmPassword;
    }
);

watch(
    () => company.value.confirmPassword,
    (newConfirmPassword) => {
        passwordsDoNotMatch.value =
            company.value.password !== newConfirmPassword;
    }
);

// Fonction pour obtenir le nom du fichier
const getFileName = (file) => {
    if (!file) return '';
    if (typeof file === 'string') {
        // Extraire le nom du fichier du chemin
        return file.split('/').pop();
    } else if (file instanceof File) {
        return file.name;
    }
    return '';
};

const fetchCompanyProfile = async () => {
    try {
        const response = await Axios.get("/api/developers/profile"); // Route conservée
        console.log("Fetched company profile:", response.data);

        // Mettez à jour le profil de l'entreprise
        company.value = {
            ...company.value, // Conserver les propriétés existantes
            ...response.data, // Les données du serveur
            email: response.data.user.email, // Récupérer l'email de l'utilisateur
            password: "", // Réinitialiser le mot de passe
            confirmPassword: "",
            errors: {},
        };

        // Assurez-vous que l'ID est défini
        company.value.id = response.data.id;
    } catch (error) {
        console.error("Failed to fetch company profile:", error.response);
    }
};

const handleFileUpload = (fieldName, event) => {
    const file = event.target.files[0];
    company.value[fieldName] = file;
};

const submitProfile = async () => {
    // Réinitialiser les erreurs
    company.value.errors = {};

    // Vérifier si les mots de passe correspondent
    if (company.value.password) {
        if (passwordsDoNotMatch.value) {
            company.value.errors = {
                password: ["Les mots de passe ne correspondent pas."],
            };
            return;
        }
    }

    try {
        console.log("Submitting Profile:", company.value);

        const formData = new FormData();

        // Ajout du 'method spoofing' pour la méthode PUT
        formData.append("_method", "PUT");

        // Ajouter les champs au FormData
        formData.append("name", company.value.name);
        formData.append("email", company.value.email);
        formData.append("description", company.value.description || "");

        // Ajouter l'image de profil si présente
        if (company.value.profil_image instanceof File) {
            formData.append("profil_image", company.value.profil_image);
        }

        // Ajouter le mot de passe si l'utilisateur souhaite le changer
        if (company.value.password) {
            formData.append("password", company.value.password);
        }

        console.log("Form Data being sent:", formData);

        const response = await Axios.post(
            `/api/companies/${company.value.id}`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        console.log("Profile updated successfully", response.data);

        // Réinitialiser les erreurs
        company.value.errors = {};

        // Afficher un message de succès ou rediriger l'utilisateur si nécessaire
    } catch (error) {
        if (error.response) {
            console.error("Failed to update profile:", error.response.data);
            // Mettre à jour les erreurs pour les afficher dans le formulaire
            company.value.errors = error.response.data.errors || {};
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
            // Utiliser method spoofing pour DELETE
            const formData = new FormData();
            formData.append('_method', 'DELETE');

            const response = await Axios.post(
                `/api/companies/${company.value.id}`,
                formData,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );

            console.log("Account deleted:", response.data);
            // Déconnecter l'utilisateur
            authStore.logout();
            // Rediriger vers la page d'accueil
            router.push({ name: 'home' });
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
    await fetchCompanyProfile();
});
</script>
