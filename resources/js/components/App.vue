<template>
    <Top />
    <Header />
    <router-view />
    <Footer />
</template>

<script setup>
import Top from './Top.vue';
import Header from './Header.vue';
import Footer from './Footer.vue';
</script>
