import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null, // Stocke les informations de l'utilisateur
        isAuthenticated: false, // Statut d'authentification
    }),
    persist: true, // Utilise le stockage persistant
    actions: {
        setUser(user) {
            this.user = user;
            this.isAuthenticated = true;
        },
        logout() {
            this.user = null;
            this.isAuthenticated = false;
        },
    },
});
