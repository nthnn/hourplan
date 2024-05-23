<script setup lang="ts">
import { useRouter } from "vue-router";
const router = useRouter();

setTimeout(
    ()=> router.push("/login"),
    3000
);
</script>

<template>
    <div class="d-flex align-items-center justify-content-center min-vh-100 w-100">
        <img src="@/assets/images/splash_logo.png" width="256" height="256" />
    </div>
</template>
