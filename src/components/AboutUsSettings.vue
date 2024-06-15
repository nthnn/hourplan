<script setup lang="ts">
import { detectMobile } from '@/assets/scripts/mobile_detect';
</script>

<template>
    <h1 v-if="!detectMobile()">About Us</h1>
    <hr class="desktop-only"/>

    <div class="container">
        <p class="text-lato">Greetings!</p>
        <p class="text-lato">We are JMFV and we are multimedia art students from STI College Cubao. We are conducting a research about the time allocation of STI students between academic pursuits and artistic endeavors. That is why we created this website, so that we can help fellow art students in allocating their time for school and their artistic endeavors. We believe that education is important but also your passion as an artist. That is why hope that this website may help you in managing and planning your available time. </p>
        <p class="text-lato">If you want to give us feedback regarding our website, please answer the google forms in the Send Feedback button. Thank you and we hope that with our website you can have more time to create your arts. </p>
    </div>
</template>