import { defineStore } from 'pinia';
import Axios from 'axios';

//Récupération entre les développeurs ou les offres d'emploi
export const useDeveloperAndJobStore = defineStore('developerAndJob', {
    state: () => ({
        developers: [],
        jobOffers: [],
        currentPage: 1,
        totalPages: 1,
        isDeveloper: true,
    }),
    actions: {
        /**
         * Récupère les développeurs ou les offres d'emploi en fonction du flag isDeveloper ou isJobOffers.
         */
        async fetchResources(filters = {}, page = 1) {
            try {
                const params = { ...filters, page: page };
                const endpoint = this.isDeveloper ? "/api/developers/filter" : "/api/job-offers/filter";

                const response = await Axios.get(endpoint, { params });

                if (this.isDeveloper) {
                    this.developers = response.data.data;
                    this.totalPages = response.data.last_page;
                    this.currentPage = response.data.current_page;
                } else {
                    this.jobOffers = response.data.data;
                    this.totalPages = response.data.last_page;
                    this.currentPage = response.data.current_page;
                }
            } catch (error) {
                console.error("Failed to fetch resources:", error.response ? error.response.data : error);
            }
        },
        reset() {
            this.developers = [];
            this.jobOffers = [];
            this.currentPage = 1;
            this.totalPages = 1;
        },
        /**
         * Définit le flag isDeveloper pour déterminer quelle ressource récupérer.
         * @param {Boolean} value 
         */
        setIsDeveloper(value) {
            this.isDeveloper = value;
        },
    }
});
