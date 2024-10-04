<template>
    <footer class="absolute w-full bg-blue-500 p-4 md:pl-16 lg:pl-10 md:py-8 pb-24 md:mb-0 -z-20">
        <div
            class="grid grid-flow-row gap-4 space-y-5 md:space-y-0 items-center justify-items-center text-center md:grid-flow-col md:text-left">
            <div class="text-white max-w-md space-y-4 md:space-y-2">
                <h3 class="font-extrabold text-xl tracking-wider">EmploiDev</h3>
                <p class="text-sm sm:text-base leading-7">
                    Développez votre carrière avec EmploiDev, la plateforme de recrutement dédiée aux développeurs informatiques. Trouvez des opportunités d'alternance et des offres d'emploi en développement logiciel. Rejoignez des entreprises technologiques de pointe et explorez des carrières enrichissantes dans le secteur numérique.
                </p>
            </div>

            <ul class="text-base font-medium text-white space-y-4  md:space-y-5 ">
                <li class="space-x-3">
                    <i class="fa-solid fa-chevron-right" style="color: #ffffff;"></i>
                    <a href="#" class="hover:underline">Accueil </a>
                </li>
                <li class="space-x-3">
                    <i class="fa-solid fa-chevron-right" style="color: #ffffff;"></i>
                    <a href="#" class="hover:underline">Contactez-nous</a>
                </li>
                <router-link :to="{ name: 'mentionsLegales' }" class="space-x-3">
                    <i class="fa-solid fa-chevron-right" style="color: #ffffff;"></i>
                    <a href="#" class="hover:underline">Mentions Légales</a>
                </router-link>
                <router-link :to="{ name: 'politiqueConfidentialite' }"  class="space-x-3">
                    <i class="fa-solid fa-chevron-right" style="color: #ffffff;"></i>
                    <a href="#" class="hover:underline">Politique de Confidentialité</a>
                </router-link>
    
            </ul>
        </div>
        <hr class="my-6 w-full sm:w-1/2 border-white mx-auto lg:my-8" />
        <span class="block text-sm text-white text-center">© 2024 <a href="#" class="hover:underline">EmploiDev</a>.
            Tous droits réservés.</span>
    </footer>
</template>

<script setup> </script>