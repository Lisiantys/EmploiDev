<template>
    <div class="w-full max-w-md bg-white shadow-md rounded-lg mt-16 p-6 m-auto ">

        <form @submit.prevent="login">
            <div class="block mb-6">
                <PageTitle title="// Connexion" />
            </div>

            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">E-mail...</label>
                <input v-model="credentials.email" id="email" type="email"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required maxlength="255" />
            </div>

            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Mot de passe...</label>
                <input v-model="credentials.password" id="password" type="password"
                    class="shadow-sm bg-gray-50 border border-blue-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required minlength="8" />
            </div>

            <div v-if="loginError" class="mb-5 p-4 border border-red-500 rounded bg-red-100">
                <ul class="list-disc list-inside">
                    <li class="text-red-500">{{ loginError }}</li>
                </ul>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="border border-blue-500 bg-blue-500 text-white font-normal text-base rounded-lg px-2 py-1 hover:bg-blue-600 transition">
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
import PageTitle from './PageTitle.vue';
import { useGlobalNotify } from '../notifications/useGlobalNotify';

const router = useRouter();
const authStore = useAuthStore();
const notify = useGlobalNotify();
const loginError = ref('');

const credentials = ref({
    email: '',
    password: ''
});

const login = async () => {
    try {
        await Axios.get('/csrf-cookie');
        const response = await Axios.post('/api/login', credentials.value);
        authStore.setUser(response.data.user)
        router.push('/');

        notify({
            group: 'success-action',
            type: 'success',
            title: 'Succès',
            text: 'Connexion réussie !'
        });
    } catch (error) {
        if (error.response && error.response.status === 401) {
            loginError.value = error.response.data.message;
        }
    }
};
</script>
