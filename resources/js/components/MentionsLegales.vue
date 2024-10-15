<template title="Mentions Légales">


    <div class="hidden md:block mt-20 mb-6">
        <PageTitle title="// Mentions Légales" />
    </div>

    <div class="space-y-4 mt-20 md:mt-0">
        <h2 class="font-semibold text-lg md:text-2xl">Propriétaire du site : </h2>

        <p>EI Maxime POUZARGUE</p>

        <p>Adresse : 237 Bis Avenue François Mitterand, 85340 Les Sables d'Olonne.</p>

        <p>Contact : contact@winterbike24.fr</p>
    </div>

    <div class="space-y-4 mt-5">
        <h2 class="font-semibold text-lg md:text-2xl">Propriétés intellectuelles</h2>
        <p class="leading-9">
            Maxime POUZARGUE est propriétaire des droits de propriété intellectuelle et détient les droits d’usage
            sur les éléments accessibles sur le site internet, notamment les textes, et les logos.
            <br>
            Pour les icons :
            Font Awesome : <a href="https://fontawesome.com/"
                class="text-dark-green font-semibold">https://fontawesome.com/</a>
            <br>
            Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments
            du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable
            de Maxime POUZARGUE.
            <br>
            Toute exploitation non autorisée du site ou de l’un quelconque des éléments qu’il contient sera
            considérée comme constitutive d’une contrefaçon et poursuivie conformément aux dispositions des articles
            L.335-2 et suivants du Code de Propriété Intellectuelle.
        </p>
    </div>

    <div class="space-y-4 mt-5">
        <h2 class="font-semibold text-lg md:text-2xl">Hébergeur :</h2>
        <p>o2switch – <a href="https://www.o2switch.fr/"
                class="text-dark-green font-semibold">https://www.o2switch.fr/</a> Chem. des Pardiaux, 63000
            Clermont-Ferrand – Téléphone : 0444446040</p>
    </div>

</template>


<script setup>
import PageTitle from './PageTitle.vue'; 
</script>

<style scoped>
p {
    font-size: 14px;
}

@media screen and (max-width: 768px) {
    p {
        font-size: 12px;
    }
}
</style>
