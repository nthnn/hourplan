<script setup lang="ts">
import { RouterView } from 'vue-router';
import "@/assets/styles/global.css";
</script>

<script lang="ts">
export default {
    watch: {
        '$route'(to, from) {
            if(to.meta.overflowHidden) {
                document.documentElement.classList.add('overflow-hidden');
                document.body.classList.add('overflow-hidden');
            }
            else {
                document.documentElement.classList.remove('overflow-hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
    },

    mounted() {
        if(this.$route.meta.overflowHidden) {
            document.documentElement.classList.add('overflow-hidden');
            document.body.classList.add('overflow-hidden');
        }
    }
}
</script>

<template>
    <div :class="{'overflow-hidden': $route.meta.overflowHidden}">
        <RouterView />
    </div>
</template>