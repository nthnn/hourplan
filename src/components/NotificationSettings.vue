<script setup lang="ts">
import { detectMobile } from '@/assets/scripts/mobile_detect';
</script>

<template>
    <h1 v-if="!detectMobile()">Notifications</h1>
    <hr class="desktop-only"/>

    <div class="bg-warning rounded p-2 text-lato">
        Notification setting is only for mobile app version of HourPlan.
    </div>
</template>