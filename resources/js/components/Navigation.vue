<template>
    <div class="flex flex-row md:flex-col text-base bg-blue-500 md:h-full text-white md:py-7 fixed custom-sidebar z-50">
        <router-link :to="{ name: 'home' }" href="#" id="logo-link"
            class="w-full h-16 flex items-center justify-center">
            <img class="w-14" :src="`${baseImageUrl}logo.webp`" alt="logo" />
            <h1 class="sidebar-text logo-text font-bold text-xl animate-charcter">
                EmploiDev
            </h1>
        </router-link>

        <router-link v-if="isAuthenticated && isDeveloper" :to="{ name: 'developerProfile' }"
            class="w-full h-16 flex items-center justify-center">
            <img :src="`${baseImageUrl}user-solid.svg`" class="svg-icon" alt="user" />
            <p class="sidebar-text">Profil-Dev</p>
        </router-link>

        <router-link v-if="isAuthenticated && isCompany" :to="{ name: 'companyProfile' }"
            class="w-full h-16 flex items-center justify-center">
            <img :src="`${baseImageUrl}user-solid.svg`" class="svg-icon" alt="company" />
            <p class="sidebar-text">Profil-Comp</p>
        </router-link>

        <router-link v-if="isAuthenticated && isDeveloper" :to="{ name: 'developerApplication' }"
            class="w-full h-16 flex items-center justify-center">
            <img :src="`${baseImageUrl}list-ul-solid.svg`" class="svg-icon" alt="applications" />
            <p class="sidebar-text">Candidatures</p>
        </router-link>

        <router-link v-if="isAuthenticated && isCompany" :to="{ name: 'companyJobOffer' }"
            class="w-full h-16 flex items-center justify-center">
            <img :src="`${baseImageUrl}list-ul-solid.svg`" class="svg-icon" alt="offers" />
            <p class="sidebar-text">Vos offres</p>
        </router-link>

        <router-link v-if="isAuthenticated && isAdmin" :to="{ name: 'adminDashboard' }"
            class="w-full h-16 flex items-center justify-center">
            <img :src="`${baseImageUrl}list-ul-solid.svg`" class="svg-icon" alt="dashboard" />
            <p class="sidebar-text">Dashboard</p>
        </router-link>

        <router-link :to="{ name: 'home' }" class="w-full h-16 flex items-center justify-center">
            <img :src="`${baseImageUrl}id-card-regular.svg`" class="svg-icon" alt="id-card" />
            <p class="sidebar-text">Développeurs</p>
        </router-link>

        <router-link :to="{ name: 'job' }" class="w-full h-16 flex items-center justify-center">
            <img :src="`${baseImageUrl}building-solid.svg`" class="svg-icon" alt="jobs" />
            <p class="sidebar-text">Emplois</p>
        </router-link>

        <router-link v-if="!isAuthenticated" :to="{ name: 'login' }"
            class="w-full h-16 flex items-center justify-center">
            <img :src="`${baseImageUrl}right-to-bracket-solid.svg`" class="svg-icon" alt="login" />
            <p class="sidebar-text">Connexion</p>
        </router-link>

        <router-link v-if="!isAuthenticated" :to="{ name: 'register' }"
            class="w-full h-16 flex items-center justify-center">
            <img :src="`${baseImageUrl}user-plus-solid.svg`" class="svg-icon" alt="register" />
            <p class="sidebar-text">Inscription</p>
        </router-link>

        <router-link v-if="isAuthenticated" :to="{ name: 'logout' }" @click="logout"
            class="w-full h-16 flex items-center justify-center">
            <img :src="`${baseImageUrl}power-off-solid.svg`" class="svg-icon" alt="logout" />
            <p class="sidebar-text">Déconnexion</p>
        </router-link>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useAuthStore } from "../stores/authStore";

const authStore = useAuthStore();
const baseImageUrl = ref("/storage/images/");
const isAuthenticated = computed(() => authStore.isAuthenticated);

// Vérifier les rôles de l'utilisateur
const isDeveloper = computed(() => authStore.user?.role_id === 1);
const isCompany = computed(() => authStore.user?.role_id === 2);
const isAdmin = computed(() => authStore.user?.role_id === 3);

const logout = () => {
    authStore.logout();
};
</script>

<style scope>
.custom-sidebar {
    width: 80px;
    transition: width 0.3s ease-in-out;
}

.custom-sidebar a {
    text-decoration: none;
}

.custom-sidebar a:hover {
    background-color: #2e67c3;
}

@media screen and (max-width: 768px) {
    .custom-sidebar {
        width: 100%;
        bottom: 0;
    }

    .custom-sidebar a p,
    .custom-sidebar #logo-link {
        display: none;
    }

    .svg-icon {
        width: 24px;
        height: 24px;
        filter: invert(100%) sepia(100%) saturate(0%);
    }
}

@media screen and (min-width: 768px) {
    .custom-sidebar:hover {
        width: 12.5rem;
    }

    .custom-sidebar a img {
        transition: transform 0.3s ease-in-out;
    }

    .custom-sidebar:hover a img {
        transform: translateX(-60px);
    }

    .svg-icon {
        width: 28px;
        height: 28px;
        filter: invert(100%) sepia(100%) saturate(0%);
    }

    .logo-text {
        font-size: 22px !important;
    }

    .sidebar-text {
        color: white;
        position: absolute;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out,
            transform 0.3s ease-in-out;
        white-space: nowrap;
        transform: translateX(-20px);
        margin-left: 1rem;
        font-size: 13px;
    }

    .custom-sidebar:hover .sidebar-text {
        display: flex;
        opacity: 1;
        visibility: visible;
        transform: translateX(10px);
    }
}
</style>
