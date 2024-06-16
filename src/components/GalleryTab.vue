<script lang="ts">
import env from '@/assets/scripts/config';

export default {
    data() {
        return {
            currentTheme: localStorage.getItem("theme") as string,
            publicPath: env.host.split("/")[0] + "/"
        }
    },
    props: {
        show: {type: Boolean}
    }
};
</script>

<template>
    <div v-if="show" class="container">
        <h1>Gallery</h1>

        <div class="container">
            <div id="no-gallery-contents" align="center">
                <img :src="`${publicPath}images/cat-delighted-${currentTheme}.png`" width="120" />
                <p>No gallery contents yet.</p>
            </div>
        </div>

        <button class="floating-btn shadow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="5.0" stroke="currentColor" width="18" height="18" class="mb-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </button>
    </div>
</template>

<style scoped>
.floating-btn {
    position: fixed;
    right: 60px;
    bottom: 60px;
    width: 60px;
    height: 60px;
    background-color: #1a172a;
    color: var(--bs-white) !important;
    border: none;
    border-radius: 100%;
}

@media only screen and (max-width: 771px) {
    .floating-btn {
        right: 30px;
        bottom: 30px;
    }
}
</style>