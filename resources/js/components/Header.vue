<template>
    <div class="flex flex-row md:flex-col text-base bg-blue-500 md:h-full text-white md:py-7 fixed custom-sidebar z-10">
        <router-link :to="{ name: 'home' }" href="#" id="logo-link"
            class="w-full h-16 flex items-center justify-center">
            <img class="w-14" :src="logoUrl" alt="logo" />
            <h1 class="sidebar-text font-bold text-xl animate-charcter">
                EmploiDev
            </h1>
        </router-link>

        <router-link :to="{ name: 'home' }" class="w-full h-16 flex items-center justify-center">
            <i class="fa-regular fa-id-card fa-xl" style="color: #ffffff;"></i>
            <p class="sidebar-text">Développeurs</p>
        </router-link>

        <router-link :to="{ name: 'job' }" class="w-full h-16 flex items-center justify-center">
            <i class="fa-solid fa-building fa-xl" style="color: #ffffff"></i>
            <p class="sidebar-text">Emplois</p>
        </router-link>

        <router-link v-if="!isAuthenticated" :to="{ name: 'login' }"
            class="w-full h-16 flex items-center justify-center">
            <i class="fa-solid fa-right-to-bracket fa-xl" style="color: #ffffff"></i>
            <p class="sidebar-text">Connexion</p>
        </router-link>

        <router-link v-if="!isAuthenticated" :to="{ name: 'register' }"
            class="w-full h-16 flex items-center justify-center">
            <i class="fa-solid fa-user-plus fa-xl" style="color: #ffffff"></i>
            <p class="sidebar-text">Inscription</p>
        </router-link>

        <router-link v-if="isAuthenticated" :to="{ name: 'logout' }" @click="logout"
            class="w-full h-16 flex items-center justify-center">
            <i class="fa-solid fa-power-off fa-xl" style="color: #ffffff"></i>
            <p class="sidebar-text">Déconnexion</p>
        </router-link>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useAuthStore } from "../stores/authStore";

const authStore = useAuthStore();
const logoUrl = ref("/storage/images/logo.webp");
const isAuthenticated = computed(() => authStore.isAuthenticated);

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
}

@media screen and (min-width: 768px) {
    .custom-sidebar:hover {
        width: 12.5rem;
    }

    .custom-sidebar a i,
    .custom-sidebar a img {
        transition: transform 0.3s ease-in-out;
    }

    .custom-sidebar:hover a i,
    .custom-sidebar:hover a img {
        transform: translateX(-60px);
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
    }

    .custom-sidebar:hover .sidebar-text {
        display: flex;
        opacity: 1;
        visibility: visible;
        transform: translateX(10px);
    }
}
</style>
