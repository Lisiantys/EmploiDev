<template>
    <div class="min-h-screen flex flex-col">
      <Top />
      <Header />
      <div class="flex-grow p-6 sm:p-10 text-2xl font-bold md:pl-32">
        <router-view />
      </div>
      <Footer />
    </div>
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
  
  <!-- Pas de styles nécessaires si vous utilisez Tailwind CSS -->
  