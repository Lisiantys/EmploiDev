import { defineStore } from 'pinia';
import Axios from 'axios';

export const useDevelopersStore = defineStore('developers', {
    state: () => ({
        developers: [],
        currentPage: 1,
        totalPages: 1,
    }),
    actions: {
        async fetchDevelopers(filters = {}, page = 1) {
            try {
                const params = { ...filters, page: page };
                const response = await Axios.get("/api/developers/filter", { params });
                this.developers = response.data.data;
                this.totalPages = response.data.last_page;
                this.currentPage = response.data.current_page;
            } catch (error) {
                console.error("Failed to fetch developers:", error.response ? error.response.data : error);
            }
        },
        reset() {
            this.developers = [];
            this.currentPage = 1;
            this.totalPages = 1;
        }
    }
});
