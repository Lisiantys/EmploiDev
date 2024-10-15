<template>
  <div class="container max-w-screen-xl mx-auto px-4 py-6">
    <!-- Titre de la page -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mt-10">
      <form @submit.prevent="submitProfile" class="p-4 sm:p-6">
        <!-- Informations de l'entreprise -->
        <div class="mb-6">
          <div class="mb-6 hidden md:block">
            <PageTitle title="// Votre profil entreprise" />
          </div>
          <h2 class="text-lg font-semibold mb-4 border-b pb-2">Informations de l'entreprise</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nom de l'entreprise -->
            <div>
              <label class="block mb-1 text-xs font-medium text-gray-700">Nom de l'entreprise</label>
              <input v-model="company.name" placeholder="Nom de l'entreprise..." required
                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <!-- E-mail -->
            <div>
              <label class="block mb-1 text-xs font-medium text-gray-700">E-mail</label>
              <input v-model="company.email" type="email" placeholder="E-mail..." required
                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold mb-4 border-b pb-2">Description</h2>
          <div>
            <label class="block mb-1 text-xs font-medium text-gray-700">Présentation</label>
            <textarea v-model="company.description"
              class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Une brève description de votre entreprise..." minlength="8" maxlength="255"></textarea>
          </div>
        </div>

        <!-- Documents et Médias -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold mb-4 border-b pb-2">Documents et Médias</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Logo de l'entreprise -->
            <div>
              <label class="block mb-1 text-xs font-medium text-gray-700">Logo de l'entreprise</label>
              <input type="file" accept=".jpg,.png" @change="handleFileUpload('profil_image', $event)"
                class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" />
              <span v-if="company.profil_image" class="text-xs text-gray-500">
                {{ getFileName(company.profil_image) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Sécurité -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold mb-4 border-b pb-2">Sécurité</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nouveau mot de passe -->
            <div>
              <label class="block mb-1 text-xs font-medium text-gray-700">Nouveau mot de passe</label>
              <input v-model="company.password" type="password" placeholder="Nouveau mot de passe"
                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <!-- Confirmer le mot de passe -->
            <div>
              <label class="block mb-1 text-xs font-medium text-gray-700">Confirmer le mot de passe</label>
              <input v-model="company.confirmPassword" type="password" placeholder="Confirmer le mot de passe"
                class="block text-sm font-normal w-full p-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
              <div v-if="passwordsDoNotMatch" class="text-red-500 mt-1 text-xs">
                Les mots de passe ne correspondent pas.
              </div>
            </div>
          </div>
        </div>

        <!-- Affichage des erreurs -->
        <div v-if="company.errors && Object.keys(company.errors).length > 0"
          class="mb-6 p-3 border border-red-500 rounded bg-red-50">
          <ul class="list-disc list-inside text-red-500 text-xs">
            <li v-for="(errorMessages, field) in company.errors" :key="field">
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
import { ref, onMounted, watch } from "vue";
import Axios from "axios";
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/authStore';
import PageTitle from './PageTitle.vue';
import { useGlobalNotify } from '../notifications/useGlobalNotify';
const notify = useGlobalNotify();

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


const fetchCompanyProfile = async () => {
  try {
    const response = await Axios.get("/api/developers/profile"); // Route conservée

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

    const response = await Axios.post(
      `/api/companies/${company.value.id}`,
      formData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      }
    );

    // Réinitialiser les erreurs
    company.value.errors = {};

    notify({
      group: 'success-action',
      type: 'success',
      title: 'Succès',
      text: 'Compte mis à jour avec succès !'
    });

    // Afficher un message de succès ou rediriger l'utilisateur si nécessaire
  } catch (error) {
    if (error.response) {
      // Mettre à jour les erreurs pour les afficher dans le formulaire
      company.value.errors = error.response.data.errors || {};
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

      // Déconnecter l'utilisateur
      authStore.logout();
      // Rediriger vers la page d'accueil
      router.push({ name: 'home' });
      notify({
        group: 'success-action',
        type: 'success',
        title: 'Succès',
        text: 'Compte supprimé avec succès !'
      });
    } catch (error) {
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
