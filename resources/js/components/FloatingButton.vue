<template>
  <button @click="$emit('click')" class="fixed bottom-20 right-5 bg-blue-600 text-white py-3 px-3 rounded-full shadow-lg text-base hover:bg-blue-700 transition duration-300 ease-in-out flex items-center z-50">
    <img class="h-8 w-8" :src=baseImageUrl alt="Logo" />
  </button>
</template>

<script setup>
import { ref } from 'vue'; 
const baseImageUrl = ref('/storage/images/plus-solid.svg');
</script>

<style scoped>
img{
  filter: invert(100%) sepia(100%) saturate(0%);
}
</style>