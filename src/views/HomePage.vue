<script setup lang="ts">
import Navbar from "../components/Navbar.vue";
import { useRouter } from "vue-router";
import {
    validateCurrentSession
} from "@/assets/scripts/session";

const router = useRouter();
validateCurrentSession(router);
setInterval(()=> validateCurrentSession(router), 1000);
</script>

<template>
    <Navbar />
</template>
