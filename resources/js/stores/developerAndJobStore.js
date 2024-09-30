import { defineStore } from 'pinia';
import Axios from 'axios';

export const useDeveloperAndJobStore = defineStore('developerAndJob', {
    state: () => ({
        developers: [],
        jobOffers: [],
        currentPage: 1,
        totalPages: 1,
        isDeveloper: true, // Flag to determine which resource to fetch
    }),
    actions: {
        async fetchResources(filters = {}, page = 1) {
            try {
                const params = { ...filters, page: page };
                const endpoint = this.isDeveloper ? "/api/developers/filter" : "/api/job-offers/filter";
                
                console.log(`Fetching from: ${endpoint} with params:`, params); // Log the endpoint and parameters
                
                const response = await Axios.get(endpoint, { params });
                
                console.log("API Response:", response.data); // Log the full response
                
                if (this.isDeveloper) {
                    this.developers = response.data.data;
                    this.totalPages = response.data.last_page;
                    this.currentPage = response.data.current_page;
                    console.log("Developers fetched:", this.developers); // Log developers
                } else {
                    this.jobOffers = response.data.data;
                    this.totalPages = response.data.last_page;
                    this.currentPage = response.data.current_page;
                    console.log("Job offers fetched:", this.jobOffers); // Log job offers
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
        setIsDeveloper(value) {
            this.isDeveloper = value;
        },
    }
});
