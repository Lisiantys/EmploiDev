import { defineStore } from 'pinia';
import Axios from 'axios';

export const useResourcesStore = defineStore('resources', {
    state: () => ({
        contracts: [],
        developerTypes: [],
        programmingLanguages: [],
        localisations: [],
        yearsExperiences: [],
    }),
    actions: {
        async fetchContracts() {
            if (this.contracts.length === 0) {
                try {
                    const response = await Axios.get("/api/types-contracts");
                    this.contracts = response.data;
                } catch (error) {
                    console.error("Failed to fetch contracts:", error);
                }
            }
        },
        async fetchDeveloperTypes() {
            if (this.developerTypes.length === 0) {
                try {
                    const response = await Axios.get("/api/types-developers");
                    this.developerTypes = response.data;
                } catch (error) {
                    console.error("Failed to fetch developer types:", error);
                }
            }
        },
        async fetchProgrammingLanguages() {
            if (this.programmingLanguages.length === 0) {
                try {
                    const response = await Axios.get("/api/programming-languages");
                    this.programmingLanguages = response.data;
                } catch (error) {
                    console.error("Failed to fetch programming languages:", error);
                }
            }
        },
        async fetchLocations() {
            if (this.localisations.length === 0) {
                try {
                    const response = await Axios.get("/api/locations");
                    this.localisations = response.data;
                } catch (error) {
                    console.error("Failed to fetch locations:", error);
                }
            }
        },
        async fetchYearsExperiences() {
            if (this.yearsExperiences.length === 0) {
                try {
                    const response = await Axios.get("/api/years-experiences");
                    this.yearsExperiences = response.data;
                } catch (error) {
                    console.error("Failed to fetch years of experience:", error);
                }
            }
        },
        async fetchAllResources() {
            await Promise.all([
                this.fetchContracts(),
                this.fetchDeveloperTypes(),
                this.fetchProgrammingLanguages(),
                this.fetchLocations(),
                this.fetchYearsExperiences()
            ]);
        }
    }
});
