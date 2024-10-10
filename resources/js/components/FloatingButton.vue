<!-- FloatingButton.vue -->
<template>
  <button @click="$emit('click')" class="floating-button">
    <slot>+ Ajouter</slot>
  </button>
</template>

<script setup>
// Aucun script nécessaire
</script>

<style scoped>
.floating-button {
  position: fixed;
  bottom: 20px;
  /* Ajustez selon vos besoins */
  right: 20px;
  /* Ajustez selon vos besoins */
  background-color: #3b82f6;
  /* Bleu Tailwind (bg-blue-500) */
  color: white;
  padding: 12px 20px;
  border-radius: 50px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  z-index: 1000;
  /* Assurez-vous que le bouton est au-dessus des autres éléments */
}

.floating-button:hover {
  background-color: #2563eb;
  /* Bleu foncé Tailwind (bg-blue-600) */
}
</style>
