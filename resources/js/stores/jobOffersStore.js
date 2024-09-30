import { defineStore } from 'pinia';
import Axios from 'axios';

export const useJobOffersStore = defineStore('jobOffers', {
    state: () => ({
        jobOffers: [],
        currentPage: 1,
        totalPages: 1,
    }),
    actions: {
        async fetchJobOffers(filters = {}, page = 1) {
            try {
                const params = { ...filters, page: page };
                const response = await Axios.get("/api/job-offers/filter", { params });
                this.jobOffers = response.data.data;
                this.totalPages = response.data.last_page;
                this.currentPage = response.data.current_page;
            } catch (error) {
                console.error("Failed to fetch jobs:", error.response ? error.response.data : error);
            }
        },
        reset() {
            this.jobOffers = [];
            this.currentPage = 1;
            this.totalPages = 1;
        }
    }
});
