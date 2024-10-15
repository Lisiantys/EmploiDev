<template>
  <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-blue-600">{{ title }}</h1>
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