<template>
    <form @submit.prevent="nextStep">
        <h2>Inscription Entreprise</h2>
        <div v-if="step === 1" class="tab">
            <p><input v-model="company.name" placeholder="Nom de l'entreprise..." required /></p>
            <p><input v-model="company.email" placeholder="E-mail..." required /></p>
        </div>

        <div v-if="step === 2" class="tab">
            <p><input v-model="company.phone" placeholder="Téléphone..." required /></p>
            <p><input v-model="company.address" placeholder="Adresse..." required /></p>
        </div>

        <div v-if="step === 3" class="tab">
            <p><input v-model="company.password" placeholder="Mot de passe..." type="password" required /></p>
            <p><input v-model="company.confirmPassword" placeholder="Confirmer le mot de passe..." type="password" required /></p>
        </div>

        <div class="flex justify-between">
            <button type="button" @click="prevStep" v-if="step > 1">Précédent</button>
            <button type="submit" v-if="step < 3">Suivant</button>
            <button type="submit" v-if="step === 3">S'inscrire</button>
        </div>
    </form>
</template>

<script setup>
import { ref } from 'vue';

const step = ref(1);
const company = ref({
    name: '',
    email: '',
    phone: '',
    address: '',
    password: '',
    confirmPassword: ''
});

const nextStep = () => {
    if (step.value < 3) {
        step.value++;
    } else {
        // Handle the final submission for company registration
        console.log('Inscription Entreprise:', company.value);
    }
};

const prevStep = () => {
    if (step.value > 1) {
        step.value--;
    }
};
</script>

<style scoped>
/* Add styles for your company registration form here */
</style>
