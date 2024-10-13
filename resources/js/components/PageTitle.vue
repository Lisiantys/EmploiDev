<template>
    <h1 class="text-3xl lg:text-4xl font-bold">{{ title }}</h1>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  const props = defineProps({
    title: {
      type: String,
      required: true,
    },
  });
  </script>
  
  