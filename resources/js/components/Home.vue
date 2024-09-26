<template>
    <div>
        <h2>This is my home</h2>
        <div v-if="developers.length">
            <h3>Developers</h3>
            <ul>
                <li v-for="developer in developers" :key="developer.id">
                    {{ developer.first_name }} - {{ developer.is_free ? 'Available' : 'Not Available' }}
                </li>
            </ul>
        </div>
        <div v-else>
            <p>No developers available.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Axios from 'axios';

const developers = ref([]);

const fetchDevelopers = async () => {
    try {
        const response = await Axios.get('/api/developers');
        console.log(response.data);
        developers.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch developers:', error);
    }
};

onMounted(fetchDevelopers);
</script>
