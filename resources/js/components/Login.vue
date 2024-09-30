<template>
        <div class="w-full max-w-md bg-white shadow-md rounded-lg p-6 m-auto pt-48">

    <form @submit.prevent="login">
        <h2>Connexion</h2>

        <div class="mb-5">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">E-mail...</label>
            <input v-model="credentials.email" id="email" type="email"
                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                placeholder="E-mail..." required maxlength="255" />
        </div>

        <div class="mb-5">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Mot de passe...</label>
            <input v-model="credentials.password" id="password" type="password"
                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                required minlength="8"/>
        </div>

        <div v-if="loginError" class="mb-5 p-4 border border-red-500 rounded bg-red-100">
            <ul class="list-disc list-inside">
                <li class="text-red-500">{{ loginError }}</li>
            </ul>
        </div>

        <div class="flex justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Se connecter
            </button>
        </div>
    </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Axios from "axios";
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/authStore';

const router = useRouter();
const authStore = useAuthStore();

const credentials = ref({
    email: '',
    password: ''
});
const loginError = ref('');

const login = async () => {
    try {
       await Axios.get('/csrf-cookie'); 
        const response = await Axios.post('/api/login', credentials.value);
        console.log(response.data.user);
        authStore.setUser(response.data.user)
        router.push('/'); // Redirection vers la page d'accueil après la connexion réussie
    } catch (error) {
        if (error.response && error.response.status === 401) {
            loginError.value = error.response.data.message;
        } else {
            console.error('Erreur lors de la connexion:', error);
        }
    }
};
</script>
