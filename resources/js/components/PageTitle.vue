<template>
    <h1 class="page-title">{{ title }}</h1>
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
  
  <style scoped>
  .page-title {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 1rem;
  }
  </style>
  