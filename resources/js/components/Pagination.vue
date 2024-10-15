<template>
  <div v-if="totalPages > 1" class="flex justify-between items-center mt-4">
    <button @click="previousPage" :disabled="currentPage === 1"
      class="border-2 border-blue-500 text-blue-500 bg-transparent text-sm md:text-base font-normal rounded-lg px-2 py-1 hover:bg-blue-50 transition">
      Précédent
    </button>

    <span class="text-sm md:text-base">Page {{ currentPage }} / {{ totalPages }}</span>

    <button @click="nextPage" :disabled="currentPage === totalPages"
      class="border-10  border-blue-500 bg-blue-500 text-white  text-sm md:text-base rounded-lg px-2 py-1 hover:bg-blue-600 transition">
      Suivant
    </button>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  totalPages: {
    type: Number,
    required: true,
  }
});

const emit = defineEmits(['updatePage']);

const previousPage = () => {
  if (props.currentPage > 1) {
    emit('updatePage', props.currentPage - 1);
  }
};

const nextPage = () => {
  if (props.currentPage < props.totalPages) {
    emit('updatePage', props.currentPage + 1);
  }
};
</script>

<style scoped>
button:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}
</style>