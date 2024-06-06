<script setup lang="ts">
import { detectMobile } from '@/assets/scripts/mobile_detect';
</script>

<template>
    <h1 v-if="!detectMobile()">Account Settings</h1>
</template>