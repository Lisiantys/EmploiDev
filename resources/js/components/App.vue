<template>
    <Top />
    <Header />
    <div class="p-6 sm:p-10 text-2xl font-bold md:pl-32">
        <router-view />
    </div>
    <Footer />
</template>

<script setup>
import Top from './Top.vue';
import Header from './Header.vue';
import Footer from './Footer.vue';

import { onMounted } from 'vue';
import { useResourcesStore } from '../stores/resourcesStore';


const resourcesStore = useResourcesStore();

onMounted(() => {
    resourcesStore.fetchAllResources();
});
</script>
