<template>
    <footer class="w-full bg-blue-500 p-4 md:pl-16 lg:pl-10 md:py-8 pb-24 md:mb-0">
      <div
        class="grid grid-flow-row gap-4 space-y-5 md:space-y-0 items-center justify-items-center text-center md:grid-flow-col md:text-left">
        <div class="text-white max-w-md space-y-4 md:space-y-2">
          <h3 class="font-extrabold text-xl tracking-wider">EmploiDev</h3>
          <p class="text-sm sm:text-base leading-7">
            Développez votre carrière avec EmploiDev, la plateforme de recrutement dédiée aux développeurs
            informatiques. Trouvez des opportunités d'alternance et des offres d'emploi en développement
            logiciel. Rejoignez des entreprises technologiques de pointe et explorez des carrières
            enrichissantes dans le secteur numérique.
          </p>
        </div>
  
        <ul class="text-base font-medium text-white">
          <li>
            <div class="link-list">
              <img :src="imageUrl" class="svg-icon" alt="#" />
              <a href="#" class="hover:underline">Accueil </a>
            </div>
          </li>
          <li>
            <div class="link-list">
              <img :src="imageUrl" class="svg-icon" alt="#" />
              <a href="#" class="hover:underline">Contactez-nous</a>
            </div>
          </li>
          <router-link :to="{ name: 'mentionsLegales' }">
            <div class="link-list">
              <img :src="imageUrl" class="svg-icon" alt="#" />
              <a href="#" class="hover:underline">Mentions Légales</a>
            </div>
          </router-link>
          <router-link :to="{ name: 'politiqueConfidentialite' }">
            <div class="link-list">
              <img :src="imageUrl" class="svg-icon" alt="#" />
              <a href="#" class="hover:underline">Politique de Confidentialité</a>
            </div>
          </router-link>
        </ul>
      </div>
      <hr class="my-6 w-full sm:w-1/2 border-white mx-auto lg:my-8" />
      <span class="block text-sm text-white text-center">© 2024 <a href="#" class="hover:underline">EmploiDev</a>.
        Tous droits réservés.</span>
    </footer>
  </template>
  
  <script setup>
  import { ref } from "vue";
  const imageUrl = ref("/storage/images/chevron-right-solid.svg");
  </script>
  
  <style scoped>
  .link-list {
    display: flex;
    align-items: center;
    gap: 5px;
    padding-top: 16px;
  }
  
  .svg-icon {
    width: 16px;
    height: 16px;
  }
  </style>
  