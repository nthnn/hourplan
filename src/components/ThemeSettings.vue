<script setup lang="ts">
import { detectMobile } from '@/assets/scripts/mobile_detect';
</script>

<template>
    <h1 v-if="!detectMobile()">Theme Settings</h1>
</template>